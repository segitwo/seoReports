<?php

namespace App\Template;

use App\Report\Chart;
use App\Report\XMLBuilder;
use App\Stats\YGoals;
use App\Stats\YMetric;
use Carbon\Carbon;

class ConversionsBlock extends TemplateBlockExtension
{
    public function listProperties()
    {
        return [];
    }

    public function getData($requestData, $reportId)
    {
        parent::getData($requestData, $reportId);

        $relsPath = app_path('Stats/' . $reportId . '/word/_rels/document.xml.rels');

        $conversionsCharts = [];

        if (file_exists($relsPath)) {

            $rels = simplexml_load_file($relsPath);

            $golas = YGoals::getList($this->siteKey);

            $lines = [];
            foreach ($golas->goals as $goal) {
                $conversion = YMetric::getData($this->siteKey, [
                    'days' => $this->days,
                    'metric' => 'ym:s:goal' . $goal->id . 'conversionRate',
                ]);

                $goalReaches = YMetric::getData($this->siteKey, [
                    'days' => $this->days,
                    'metric' => 'ym:s:goal' . $goal->id . 'reaches',
                ]);

                if ($conversion->totals['0'] == 0 && $goalReaches->totals['0'] == 0) {
                    continue;
                }

                usort($conversion->data, function ($a, $b) {
                    $ad = new \DateTime($a->dimensions['0']->name);
                    $bd = new \DateTime($b->dimensions['0']->name);

                    if ($ad == $bd) {
                        return 0;
                    }

                    return $ad < $bd ? -1 : 1;
                });

                foreach ($conversion->data as $data) {
                    $lines[$goal->name]['charts'][] = $data->metrics['0'];
                }

                $lines[$goal->name]['totals']['conversions'] = round($conversion->totals['0'], 2) . '%';
                $lines[$goal->name]['totals']['goals'] = round($goalReaches->totals['0'], 2);

            }

            foreach ($lines as $key => $line) {

                //id для названия картинки
                $imageId = random_int(1000, 9999);

                //создаем график с именем $imageId . '.png'
                Chart::makeLineChart([$key => $line['charts']], $imageId . '.png', $reportId, $this->today);

                //Создаем в document.xml.rels отношение где привязываем изображение с графиком идентийкатору.
                $xmlId = 'rId' . $imageId;
                $relationshipXML = new \SimpleXMLElement('<Relationship/>');
                $relationshipXML->addAttribute('Type', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/image');
                $relationshipXML->addAttribute('Id', $xmlId);
                $relationshipXML->addAttribute('Target', 'media/' . $imageId . '.png');

                XMLBuilder::sxml_append($rels, $relationshipXML);
                $rels->asXml($relsPath);

                $conversionsCharts[] = ['conversion' => $key, 'chartId' => $xmlId, 'total' => $line['totals']];
            }

            return view('reports.xml.chart.conversionsChart', ['conversionsCharts' => $conversionsCharts]);
        } else {
            exit('Не удалось открыть файл ' . $relsPath);
        }
    }


}
