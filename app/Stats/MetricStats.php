<?php


namespace App\Stats;

use Carbon\Carbon;

class MetricStats {

    private $siteKey = "";
    private $allpKey = "";
    private $montharr = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

    private $today;
    private $prevDay;
    private $days;

    public function __construct($siteKey, $allpKey = "", Carbon $today) {
        $this->siteKey = $siteKey;
        $this->allpKey = $allpKey;

        $this->today = $today;
        $this->prevDay = clone $today;
        $this->prevDay->modify('-1 month');

        $this->days = [$this->prevDay->format('Y-m-d'), $today->format('Y-m-d')];
    }

    public function getTotalVisitsData() {

        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'traffic',
            'days' => $this->days,
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

    //Посещаемость из поисковых систем
    public function getSEData() {

        //$metrika_url = "https://api-metrika.yandex.ru/stat/v1/data?id=" . $this->siteKey . "&sort=ym:s:date&pretty=1&date1=" . $prevDay->format('Y-m-d') . "&date2=" . $this->today->format('Y-m-d') . "&limit=10000&oauth_token=AQAAAAAFr1TtAARuu-zYOVdFGkTggA-LSDRV_8g&preset=search_engines&group=month&dimensions=ym:s:lastSearchEngineRoot,ym:s:lastSearchEngine,ym:s:lastSearchPhrase,ym:s:date&filters=ym:s:date!=null";


        $prevDay = clone $this->today;
        $prevDay->modify('-3 month');

        $days = [$prevDay->format('Y-m-d'), $this->today->format('Y-m-d')];

        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'search_engines',
            'days' => $days,
            'sort' => 'ym:s:date',
            'dimensions' => 'ym:s:lastSearchEngineRoot,ym:s:lastSearchEngine,ym:s:lastSearchPhrase,ym:s:date',
            'filters' => 'ym:s:date!=null'
        ]);

        $preResultData = [];

        foreach($metricData->data as $idx => $data) {
            $dateArray = (array)$data->dimensions[3];

            $SEData = (array)$data->dimensions[0];
            $metricVals = $data->metrics;

            if(!array_key_exists($dateArray['name'], $preResultData)){
                $preResultData[$dateArray['name']] = [
                    'yandex' => 0,
                    'google' => 0,
                    'other' => 0
                ];
            }



            if($SEData["id"] == "yandex"){
                if(array_key_exists("yandex", $preResultData[$dateArray['name']])){
                    $preResultData[$dateArray['name']]["yandex"] += $metricVals["0"];
                } else {
                    $preResultData[$dateArray['name']]["yandex"] = $metricVals["0"];
                }
            }

            if($SEData["id"] == "google"){
                if(array_key_exists("google", $preResultData[$dateArray['name']])){
                    $preResultData[$dateArray['name']]["google"] += $metricVals["0"];
                } else {
                    $preResultData[$dateArray['name']]["google"] = $metricVals["0"];
                }
            }

            if($SEData["id"] != "yandex" && $SEData["id"] != "google"){
                if(array_key_exists("other", $preResultData[$dateArray['name']])){
                    $preResultData[$dateArray['name']]["other"] += $metricVals["0"];
                } else {
                    $preResultData[$dateArray['name']]["other"] = $metricVals["0"];
                }
            }

        }

        $monthOffset = 0;
        $notFirst = 0;
        $firstDayInMonth = new \DateTime();
        $resultData = [];
        $totalYandex = 0;
        $totalGoogle = 0;
        $totalOther = 0;
        $totalTotal = 0;
        $idx = 0;
        foreach($preResultData as $date => $data) {

            $date = new \DateTime($date);

            if($notFirst == 0){
                $notFirst = 1;
                $firstDayInMonth = $date;
            }

            if($firstDayInMonth->format('d') == $date->format('d') && $idx != 0){
                $monthOffset = 1;
                if(intval($firstDayInMonth->format('d')) > 15){
                    $monthOffset = 0;
                }
                $resultData["periods"][] = $this->montharr[$firstDayInMonth->format("m") - $monthOffset] . " (" . $firstDayInMonth->format('d.m') . " – " . $date->format('d.m') . ")";
                $resultData["yandex"][] = $totalYandex;
                $resultData["google"][] = $totalGoogle;
                $resultData["other"][] = $totalOther;
                $resultData["total"][] = $totalTotal;
                $firstDayInMonth = $date;
                $totalYandex = 0;
                $totalGoogle = 0;
                $totalOther = 0;
                $totalTotal = 0;

            }

            if(array_key_exists("yandex", $data)){
                $totalYandex += $data["yandex"];
                $totalTotal += $data["yandex"];
            }

            if(array_key_exists("google", $data)){
                $totalGoogle += $data["google"];
                $totalTotal += $data["google"];
            }

            if(array_key_exists("other", $data)){
                $totalOther += $data["other"];
                $totalTotal += $data["other"];
            }
            $idx ++;
        }

        if($totalYandex || $totalGoogle || $totalOther || $totalTotal){
            $resultData["periods"][] = $this->montharr[$firstDayInMonth->format("m") - $monthOffset] . " (" . $firstDayInMonth->format('d.m') . " – " . $date->format('d.m') . ")";
            $resultData["yandex"][] = $totalYandex;
            $resultData["google"][] = $totalGoogle;
            $resultData["other"][] = $totalOther;
            $resultData["total"][] = $totalTotal;
        }

        return $resultData;

    }

    public function getDepthData() {

        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'deepness_depth',
            'dimensions' => 'ym:s:pageViewsInterval',
            'metric' => 'ym:s:visits,ym:s:pageviews,ym:s:bounceRate,ym:s:pageDepth,ym:s:avgVisitDurationSeconds',
            'days' => $this->days
        ]);

        if(!isset($metricData->totals)) {
            return '';
        }

        $totals = $metricData->totals;
        $totals[2] = round($totals[2], 2);
        $totals[3] = round($totals[3], 2);
        $totals[4] = gmdate("H:i:s", ceil($totals[4]) % 86400);

        return $totals;

    }

    public function sourcesSummary(){

        $metricData = YMetric::getData($this->siteKey, [
            'dimensions' => 'ym:s:<attribution>TrafficSource',
            'days' => $this->days,
            'metric' => 'ym:s:visits',
            'link' => '/bytime',
            'group' => 'day',
            'attribution' => 'last'
        ]);

        return $this->processDateData($metricData);
    }

    private function processDateData($data){
        $output = [];
        foreach ($data->data as $row) {
            $key = $row->dimensions['0']->name;

            foreach($row->metrics['0'] as $val){
                $output[$key][] = $val;
            }
        }

        return $output;
    }

    public function getMaxBouncePages(){

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
                $outputRow['5'] = str_pad(floor(($outputRow['5']) / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad((ceil($outputRow['5']) % 60), 2, '0', STR_PAD_LEFT);
                $output[] = $outputRow;
                $idx ++;
            }

        }

        return $output;
    }

    public function getMostPopularPages(){

        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'content_entrance',
            'dimensions' => 'ym:s:startURLHash',
            'days' => $this->days,
            'metric' => 'ym:s:visits',
            'sort' => 'ym:s:visits'
        ]);

        $output = [];
        $idx = 1;
        foreach(array_reverse($metricData->data) as $data){
            if($data->metrics['0'] >= 10){
                $outputRow = array_prepend(array_prepend($data->metrics, $data->dimensions['0']->name), $idx);
                $output[] = $outputRow;
                $idx ++;
            }
        }

        return $output;
    }

    public function getTimeData() {

        $metricData = YMetric::getData($this->siteKey, [
            'preset' => 'deepness_time',
            'dimensions' => 'ym:s:visitDurationInterval',
            'days' => $this->days,
            'metric' => 'ym:s:visits,ym:s:pageviews,ym:s:bounceRate,ym:s:avgVisitDurationSeconds',
        ]);

        $resultData = [];

        foreach($metricData->data as $data) {

            if(isset($resultData['data']) && array_key_exists($data->dimensions[0]->name, $resultData['data'])) {
                $resultData['data'][$data->dimensions[0]->name] = $this->array_custom_merge($resultData['data'][$data->dimensions[1]->name], $data->metrics);
            } else {
                $resultData['data'][$data->dimensions[0]->name] = $data->metrics;
                $resultData['data'][$data->dimensions[0]->name]["count"] = 1;
            }
        }

        $totals = $metricData->totals;
        $totals[2] = round($totals[2], 2);
        $totals[3] = gmdate("H:i:s", ceil($totals[3]) % 86400);

        $resultData["totals"] = $totals;

        $resultData["data"] = $this->sortData($resultData["data"], 2, SORT_DESC, SORT_NUMERIC);

        return $resultData;
    }

    public function getAveragePositions(){

        //Статистика по запросам
        $keyWordsStat = SERanking::getData($params = [
            'method' => 'stat',
            'data' => [
                'siteid' => '222246',
                'dateStart' => $this->prevDay->format('Y-m-d'),
                'dateEnd' => $this->today->format('Y-m-d'),
            ]
        ]);

        $output = [];
        foreach($keyWordsStat as $key => $stat){

            if($stat->seID == 411){
                $key = 'Яндекс';
            } elseif(in_array($stat->seID, [474, 339])) {
                $key = 'Google';
            }

            if(isset($key)){
                $region = $stat->region_name;

                //название региона яндекса SE Ranking не передает, но передает id региона, поэтому тянем так.
                if($key == 'Яндекс'){
                    //echo print_r(YMarket::getRegion($stat->regionID), 1);
                    //$mRegion = YMarket::getRegion($stat->regionID);
                    //$region = $mRegion->regions['0']->name;

                    $regions = [
                        '2' => 'Санкт-Петербург',
                        '213' => 'Москва',
                    ];
                    if(isset($regions[$stat->regionID])){
                        $region = $regions[$stat->regionID];
                    }

                }

                $positionsRow = [];
                foreach ($stat->keywords as $keyword) {
                    foreach ($keyword->positions as $k => $position) {
                        if(isset($positionsRow[$k])){
                            $positionsRow[$k] += intval($position->pos);
                        } else {
                            $positionsRow[$k] = intval($position->pos);
                        }

                    }
                }
                //$output[$key . ' (' . $region . ')'][] = $positionsRow;
                foreach ($positionsRow as $val) {
                    $output[$key . ' (' . $region . ')']['charts'][] = round($val / count($stat->keywords), 2);
                    $output[$key . ' (' . $region . ')']['se'] = $key;
                }
            }

        }

        return $output;
    }

    public function getPositions(){

        //Так как статистика по ключевым словам не содержит поисковой запрос, а только его id, то тащим еще и список запросов.
        $keyWordsData = SERanking::getData($params = [
            'method' => 'siteKeywords',
            'data' => [
                'siteid' => '222246'
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
                'siteid' => '222246',
                'dateStart' => '2017-07-10',
                'dateEnd' => '2017-07-17'
            ]
        ]);

        $output = [];
        foreach($keyWordsStat as $stat){
            if($stat->seID == 411){
                $key = 'Яндекс';
            } elseif(in_array($stat->seID, [474, 339])) {
                $key = 'Google';
            }

            if(isset($key)){
                foreach ($stat->keywords as $keyword) {
                    if(isset($keyWords[$keyword->id])){
                        foreach ($keyword->positions as $position) {
                            //Привеодим к виду ['Поисковая система']['Поисковый запрос']['change', 'pos']
                            $output[$key][$keyWords[$keyword->id]][] = ['change' => $position->change, 'pos' => $position->pos];
                        }
                    }

                }
            }

        }

        return $output;
    }

    public function sortData($array, $keyVal, $sortdir = SORT_DESC, $flag = SORT_REGULAR) {
        $sortArray = [];
        foreach ($array as $key => $row) {
            $sortArray[$key]  = $row[$keyVal];
        }
        array_multisort($sortArray, $sortdir, $flag, $array);

        return $array;
    }

    public function array_custom_merge($arr1, $arr2) {
        $newArr = $arr1;

        foreach($arr2 as $k => $v) {
            if(isset($newArr[$k])) {
                if(is_numeric($v)){
                    $newArr[$k] += $v;
                }
            } else {
                $newArr[$k] = $v;
            }
        }

        if(isset($newArr["count"])) {
            $newArr["count"] ++;
        }

        return $newArr;
    }

}

