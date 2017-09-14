<?php

namespace App\Template;

use App\Stats\YMetric;
use Carbon\Carbon;

use CpChart\Data;
use CpChart\Image;

class SourcesSummary extends TemplateBlockExtension
{
    protected $casts = ['show_if_grown' => 'boolean'];

    public function listProperties(){
        return [
            'hide_if_reduce' => [
                'type' => 'boolean',
                'value' => 0
            ]
        ];
    }

    public function getData($siteKey, $rankingKey, Carbon $today, $reportId){
        parent::getData($siteKey, $rankingKey, $today, $reportId);

        $metricData = YMetric::getData($this->siteKey, [
            'dimensions' => 'ym:s:<attribution>TrafficSource',
            'days' => $this->days,
            'metric' => 'ym:s:visits',
            'link' => '/bytime',
            'group' => 'day',
            'attribution' => 'last'
        ]);

        $lines = $this->processDateData($metricData);

        $this->makeSourcesLineChart($lines, $reportId, $this->today);

        return view('reports.xml.chart.sourcesSummary')->render();
    }

    private function processDateData($data){
        $output = [];
        foreach ($data->data as $row) {
            $key = $row->dimensions['0']->name;

            foreach($row->metrics['0'] as $val){
                $output[$key][] = $val;
            }
        }

        return $output;
    }

    public function makeSourcesLineChart($lines, $unicId, Carbon $today){

        $firstVal = current($lines['Переходы из поисковых систем']);
        $lastVal = end($lines['Переходы из поисковых систем']);
        //if(($firstVal * 1.1) < $lastVal){
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
        //}


    }

    private function makeComment(MetricStats $MetrikData, Carbon $prevDay){
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
