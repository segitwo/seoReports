<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 15.08.2017
 * Time: 14:34
 */

namespace App\Report;

use App\Template\Template;
use App\Template\TemplateBlock;
use Carbon\Carbon;
use CpChart\Data;
use CpChart\Image;

use App\Stats\MetricStats;
use App\Stats\AutoText;
use App\Stats\Word;

use App\Http\Requests\ReportFormRequest;
use App\Project;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class Report {

    private $xmlBlocks;
    private $filesystem;

    /**
     * Report constructor.
     * @param $xmlBlocks
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }


    function upload($data, $path = ''){

        $unicId = $this->create($data);

        $content = $this->filesystem->get(app_path('Stats/' . $unicId . '.docx'));

        Storage::disk('dropbox')->put($path . '/' . $data['doc_name'] . '.docx', $content);

        $file = (app_path('Stats/' . $unicId . '.docx'));
        unlink($file);
        return true;
    }

    private function addBlockToReport($xmlBlock){
        $this->xmlBlocks .= $xmlBlock;
    }


    function create($requestData){

        $today = Carbon::parse($requestData['date']);

        $prevDay = clone $today;
        $prevDay->modify('-1 month');
        $prevDay->format('d-m-Y');

        $dataOutput["today"] = $today->format('d.m.Y');
        $dataOutput["prevDay"] = $prevDay->format('d.m.Y');


        $dataOutput["regionName"] = $requestData['regionName'];

        //Если регион был незаполнен в настройках то записываем полученное значение.
        $project = Project::find($requestData['id']);
        if(!$project->region){
            $project->region = $requestData['regionName'];
            $project->save();
        }

        if(!$project->template_id){
            return false;
        }

        $dataOutput["sitename"] = $project->url;


        $reportId = uniqid();

        if(!$this->filesystem->copyDirectory(app_path('Stats/word/'), app_path('Stats/' . $reportId . '/'))){return;};

        $template = Template::find($project->template_id);
        $blocks = $template->blocks->sortBy('sortIndex');
        if(count($blocks)){
            foreach ($blocks as $block) {
                $xmlBlock = $block->getOne($block->class_key)->first()->getData($requestData, $reportId);
                $this->addBlockToReport($xmlBlock);
            }
        }

        $dataOutput['blocks'] = $this->xmlBlocks;
        $this->generateReportDocument($dataOutput, $reportId);

        return $reportId;

    }

    private function generateReportDocument($xmlData, $reportId){
        $output = view('reports.xml.newReport', $xmlData)->render();

        $output = str_replace('<desyatov_mv@mail.ru>', '', $output);

        $xml = simplexml_load_string($output);

        $xml->asXML(app_path('Stats/' . $reportId . '/word/document.xml'));

        $w = new Word($reportId . ".docx", $reportId . "/");
        $w->create();

        $this->filesystem->deleteDirectory(app_path('Stats/' . $reportId . '/'));

        return true;
    }

    private function getGeneralStatistic(MetricStats $MetrikData, Carbon $prevDay){
        $generalStatistic = [];
        $generalStatistic["firstHalf"] = 0;
        //текущий месяц
        $resultData = [];
        $resultData[] = $MetrikData->getTotalVisitsData();

        //прошлый месяц
        $prePrevDay = clone $prevDay;
        $prePrevDay->modify('-1 month');
        $resultData[] = $MetrikData->getTotalVisitsData([$prePrevDay->format('Y-m-d'), $prevDay->format('Y-m-d')]);

        if(isset($resultData['1']['guests'])){
            $generalStatisticChange = $resultData['1']['guests'] - $resultData['0']['guests'];
            $generalStatistic["prevGuests"] = $resultData['0']['guests'];
            $generalStatistic["nextGuests"] = $resultData['1']['guests'];
            $generalStatistic["period"] = 0;

            $generalStatistic["grouth"] = "down";
            if($generalStatisticChange > 0){
                $generalStatisticChangePercent = round(($generalStatisticChange * 100) / $resultData['1']['guests'], 2);
                $generalStatistic["percent"] = $generalStatisticChangePercent;
                if($generalStatisticChangePercent > 10){
                    $generalStatistic["grouth"] = "up";
                } else {
                    $generalStatistic["grouth"] = "stable";
                }
            }

            $generalStatistic["firstHalfText"] = "";
            $generalStatistic["secondMonthText"] = "";
            if (!empty($requestData['period'])) {
                $generalStatistic["period"] = $requestData['period'];
                if($generalStatistic["grouth"] == "up"){
                    if(in_array($requestData['period'], [2,3,4,5,6])){
                        $generalStatistic["firstHalf"] = 1;
                        $firstHalfText = ["Как видно", "Из поисковой статистики следует, что", "Мы наблюдаем, что", "Заметно, что", "Мы видим, что"];
                        $rand_key = array_rand($firstHalfText, 1);
                        $generalStatistic["firstHalfText"] = $firstHalfText[$rand_key];
                    }

                    if($requestData['period'] == 2){
                        $generalStatistic["secondMonthText"] = "При грамотной настройке сайта в поисковой выдаче сильно растет количество фраз, по которым сайт могут находить пользователи.";
                    }
                }
            }

            $resultData = $MetrikData->getSEData();

            if(isset($resultData["total"][1])){
                $generalStatistic["prevSEGuests"] = $resultData["total"][0];
                $generalStatistic["nextSEGuests"] = $resultData["total"][1];

                $generalStatisticSEChange = $resultData["total"][1] - $resultData["total"][0];

                $generalStatistic["SEgrouth"] = "down";
                if($generalStatisticSEChange > 0){
                    $generalStatisticChangeSEPercent = round(($generalStatisticSEChange * 100) / $resultData["total"][1], 2);
                    $generalStatistic["SEpercent"] = $generalStatisticChangeSEPercent;
                    if($generalStatisticChangeSEPercent > 10){
                        $generalStatistic["SEgrouth"] = "up";
                    } else {
                        $generalStatistic["SEgrouth"] = "stable";
                    }
                }

                return $generalStatistic;
            }
        }

        return false;

    }
}
