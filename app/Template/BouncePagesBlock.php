<?php

namespace App\Template;

use App\Stats\YMetric;
use Carbon\Carbon;

class BouncePagesBlock extends TemplateBlockExtension
{
    public function listProperties()
    {
        return [];
    }

    public function getData($requestData, $reportId)
    {
        parent::getData($requestData, $reportId);

        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'content_entrance',
            'dimensions' => 'ym:s:startURLHash',
            'days' => $this->days,
            'metric' => 'ym:s:visits,ym:s:bounceRate,ym:s:pageDepth,ym:s:avgVisitDurationSeconds',
        ]);

        $output = [];
        $idx = 1;
        foreach($metricData->data as $data){
            if($data->metrics['1'] > 20){
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
