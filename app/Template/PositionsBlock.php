<?php

namespace App\Template;

use App\Stats\SERanking;
use Carbon\Carbon;

class PositionsBlock extends TemplateBlockExtension
{
    public function listProperties()
    {
        return [];
    }

    public function getData($requestData, $reportId)
    {
        parent::getData($requestData, $reportId);

        //Так как статистика по ключевым словам не содержит поисковой запрос, а только его id, то тащим еще и список запросов.
        $keyWordsData = SERanking::getData($params = [
            'method' => 'siteKeywords',
            'data' => [
                'siteid' => $this->rankingKey,
            ]
        ]);

        //Приводим к виду ['id запроса' => 'Имя запроса']
        $keyWords = array_column($keyWordsData, 'name', 'id');

        //Статистика по запросам
        $keyWordsStat = SERanking::getData($params = [
            'method' => 'stat',
            'data' => [
                'siteid' => $this->rankingKey,
                'dateStart' => $this->prevDay->format('Y-m-d'),
                'dateEnd' => $this->today->format('Y-m-d'),
            ]
        ]);

        $output = [];

        foreach(array_slice($keyWordsStat, 0, 2) as $stat){

            $key = ($stat->seID == 411) ? 'Яндекс' : 'Google';

            foreach ($stat->keywords as $keyword) {
                if(isset($keyWords[$keyword->id])){

                    //По дефолту
                    if(!isset($output[$keyWords[$keyword->id]]['Яндекс'])){
                        $output[$keyWords[$keyword->id]]['Яндекс'] = ['change' => 0, 'pos' => 0, 'last_position' => 0];
                    }

                    if(!isset($output[$keyWords[$keyword->id]]['Google'])){
                        $output[$keyWords[$keyword->id]]['Google'] = ['change' => 0, 'pos' => 0, 'last_position' => 0];
                    }

                    $positions = $keyword->positions;
                    if(!count($positions)){continue;}

                    $lastPosition = intval(reset($positions)->pos);
                    $currentPosition = intval(end($positions)->pos);
                    $change = $lastPosition - $currentPosition;
                    $change = ($lastPosition) ? $change : abs($change);

                    $output[$keyWords[$keyword->id]][$key] = [
                        'change' => $change,
                        'pos' => $currentPosition,
                        'last_position' => $lastPosition
                    ];
                }
            }
        }

        return view('reports.xml.table.positions', ['positionRows' => $output])->render();
    }


}
