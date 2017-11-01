<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 14.09.2017
 * Time: 15:50
 */

namespace App\Report;


use Carbon\Carbon;
use CpChart\Data;
use CpChart\Image;

class Chart
{

    private function processLineChart($lines, Carbon $today){
        $prevDay = clone $today;
        $prevDay->modify('-1 month');

        $daysInterval = $today->diff($prevDay)->days;

        foreach ($lines[key($lines)] as &$value) {
            $value = round($value);
        }
        unset($value);

        reset($lines);
        $lines[key($lines)] = array_pad(current($lines), -$daysInterval, 0);

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

        return $data;
    }

    private function setDefaultLineChartProperties($data, $scaleSetting = [
        "CycleBackground"=>TRUE,
        "GridR"=>0,
        "GridG"=>0,
        "GridB"=>0,
        "GridAlpha"=>10,
        "Factors"=>array(8)
    ]){
        $chart = new Image(800, 350, $data);
        $chart->setFontProperties(array("FontName"=>"../fonts/calibri.ttf","FontSize"=>10));
        $chart->setGraphArea(40,30,750,320);

        $chart->drawScale($scaleSetting);

        $chart->drawLineChart(["DisplayValues" => false, "DisplayColor" => DISPLAY_AUTO]);
        $chart->setShadow(false);

        return $chart;
    }

    public function makeLineChart($lines, $name, $uniqueId, Carbon $today){

        $data = self::processLineChart($lines, $today);

        $chart = self::setDefaultLineChartProperties($data, [
                "CycleBackground" => TRUE,
                "GridR" => 0,
                "GridG" => 0,
                "GridB" => 0,
                "GridAlpha" => 10,
                "Factors" => array(8),
                "Mode" => SCALE_MODE_START0
            ]
        );
        //$chart->autoOutput("image8");
        $chart->render(app_path('Stats/generated/' . $uniqueId . '/word/media/' . $name));
    }

    public function makeLineChartNegativeDisplay($lines, $name, $uniqueId, Carbon $today){

        $data = self::processLineChart($lines, $today);
        $data->negateValues(array_keys($lines));
        $data->setAxisDisplay(0,AXIS_FORMAT_CUSTOM,[$this, "NegateValuesDisplay"]);

        $chart = self::setDefaultLineChartProperties($data);

        $chart->render(app_path('Stats/generated/' . $uniqueId . '/word/media/' . $name));
    }

    public function NegateValuesDisplay($Value) {
        if ( $Value == VOID ) {
            return VOID;
        } elseif ($Value == 0) {
            return 0;
        } else {
            return -$Value;
        }
    }

}