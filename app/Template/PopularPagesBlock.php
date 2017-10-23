<?php

namespace App\Template;


use App\Stats\YMetric;
use Carbon\Carbon;

class PopularPagesBlock extends TemplateBlockExtension
{
    public function listProperties()
    {
        return [
            'limit' => [
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
            'metric' => 'ym:s:visits',
            'sort' => 'ym:s:visits'
        ]);

        $output = [];
        $idx = 1;
        $limit = $requestData['templateBlock']->limit ? $requestData['templateBlock']->limit : INF;
        foreach(array_reverse($metricData->data) as $data){
            if($data->metrics['0'] >= 10){
                $outputRow = array_prepend(array_prepend($data->metrics, $data->dimensions['0']->name), $idx);
                $output[] = $outputRow;
                $idx ++;
                if($idx > $limit){
                    break;
                }
            }
        }

        return view('reports.xml.table.mostPopular', ['mostPopularPages' => $output])->render();
    }


}
