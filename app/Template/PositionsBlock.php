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
        $keyWords = [];
        foreach ($keyWordsData as $data) {
            $keyWords[$data->id] = $data->name;
        }

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

        
        foreach($keyWordsStat as $stat){
            if($stat->seID == 411) {
                $key = 'Яндекс';
                //} elseif(in_array($stat->seID, [474, 339, 454])) {
            } else {
                $key = 'Google';
            }

            if(isset($key)){
                foreach ($stat->keywords as $keyword) {
                    if(isset($keyWords[$keyword->id])){

                        $positions = $keyword->positions;

                        reset($positions);
                        $lastPosition = intval(current($positions)->pos);
                        end($positions);
                        $currentPosition = intval(current($positions)->pos);

                        $change = $lastPosition - $currentPosition;
                        if(!$lastPosition){
                            $change = abs($change);
                        }

                        $output[$keyWords[$keyword->id]][$key] = ['change' => $change, 'pos' => $currentPosition, 'last_position' => $lastPosition];

                        //По дефолту
                        if(!isset($output[$keyWords[$keyword->id]]['Яндекс'])){
                            $output[$keyWords[$keyword->id]]['Яндекс'] = ['change' => 0, 'pos' => 0, 'last_position' => 0];
                        }

                        if(!isset($output[$keyWords[$keyword->id]]['Google'])){
                            $output[$keyWords[$keyword->id]]['Google'] = ['change' => 0, 'pos' => 0, 'last_position' => 0];
                        }
                    }
                }
            }

        }

        return view('reports.xml.table.positions', ['positionRows' => $output])->render();
    }


}
