<?php

namespace App\Template;

use App\Project;
use App\Stats\YMetric;
use Carbon\Carbon;

class MobileRecommendation extends TemplateBlockExtension
{
    public function listProperties()
    {
        return [
            'start_month' => [
                'type' => 'integer',
                'value' => 1
            ]
        ];
    }

    public function getData($requestData, $reportId)
    {
        parent::getData($requestData, $reportId);

        $output = '';
        $start_month = intval($requestData['templateBlock']->start_month);
        $project = Project::find($requestData['id']);
        $monthsInterval = Carbon::parse($project->start_date)->diffInMonths(Carbon::today());

        if($monthsInterval >= $start_month){
            $bounceRateData = YMetric::getData($this->siteKey, [
                'preset' => 'tech_devices',
                'dimensions' => 'ym:s:mobilePhone',
                'days' => $this->days,
                'metric' => 'ym:s:visits,ym:s:bounceRate'
            ]);

            $bounceRate = round($bounceRateData->totals[2], 2);

            $mobilePercent = $this->getMobilePercentFromYM();

            switch ($monthsInterval - $start_month){
                case 0:
                    $output .= view('reports.xml.paragraph', ['val' => __('text.mobile_recomendation_1', [
                        'percent' => $mobilePercent,
                        'bounceRate' => $bounceRate
                    ])])->render();

                    $output .= view('reports.xml.paragraph', ['val' => __('text.mobile_recomendation_2')])->render();
                    break;
                case 1:
                    $output .= view('reports.xml.paragraph', ['val' => __('text.mobile_recomendation_3', [
                        'percent' => $mobilePercent,
                        'bounceRate' => $bounceRate
                    ])])->render();
                    break;
                default:

                    $mobilePercent_last = $this->getMobilePercentFromYM([(clone $this->prevDay)->modify('-1 month')->format('Y-m-d'), $this->prevDay->format('Y-m-d')]);

                    if($mobilePercent_last < $mobilePercent){
                        $output .= view('reports.xml.paragraph', ['val' => __('text.mobile_recomendation_default', [
                            'mobilePercent_last' => $mobilePercent_last,
                            'mobilePercent' => $mobilePercent,
                            'bounceRate' => $bounceRate
                        ])])->render();
                    }

                    break;
            }

        }

        return $output;
    }

    private function getMobilePercentFromYM($days = []){
        $days = empty($days) ? $this->days : $days;
        $devicesData = YMetric::getData($this->siteKey, [
            'preset' => 'tech_devices',
            'dimensions' => 'ym:s:deviceCategory',
            'days' => $days,
            'metrics' => 'ym:s:visits'
        ]);

        $devices = [
            'desktop' => 0,
            'tablet' => 0,
            'mobile' => 0
        ];
        foreach ($devicesData->data as $data) {
            $devices[$data->dimensions[0]->id] = $data->metrics[0];
        }

        return round((($devices['tablet'] + $devices['mobile']) / $devicesData->totals[0]) * 100, 2);
    }


}
