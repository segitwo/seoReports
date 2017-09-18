<?php

namespace App\Template;

use App\Stats\YMetric;
use Carbon\Carbon;

use CpChart\Data;
use CpChart\Image;

class TotalVisitsBlock extends TemplateBlockExtension
{
    public function listProperties()
    {
        return [];
    }

    public function getData($requestData, $reportId){
        parent::getData($requestData, $reportId);

        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'traffic',
            'days' => $this->days,
            'sort' => 'ym:s:date'
        ]);

        if(!isset($metricData)) {
            return '';
        }

        $totalVisitsTable = [];

        if(isset($metricData->totals['0']) || $metricData->totals['1'] || $metricData->totals['2']){
            $totalVisitsTable["guests"] = $metricData->totals['1'];
            $totalVisitsTable["visits"] = $metricData->totals['2'];
            $totalVisitsTable["views"] = $metricData->totals['0'];
        }


        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'deepness_depth',
            'dimensions' => 'ym:s:pageViewsInterval',
            'metric' => 'ym:s:visits,ym:s:pageviews,ym:s:bounceRate,ym:s:pageDepth,ym:s:avgVisitDurationSeconds',
            'days' => $this->days
        ]);

        if(!isset($metricData->totals)) {
            return '';
        }

        $totals = $metricData->totals;
        $totals[2] = round($totals[2], 2);
        $totals[3] = round($totals[3], 2);
        $totals[4] = gmdate("H:i:s", ceil($totals[4]) % 86400);

        $depthData['totals'] = $totals;

        $this->makeTotalVisitsChart($totalVisitsTable, $reportId);
        return view('reports.xml.block.totalVisits', ['totalVisitsTable' => $totalVisitsTable, 'depthData' => $depthData])->render();
    }

    private function makeTotalVisitsChart(array $hits, $reportId){
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
        $chart->render(app_path('Stats/' . $reportId . '/word/media/image4.png'));
    }

}
