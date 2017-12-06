<?php

namespace App\Template;

use App\Stats\YMetric;
use Carbon\Carbon;

class BouncePagesBlock extends TemplateBlockExtension
{
    public function listProperties()
    {
        return [
            'min_visits' => [
                'type' => 'integer',
                'value' => 0
            ],
            'min_bounce' => [
                'type' => 'integer',
                'value' => 0
            ]
        ];
    }

    public function getData($requestData, $reportId)
    {
        parent::getData($requestData, $reportId);

        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'content_entrance',
            'dimensions' => 'ym:s:startURLHash',
            'days' => $this->days,
            'metrics' => 'ym:s:visits,ym:s:bounceRate,ym:s:pageDepth,ym:s:avgVisitDurationSeconds',
        ]);

        $min_visits = $requestData['templateBlock']->min_visits ? $requestData['templateBlock']->min_visits : 0;
        $min_bounce = $requestData['templateBlock']->min_bounce ? $requestData['templateBlock']->min_bounce : 0;

        $output = [];
        $idx = 1;
        foreach($metricData->data as $data){

            if($data->metrics['1'] >= $min_bounce && $data->metrics['0'] >= $min_visits){
                $outputRow = array_prepend(array_prepend($data->metrics, $data->dimensions['0']->name), $idx);
                $outputRow['3'] = round($outputRow['3'], 2);
                $outputRow['4'] = round($outputRow['4'], 2);
                $outputRow['5'] = str_pad(floor(($outputRow['5']) / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad((ceil($outputRow['5']) % 60), 2, '0', STR_PAD_LEFT);
                $output[] = $outputRow;
                $idx ++;
            }

        }

        return view('reports.xml.table.maxBounce', ['maxBouncePages' => $output])->render();
    }


}
