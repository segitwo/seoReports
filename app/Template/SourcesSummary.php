<?php

namespace App\Template;

use App\Stats\YMetric;
use Carbon\Carbon;

use CpChart\Data;
use CpChart\Image;

class SourcesSummary extends TemplateBlockExtension
{
    protected $casts = ['hide_if_reduce' => 'boolean'];

    public function listProperties(){
        return [
            'hide_if_reduce' => [
                'type' => 'boolean',
                'value' => 0
            ]
        ];
    }

    public function getData($requestData, $reportId){
        parent::getData($requestData, $reportId);

        $metricData = YMetric::getData($this->siteKey, [
            'dimensions' => 'ym:s:<attribution>TrafficSource',
            'days' => $this->days,
            'metrics' => 'ym:s:visits',
            'link' => '/bytime',
            'group' => 'day',
            'attribution' => 'last'
        ]);

        $lines = $this->processDateData($metricData);

        $this->makeSourcesLineChart($lines, $reportId, $this->today);

        //Комментарий к источникам трафика
        $comment = $this->makeComment();

        return view('reports.xml.chart.sourcesSummary', ['generalStatistic' => $comment])->render();
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

        /*$firstVal = current($lines['Переходы из поисковых систем']);
        $lastVal = end($lines['Переходы из поисковых систем']);
        //if(($firstVal * 1.1) < $lastVal){*/
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
        $chart->render(app_path('Stats/generated/' . $unicId . '/word/media/image8.png'));
        //}


    }

    private function makeComment(){
        $sourcesStatistic = [];
        $sourcesStatistic["firstHalf"] = 0;

        $stats = [];
        //текущий месяц
        $stats['current'] = $this->getTotalTraffic($this->days);

        //прошлый месяц
        $prePrevDay = clone $this->prevDay;
        $prePrevDay->modify('-1 month');
        $stats['previous'] = $this->getTotalTraffic([$prePrevDay->format('Y-m-d'), $this->prevDay->format('Y-m-d')]);

        $sourcesStatisticChange = $stats['current']['guests'] - $stats['previous']['guests'];
        $sourcesStatistic['prevGuests'] = $stats['previous']['guests'];
        $sourcesStatistic['nextGuests'] = $stats['current']['guests'];
        $sourcesStatistic['period'] = 0;
        $sourcesStatistic['new'] = 0;

        //Если до этого количество посетителей было нулевое, то значение прироста будет равно бесконечности
        if($stats['previous']['guests'] > 0){
            $sourcesStatisticChangePercent = round(($sourcesStatisticChange * 100) / $stats['previous']['guests'], 2);
        } else {
            $sourcesStatisticChangePercent = 100;
            $sourcesStatistic['new'] = 1;
        }
        $sourcesStatistic['percent'] = $sourcesStatisticChangePercent;

        if($sourcesStatisticChangePercent > 10){
            $sourcesStatistic['growth'] = 'up';
        } elseif ($sourcesStatisticChangePercent > -10) {
            $sourcesStatistic['growth'] = 'stable';
        } else {
            $sourcesStatistic['growth'] = 'down';
        }

        $sourcesStatistic['firstHalfText'] = '';
        $sourcesStatistic['secondMonthText'] = '';
        if (!empty($requestData['period'])) {
            $sourcesStatistic['period'] = $requestData['period'];
            if($sourcesStatistic['growth'] == 'up'){
                if(in_array($requestData['period'], [2,3,4,5,6])){
                    $sourcesStatistic['firstHalf'] = 1;
                    $firstHalfText = ['Как видно', 'Из поисковой статистики следует, что', 'Мы наблюдаем, что', 'Заметно, что', 'Мы видим, что'];
                    $rand_key = array_rand($firstHalfText, 1);
                    $sourcesStatistic['firstHalfText'] = $firstHalfText[$rand_key];
                }

                if($requestData['period'] == 2){
                    $sourcesStatistic['secondMonthText'] = 'При грамотной настройке сайта в поисковой выдаче сильно растет количество фраз, по которым сайт могут находить пользователи.';
                }
            }
        }

        $sourcesStatistic['nextSEGuests'] = $this->getTotalSEVisits($this->days);
        $sourcesStatistic['prevSEGuests'] = $this->getTotalSEVisits([$prePrevDay->format('Y-m-d'), $this->prevDay->format('Y-m-d')]);

        $sourcesStatisticSEChange = $sourcesStatistic['nextSEGuests'] - $sourcesStatistic['prevSEGuests'];

        $sourcesStatistic['SEgrowth'] = 'down';
        if($sourcesStatisticSEChange > 0){
            $sourcesStatisticChangeSEPercent = round(($sourcesStatisticSEChange * 100) / $sourcesStatistic['nextSEGuests'], 2);
            $sourcesStatistic['SEpercent'] = $sourcesStatisticChangeSEPercent;
            if($sourcesStatisticChangeSEPercent > 10){
                $sourcesStatistic['SEgrowth'] = 'up';
            } else {
                $sourcesStatistic['SEgrowth'] = 'stable';
            }
        }

        $sourcesStatistic['prevDay'] = $this->prevDay->format('d.m.Y');
        $sourcesStatistic['today'] = $this->today->format('d.m.Y');

        return view('reports.xml.generalStatistic', $sourcesStatistic)->render();
        //return $sourcesStatistic;
    }

    private function getTotalSEVisits($days){
        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'search_engines',
            'days' => $days,
            'sort' => 'ym:s:date',
            'dimensions' => 'ym:s:lastSearchEngineRoot,ym:s:date',
            'filters' => 'ym:s:date!=null',
            'metrics' => 'ym:s:visits'
        ]);

        return isset($metricData->totals[0]) ? $metricData->totals[0] : [];
    }

    private function getTotalTraffic($days){
        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'traffic',
            'days' => $days,
            'sort' => 'ym:s:date'
        ]);
        $resultData = [];
        if(isset($metricData->totals['0']) || $metricData->totals['1'] || $metricData->totals['2']){
            $resultData = [
                'guests' => $metricData->totals['1'],
                'visits' => $metricData->totals['2'],
                'views' => $metricData->totals['0']
            ];
        }
        return $resultData;
    }
}
