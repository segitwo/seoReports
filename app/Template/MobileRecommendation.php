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

            $bounceRate = round($bounceRateData->totals[1], 2);

            $mobilePercent = $this->getMobilePercentFromYM();

            switch ($monthsInterval - $start_month){
                case 0:
                    $text = 'В этом месяце из всех посетителей сайта, ' . $mobilePercent . '% перешли с мобильных устройств.' .
                    'На данный момент сайт не адаптирован под мобильные телефоны и планшеты — информацию приходится ' .
                    'увеличивать для прочтения, что весьма неудобно. В итоге показатель отказов (быстрых закрытий сайта) ' .
                    'мобильных пользователей составляет ' . $bounceRate . '%. Это существенный показатель, ' .
                    'учитывая, что ушедшие пользователи могли бы стать вашими клиентами.';
                    $output .= view('reports.xml.paragraph', ['val' => $text])->render();

                    $text = 'В результатах поисковой выдачи на мобильных устройствах позиции нашего проекта могут ' .
                    'занижаться поисковыми системами. Чтобы привлечь дополнительный трафик, увеличить число посетителей ' .
                    'сайта и клиентов за счет пользователей телефонов и планшетов, настоятельно рекомендуем вам адаптировать сайт под мобильные устройства.';
                    $output .= view('reports.xml.paragraph', ['val' => $text])->render();
                    break;
                case 1:
                    $text = 'В этом месяце из всех посетителей сайта, ' . $mobilePercent . '% перешли с мобильных устройств. ' .
                    'На данный момент сайт все еще не адаптирован под мобильные телефоны и планшеты — информацию ' .
                    'приходится увеличивать для прочтения. Показатель отказов (быстрых закрытий сайта) мобильных пользователей составляет ' . $bounceRate . '%.';
                    $output .= view('reports.xml.paragraph', ['val' => $text])->render();
                    break;
                default:

                    $mobilePercent_last = $this->getMobilePercentFromYM([(clone $this->prevDay)->modify('-1 month')->format('Y-m-d'), $this->prevDay->format('Y-m-d')]);

                    if($mobilePercent_last < $mobilePercent){
                        $text = 'За последние два месяца переходы с мобильных устройств увеличились с  ' . $mobilePercent_last . '% до ' . $mobilePercent . '%. ' .
                            'Это говорит о том, что доля мобильных пользователей сайта растет. На данный момент сайт все еще не ' .
                            'адаптирован под мобильные телефоны и планшеты — информацию приходится увеличивать для прочтения. ' .
                            'Показатель отказов (быстрых закрытий сайта) мобильных пользователей составляет ' . $bounceRate . '%.';
                        $output .= view('reports.xml.paragraph', ['val' => $text])->render();
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
            'metric' => 'ym:s:visits'
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
