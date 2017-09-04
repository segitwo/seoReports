<?php

namespace App\Template;

use App\Stats\YMetric;
use Illuminate\Database\Eloquent\Model;

class TotalVisitsBlock extends Model
{
    public function templateBlock(){
        return $this->belongsTo('App\Template\TemplateBlock');
    }

    public function getData($days = []){

        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'traffic',
            'days' => $days,
            'sort' => 'ym:s:date'
        ]);

        if(!isset($metricData)) {
            return '';
        }

        $resultData = [];
        $totalGuests = 0;
        $totalVews = 0;
        $totalVisits = 0;

        foreach($metricData->data as $idx => $data) {

            //date
            $dateArray = (array)$data->dimensions[0];
            $date = new \DateTime($dateArray['name']);

            $metricVals = $data->metrics;
            $totalGuests += $metricVals["1"];
            $totalVews += $metricVals["2"];
            $totalVisits += $metricVals["0"];

        }

        if($totalGuests || $totalVews || $totalVisits){
            //$resultData["periods"][] = $this->montharr[$firstDayInMonth->format("m") - 1] . " (" . $firstDayInMonth->format('d.m') . " – " . $date->format('d.m') . ")";
            $resultData["guests"] = $totalGuests;
            $resultData["visits"] = $totalVisits;
            $resultData["vews"] = $totalVews;
        }

        return $resultData;
    }
}
