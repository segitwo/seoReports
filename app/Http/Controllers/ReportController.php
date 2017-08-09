<?php

namespace App\Http\Controllers;

require base_path('vendor/autoload.php');

use App\Stats\MetricStats;
use Carbon\Carbon;
use CpChart\Data;
use CpChart\Image;

use App\Http\Requests\AutoTextRequest;
use App\Http\Requests\ReportFormRequest;
use App\Project;
use App\Stats\AutoText;
use App\Stats\Word;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    function index(){

        $data = [];
        $projects = Project::all();

        $data['projects'] = $projects;

        return view('reports.create', $data);
    }

    function create(ReportFormRequest $request){

        $siteKey = $request->input('siteid');
        $rinkingKey = $request->input('se_ranking');


        $today = Carbon::parse($request->input('date'));

        $dataOutput["sitename"] = $request->input('sitename');
        $dataOutput["regionName"] = $request->input('regionName');
        $dataOutput["today"] = $today->format('d.m.Y');

        $prevDay = clone $today;

        $prevDay->modify('-1 month');
        $prevDay->format('d-m-Y');

        $dataOutput["prevDay"] = $prevDay->format('d.m.Y');

        $MetrikData = new MetricStats($siteKey, $rinkingKey, $today);

        //Общая посещаемость сайта--------------------------------------------------------------------------------------------------------------------------------------------------------------
        $dataOutput["totalVisitsTable"] = $MetrikData->getTotalVisitsData();

        //Глубина просмотров
        $resultData = $MetrikData->getDepthData();
        if(!isset($resultData)) {return;}

        $dataOutput['depthData'] = ['totals' => $resultData];

        //Время на сайте
        $resultData = $MetrikData->getTimeData();

        $timeRows = "";
        foreach($resultData["data"] as $key => $data) {
            $data[2] = round($data[2] / $data["count"], 2);

            $data[3] = gmdate('H:i:s', ceil(($data[3] / $data["count"]) % 86400));

            $data["key"] = $key;
            $timeRows .= view('reports.xml.depthRow', ['data' => $data])->render();
        }

        $dataOutput["timeData"] = ['totals' => $resultData["totals"], "rows" => $timeRows];


        /*Страницы с отказами*/
        $dataOutput["maxBouncePages"] = $MetrikData->getMaxBouncePages();

        /*Популярные посадочные страницы*/
        $dataOutput["mostPopularPages"] = $MetrikData->getMostPopularPages();

        //Позиции
        $dataOutput["positionRows"] = $MetrikData->getPositionsTable();

        //Текст ------------------------------------------------------------------------------------------------------------------------------
        $dataOutput['autotext'] = "";

        $autotext = new AutoText($today);

        if (!empty($request->input('period'))) {
            $dataOutput['autotext'] .= $autotext->getAutoText();
        }

        if(!empty($request->input('dop_work'))){
            $dataOutput['autotext'] .= $autotext->getAutoText("dop");
        }

        if(!empty($request->input('support'))){
            $dataOutput['autotext'] .= $autotext->getSupportText($request->input('support'));
        }

        /*-----------------------------------------------------------------------------*/
        $generalStatistic = [];
        $generalStatistic["firstHalf"] = 0;
        //текущий месяц
        $resultData = [];
        $resultData[] = $MetrikData->getTotalVisitsData();

        //прошлый месяц
        $prePrevDay = clone $prevDay;
        $prePrevDay->modify('-1 month');
        $resultData[] = $MetrikData->getTotalVisitsData([$prePrevDay->format('Y-m-d'), $prevDay->format('Y-m-d')]);

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
        if (!empty($_POST['period'])) {
            $generalStatistic["period"] = $_POST['period'];
            if($generalStatistic["grouth"] == "up"){
                if(in_array($_POST['period'], [2,3,4,5,6])){
                    $generalStatistic["firstHalf"] = 1;
                    $firstHalfText = ["Как видно", "Из поисковой статистики следует, что", "Мы наблюдаем, что", "Заметно, что", "Мы видим, что"];
                    $rand_key = array_rand($firstHalfText, 1);
                    $generalStatistic["firstHalfText"] = $firstHalfText[$rand_key];
                }

                if($_POST['period'] == 2){
                    $generalStatistic["secondMonthText"] = "При грамотной настройке сайта в поисковой выдаче сильно растет количество фраз, по которым сайт могут находить пользователи.";
                }
            }
        }

        $resultData = $MetrikData->getSEData();
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

        /*------------------------------------------------------------------------------------*/

        $commoninfo = "";
        if($request->input('commoninfo')){
            $commoninfo = view('reports.xml.paragraph', ['val' => 'Каждый месяц мы продолжаем вести постоянный мониторинг сайта на наличие технических ошибок, вирусов, взломов, нарушений и сбоев со стороны хостинга, наличие дублей и ошибок сканирования. Проводится периодическая проверка позиций сайта, анализ изменений в выдаче и внесение соответствующих корректировок'])->render();
            //$commoninfo .= view('reports.xml.paragraph')->render();
        }

        $generalStatistic['prevDay'] = $dataOutput["prevDay"];
        $generalStatistic['today'] = $dataOutput["today"];

        $dataOutput['autotext'] = view('reports.xml.generalStatistic', $generalStatistic)->render() . $dataOutput['autotext'] . $commoninfo;

        if(!empty($request->input('next_work'))){
            $dataOutput['autotext'] .= $autotext->getNextWorkText($request->input('next_work'));
        }

        $unicId = uniqid();


        $filesystem = new Filesystem();
        if(!$filesystem->copyDirectory(app_path('Stats/word/'), app_path('Stats/' . $unicId . '/'))){return;};

        /*Визиты ГРАФИК*/
        $this->makeTotalVisitsChart($dataOutput["totalVisitsTable"], $unicId);

        /*Источники ГРАФИК*/
        $lines = $MetrikData->sourcesSummary();
        $this->makeSourcesLineChart($lines, $unicId, $today);

        /*Среднии позиции графики*/

        $relsPath = app_path('Stats/' . $unicId . '/word/_rels/document.xml.rels');

        if (file_exists($relsPath)) {

            $lines = $MetrikData->getAveragePositions();

            $rels = simplexml_load_file($relsPath);
            foreach ($lines as $key => $line) {

                //id для названия картинки
                $imageId = random_int(1000, 9999);

                //создаем график с именем $imageId . '.png'
                $this->makeLineChart([$key => $line['charts']], $imageId . '.png', $unicId, $today);

                //Создаем в document.xml.rels отношение где привязываем изображение с графиком идентийкатору.
                $xmlId = 'rId' . $imageId;
                $relationshipXML = new \SimpleXMLElement('<Relationship/>');
                $relationshipXML->addAttribute('Type', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image');
                $relationshipXML->addAttribute('Id', $xmlId);
                $relationshipXML->addAttribute('Target', 'media/' . $imageId . '.png');

                $this->sxml_append($rels, $relationshipXML);

                $rels->asXml($relsPath);

                //Добавляем график
                $seKey = $line['se'] == 'Яндекс' ? 'rId31' : 'rId33';

                $dataOutput['averageCharts'][] = ['searchEngine' => $key, 'seKey' => $seKey, 'chartId' => $xmlId];
            }

            $lines = $MetrikData->getConversionData();
            foreach ($lines as $key => $line) {

                //id для названия картинки
                $imageId = random_int(1000, 9999);

                //создаем график с именем $imageId . '.png'
                $this->makeLineChart([$key => $line['charts']], $imageId . '.png', $unicId, $today);

                //Создаем в document.xml.rels отношение где привязываем изображение с графиком идентийкатору.
                $xmlId = 'rId' . $imageId;
                $relationshipXML = new \SimpleXMLElement('<Relationship/>');
                $relationshipXML->addAttribute('Type', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image');
                $relationshipXML->addAttribute('Id', $xmlId);
                $relationshipXML->addAttribute('Target', 'media/' . $imageId . '.png');

                $this->sxml_append($rels, $relationshipXML);
                $rels->asXml($relsPath);

                $dataOutput['conversionsCharts'][] = ['conversion' => $key, 'chartId' => $xmlId, 'total' => $line['totals']];
            }

        } else {
            exit('Не удалось открыть файл ' . $relsPath);
        }

        $output = view('reports.xml.newReport', $dataOutput)->render();

        $output = str_replace('<desyatov_mv@mail.ru>', '', $output);

        $xml = simplexml_load_string($output);

        $xml->asXML(app_path('Stats/' . $unicId . '/word/document.xml'));

        $w = new Word($unicId . ".docx", $unicId . "/");
        $w->create();

        $filesystem->deleteDirectory(app_path('Stats/' . $unicId . '/'));

        $file = (app_path('Stats/' . $unicId . '.docx'));

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));

        readfile($file);

        unlink($file);

        exit();

    }

    function getAutoText(AutoTextRequest $request){

        $period = !empty($request->get('period')) ? $request->get('period') : $request->get('dop_work');

        switch($period){
            case 1:
                return view('reports.autotext.month1')->render();
                break;
            case 2:
                return view('reports.autotext.month2')->render();
                break;
            case 7:
                return view('reports.autotext.month7')->render();
                break;
            default:
                return '';
        }
    }

    function generatePreview(Request $request){

        /*$today = Carbon::parse($request->input('date'));

        $siteKey = $request->input('siteid');
        $rinkingKey = $request->input('se_ranking');


        $MetrikData = new MetricStats($siteKey, $rinkingKey, $today);

        $statsData = [];

        //Общая посещаемость сайта--------------------------------------------------------------------------------------------------------------------------------------------------------------
        $statsData['totalVisitsData'] = $MetrikData->getTotalVisitsData();

        //поисковые системы----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        //$statsData['totalSEData'] = $MetrikData->getSEData($today);

        //Глубина просмотров
        $resultData = $MetrikData->getDepthData();
        if(!isset($resultData)) {return;}

        $statsData['depthData'] = ['totals' => $resultData];

        //Время на сайте
        $resultData = $MetrikData->getTimeData();

        $timeRows = [];
        foreach($resultData["data"] as $key => $data) {
            $data[2] = round($data[2] / $data["count"], 2);

            $data[3] = gmdate('H:i:s', ceil(($data[3] / $data["count"]) % 86400));

            $data["key"] = $key;
            $timeRows[] = $data;
        }

        $statsData['timeData'] = ['totals' => $resultData["totals"], "rows" => $timeRows];

        return view('reports.html.index', $statsData);*/
        return view('reports.html.index');
    }

    public function makeLineChart($lines, $name, $unicId, Carbon $today){

        $prevDay = clone $today;
        $prevDay->modify('-1 month');

        $daysInterval = $today->diff($prevDay)->days;

        $axis = [];
        for($i = 0; $i <= $daysInterval; $i ++){
            if(!($i%7)){
                $axis[] = $prevDay->format('d.m.Y');
            } else {
                $axis[] = '';
            }
            $prevDay->modify('+1 day');
        }

        $data = new Data();

        foreach ($lines as $key => $line) {
            $data->addPoints($line, $key);
            $data->setSerieWeight($key, 0.4);
        }

        $data->addPoints($axis, "Labels");
        $data->setSerieDescription("Labels", "Months");
        $data->setAbscissa("Labels");

        /* Create the 1st chart */
        $chart = new Image(800, 350, $data);
        $chart->setFontProperties(array("FontName"=>"../fonts/calibri.ttf","FontSize"=>10));
        $chart->setGraphArea(40,30,750,320);

        $chart->drawScale(
            [
                "CycleBackground"=>TRUE,
                "GridR"=>0,
                "GridG"=>0,
                "GridB"=>0,
                "GridAlpha"=>10,
                "Factors"=>array(8),
                "Mode" => SCALE_MODE_START0
            ]
        );

        $chart->drawLineChart(["DisplayValues" => false, "DisplayColor" => DISPLAY_AUTO]);
        $chart->setShadow(false);

        //$chart->autoOutput("image8");
        $chart->render(app_path('Stats/' . $unicId . '/word/media/' . $name));
    }

    public function makeSourcesLineChart($lines, $unicId, Carbon $today){

        $prevDay = clone $today;
        $prevDay->modify('-1 month');

        $daysInterval = $today->diff($prevDay)->days;

        $axis = [];
        for($i = 0; $i <= $daysInterval; $i ++){
            if(!($i%7)){
                $axis[] = $prevDay->format('d.m.Y');
            } else {
                $axis[] = '';
            }
            $prevDay->modify('+1 day');
        }

        $data = new Data();

        foreach ($lines as $key => $line) {
            $data->addPoints($line, $key);
            $data->setSerieWeight($key, 0.2);
        }

        $data->addPoints($axis, "Labels");
        $data->setSerieDescription("Labels", "Months");
        $data->setAbscissa("Labels");
        $data->setPalette("Labels", ["R"=>229,"G"=>11,"B"=>11]);

        /* Create the 1st chart */
        $chart = new Image(800, 350, $data);
        $chart->setFontProperties(array("FontName"=>"../fonts/calibri.ttf","FontSize"=>10));
        $chart->setGraphArea(40,30,580,320);

        $chart->drawScale(
            [
                "CycleBackground"=>TRUE,
                "GridR"=>0,
                "GridG"=>0,
                "GridB"=>0,
                "GridAlpha"=>10,
                "Factors"=>array(8),
                "Mode" => SCALE_MODE_START0
            ]
        );

        $chart->drawLineChart(["DisplayValues" => false, "DisplayColor" => DISPLAY_AUTO]);
        $chart->setShadow(false);

        /* Write the legend */
        $chart->drawLegend(600, 30, ["Style" => LEGEND_NOBORDER, "Mode" => LEGEND_VERTICAL, "Family" => LEGEND_FAMILY_LINE]);
        //$chart->autoOutput("image8");
        $chart->render(app_path('Stats/' . $unicId . '/word/media/image8.png'));
    }

    private function makeTotalVisitsChart(array $hits, $unicId){
        $data = new Data();
        $data->addPoints($hits,"Hits");
        $data->addPoints(array("Посетители", "Просмотры", "Визиты"),"Labels");
        $data->setAbscissa("Labels");
        /* Create the pChart object */
        $chart = new Image(700,400, $data);


        $chart->setFontProperties(array("FontName"=>"../fonts/calibri.ttf","FontSize"=>10));

        /* Где график расположен */
        $chart->setGraphArea(50,50,680,370);

        $chart->drawScale(
            [
                "CycleBackground"=>TRUE,
                "GridR"=>0,
                "GridG"=>0,
                "GridB"=>0,
                "GridAlpha"=>10,
                "Factors"=>array(8),
                "Mode" => SCALE_MODE_START0
            ]
        );

        $chart->setShadow(TRUE, [
            "X"=>1,
            "Y"=>1,
            "R"=>255,
            "G"=>255,
            "B"=>255,
            "Alpha"=>10
        ]);

        $blue = array("R"=>76,"G"=>176,"B"=>160,"Alpha"=>100);
        $Palette = array("0"=>$blue,"1"=>$blue,"2"=>$blue);

        $chart->drawBarChart(
            array(
                "DisplayValues" => TRUE,
                //"DisplayPos" => LABEL_POS_INSIDE,
                "OverrideColors"=>$Palette,
                "DisplayR" => 0,
                "DisplayG" => 0,
                "DisplayB" => 0,
                "Interleave" => 0.5,

            )
        );

        /* Render the picture (choose the best way) */
        $chart->render(app_path('Stats/' . $unicId . '/word/media/image4.png'));
    }

    private function sxml_append(\SimpleXMLElement $to, \SimpleXMLElement $from) {
        $toDom = dom_import_simplexml($to);
        $fromDom = dom_import_simplexml($from);
        $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
    }
}
