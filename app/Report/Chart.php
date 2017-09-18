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

final class Chart
{

    public static function makeLineChart($lines, $name, $unicId, Carbon $today){

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

    /**
     * Chart constructor.
     */
    private function __construct()
    {
    }

    private function __sleep()
    {
        // TODO: Implement __sleep() method.
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }


}