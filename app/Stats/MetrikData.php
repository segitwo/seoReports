<?php

/*include ($_SERVER['DOCUMENT_ROOT'] . "/allp/xmlrpc.inc");
require_once $_SERVER['DOCUMENT_ROOT'] . '/allp/AllPositions.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/allp/Word.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/allp/xcopy.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/allp/YMetrika.php';*/

namespace App\Stats;

class MetrikData {
    
    private $siteKey = "";
    private $allpKey = "";
    private $montharr = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
    
    public function __construct($siteKey, $allpKey = "") {
        $this->siteKey = $siteKey;
        $this->allpKey = $allpKey;
    }
    
    public function getTotalVisitsData(\DateTime $today) {
        
        $prevDay = clone $today;
        $prevDay->modify('-3 month');
        
        $days = [$prevDay->format('Ymd'), $today->format('Ymd')];
        
        $m = new YMetric("traffic", "", $metric = "", $days, $this->siteKey);
        $mData = $m->getData();
        dd($mData);
        if(!isset($mData->data)) {
            return '';
        }

        $isFirst = true;
        $firstDayInMonth = new \DateTime();
        $resultData = [];
        $totalGuests = 0;
        $totalVews = 0;
        $totalVisits = 0;

        foreach($mData->data as $idx => $data) {

            //date
            $dateArray = (array)$data->dimensions[0];

            $date = new \DateTime($dateArray['name']);

            if($isFirst){
                $isFirst = false;
                $firstDayInMonth = $date;
            }

            if($firstDayInMonth->format('d') == $date->format('d') && $idx != 0){

                //$resultData["periods"]["'" . $montharr[$firstDayInMonth->format("m") - 1] . " (" . $firstDayInMonth->format('d.m') . " – " . $date->format('d.m') . ")'"] = [$totalGuests , $totalVews, $totalVisits];
                $monthOffset = 1;
                if(intval($firstDayInMonth->format('d')) > 15){
                    $monthOffset = 0;
                }
                $resultData["periods"][] =
                    $this->montharr[$firstDayInMonth->format("m") - $monthOffset] . " (" . $firstDayInMonth->format('d.m') . " – " . $date->format('d.m') . ")";

                $resultData["guests"][] = $totalGuests;
                $resultData["vews"][] = $totalVews;
                $resultData["visits"][] = $totalVisits;
                $firstDayInMonth = $date;
                $totalGuests = 0;
                $totalVews = 0;
                $totalVisits = 0;
            }



            $metricVals = $data->metrics;

            $totalGuests += $metricVals["1"];
            $totalVews += $metricVals["2"];
            $totalVisits += $metricVals["0"];


        }

        if($totalGuests || $totalVews || $totalVisits){
            $resultData["periods"][] = $this->montharr[$firstDayInMonth->format("m") - $monthOffset] . " (" . $firstDayInMonth->format('d.m') . " – " . $date->format('d.m') . ")";
            $resultData["guests"][] = $totalGuests;
            $resultData["vews"][] = $totalVews;
            $resultData["visits"][] = $totalVisits;
        }

        $resultData["periods"] = array_merge($resultData["periods"], array_fill(count($resultData["periods"]), 3 - count($resultData["periods"]), ' '));
        $resultData["guests"] = array_merge($resultData["guests"], array_fill(count($resultData["guests"]), 3 - count($resultData["guests"]), '0'));
        $resultData["vews"] = array_merge($resultData["vews"], array_fill(count($resultData["vews"]), 3 - count($resultData["vews"]), '0'));
        $resultData["visits"] = array_merge($resultData["visits"], array_fill(count($resultData["visits"]), 3 - count($resultData["visits"]), '0'));

        return $resultData;
    }
    
    //Посещаемость из поисковых систем
    public function getSEData($today) {
        
        $prevDay = clone $today;
        $prevDay->modify('-3 month');
        
        $days = [$prevDay->format('Ymd'), $today->format('Ymd')];
        
        //$m = new YMetric("search_engines", "<attribution>SearchEngineRoot", "ym:s:pageviews", $days, $this->siteKey);
        //$mData = $m->getData();
        //die(print_r($mData, 1));
        
        $metrika_url = "https://api-metrika.yandex.ru/stat/v1/data?id=" . $this->siteKey . "&sort=ym:s:date&pretty=1&date1=" . $prevDay->format('Ymd') . "&date2=" . $today->format('Ymd') . "&limit=10000&oauth_token=AQAAAAAFr1TtAARuu-zYOVdFGkTggA-LSDRV_8g&preset=search_engines&group=month&dimensions=ym:s:lastSearchEngineRoot,ym:s:lastSearchEngine,ym:s:lastSearchPhrase,ym:s:date&filters=ym:s:date!=null";
        
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $metrika_url);
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
        curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
        //curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $metrika = curl_exec ($ch);
        curl_close($ch);
        
        
        $mData  = json_decode($metrika);
        if(!isset($mData->data)) {
            return '';
        }
        
        $preResultData = [];

        foreach($mData->data as $idx => $data) {
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
    
    //Поисковые запросы
    public function getSearhPhrases($today) {
        
        $prevDay = clone $today;
        $prevDay->modify('-1 month');
        
        $days = [$prevDay->format('Ymd'), $today->format('Ymd')];
        
        $m = new YMetric("sources_search_phrases", "lastSearchPhrase", $metric = "", $days, $this->siteKey);
        $mData = $m->getData();
        
        
        $searchPhrasesTable = [];
        
        foreach($mData->data as $data) {
            $phrasesRow = [];
            $phrasesRow["name"] = $data->dimensions[1]->name;
            $phrasesRow["visits"] = $data->metrics[0];
            $phrasesRow["users"] = $data->metrics[1];
            $phrasesRow["bounceRate"] = round($data->metrics[2], 2);
            $phrasesRow["pageDepth"] = round($data->metrics[3], 3);
            $phrasesRow["seconds"] = gmdate("H:i:s", ceil($data->metrics[4]) % 86400);
            
            $searchPhrasesTable["phrasesRows"][] = $phrasesRow;
        }
        
        
        $date = new \DateTime($mData->query->date1);
        $searchPhrasesTable["month"] = $this->montharr[$date->format("m") - 1];
        $searchPhrasesTable["total_rows"] = $mData->total_rows;
        $searchPhrasesTable["totals"] = $mData->totals;
        $searchPhrasesTable["totals"][2] = round($mData->totals[2], 2);
        $searchPhrasesTable["totals"][3] = round($mData->totals[3], 3);
        $searchPhrasesTable["totals"][4] = gmdate("H:i:s", ceil($searchPhrasesTable["totals"][4])%86400);
        
        $searchPhrasesTable["phrasesRows"] = $this->sortData($searchPhrasesTable["phrasesRows"], "visits");
        array_splice($searchPhrasesTable["phrasesRows"], 100);
        return $searchPhrasesTable;
    }
    
    //allp
    /*public function getAllPositionsTop10($today = null, $prevDay = null){
        $api = new xf3\AllPositions('de64f44f92a4053e6fff3fcc366987c4');
        
        $today = $today->format('Y-m-d');
        $prevDay = $prevDay->format('Y-m-d');
        
        $report = $api->get_report($this->allpKey, $today, $prevDay, null, null);
        
        return $report["top10"] / $report["count"];
        
        /*return [
            "all" => $report["top10"] / $report["count"],
            "google" => $report["tops10"]["1055529"] / $report["count"],
            "yandex" => $report["tops10"]["1055528"] / $report["count"]
        ];*/
    /*}

    public function getAllPositionsData($today = null, $prevDay = null){
        $api = new AllPositions('de64f44f92a4053e6fff3fcc366987c4');
        
        $today = $today->format('Y-m-d');
        if(!$prevDay){
           die("Не указан предыдущая дата");
        }
        $prevDay = $prevDay->format('Y-m-d');
        
        $report = $api->get_report($this->allpKey, $today, $prevDay, null, null);

        $idSe1 = $report["sengines"][0]["id_se"];
        $nameSe1 = $report["sengines"][0]["name_se"];
        
        $idSe2 = $report["sengines"][1]["id_se"];
        $nameSe2 = $report["sengines"][1]["name_se"];
        
        
        $finalArray = [];
        foreach($report["queries"] as $query) {
            
            $position1 = $report["positions"][$idSe1 . "_" . $query["id_query"]];
            $position2 = $report["positions"][$idSe2 . "_" . $query["id_query"]];
            
            $finalArray[] = array(
                "query" => $query["query"],
                "y" => array(
                    "position" => $position1["position"],
                    "change_position" => $position1["change_position"]
                ),
                "g" => array(
                    "position" => $position2["position"],
                    "change_position" => $position2["change_position"]
                )
            );
        }
        
        return $finalArray;
    }
    
    public function getAllPositionsData10($today = null, $prevDay = null){
        $api = new xf3\AllPositions('de64f44f92a4053e6fff3fcc366987c4');
        
        $today = $today->format('Y-m-d');
        $prevDay = $prevDay->format('Y-m-d');
        
        $report = $api->get_report($this->allpKey, $today, $prevDay, null, null);

        $idSe1 = $report["sengines"][0]["id_se"];
        $nameSe1 = $report["sengines"][0]["name_se"];
        
        $idSe2 = $report["sengines"][1]["id_se"];
        $nameSe2 = $report["sengines"][1]["name_se"];
        
        
        $finalArray = [];
        foreach($report["queries"] as $query) {
            
            $position1 = $report["positions"][$idSe1 . "_" . $query["id_query"]];
            $position2 = $report["positions"][$idSe2 . "_" . $query["id_query"]];
            
            if((intval($position1["position"]) <= 10 && intval($position1["position"]) != 0) || (intval($position2["position"]) <= 10 && intval($position2["position"]) != 0)){
                $finalArray[] = $query["query"];
            }
            
        }
        
        return $finalArray;
    }
    
    public function getAllPositionLastUpdate(){
        $api = new xf3\AllPositions('de64f44f92a4053e6fff3fcc366987c4');

        return end($api->get_report_dates($this->allpKey));
    }
    */
    public function getRegionsData($today) {
        $prevDay = clone $today;
        $prevDay->modify('-1 month');
        
        $days = [$prevDay->format('Ymd'), $today->format('Ymd')];
        
        $m = new YMetric("geo_country", "regionAreaName", "ym:s:visits,ym:s:pageviews", $days, $this->siteKey);
        $mData = $m->getData();
        $resultData = [];
        
        foreach($mData->data as $data) {
            if(array_key_exists($data->dimensions[1]->name, $resultData)) {
                $resultData[$data->dimensions[1]->name] = $this->array_custom_merge($resultData[$data->dimensions[1]->name], $data->metrics);
            } else {
                $resultData[$data->dimensions[1]->name] = $data->metrics;
                $resultData[$data->dimensions[1]->name]["name"] = $data->dimensions[1]->name;
            }
        }
        
        $totals = $mData->totals;
        
        foreach($resultData as $key => $data) {
            $resultData[$key]["percent"] = round(($data[0] / $totals[0]) * 100, 2);
        }
        
        return $resultData;
    }
    
    public function getDepthData($today) {
        $prevDay = clone $today;
        $prevDay->modify('-1 month');
        
        $days = [$prevDay->format('Ymd'), $today->format('Ymd')];
        
        $m = new YMetric("deepness_depth", "pageViewsInterval", "ym:s:visits,ym:s:pageviews,ym:s:bounceRate,ym:s:avgVisitDurationSeconds", $days, $this->siteKey);
        $mData = $m->getData();
        if(!isset($mData->data)) {
            return '';
        }
        $resultData = [];
        
        foreach($mData->data as $data) {
            
            if(isset($resultData['data']) && array_key_exists($data->dimensions[1]->name, $resultData['data'])) {
                $resultData['data'][$data->dimensions[1]->name] = $this->array_custom_merge($resultData['data'][$data->dimensions[1]->name], $data->metrics);
            } else {
                $resultData['data'][$data->dimensions[1]->name] = $data->metrics;
                $resultData['data'][$data->dimensions[1]->name]["count"] = 1;
            }
        }
        
        $totals = $mData->totals;
        $totals[2] = round($totals[2], 2);
        $totals[3] = gmdate("H:i:s", ceil($totals[3]) % 86400);
        
        $resultData["totals"] = $totals;
        
        $resultData["data"] = $this->sortData($resultData["data"], 0, SORT_DESC, SORT_NUMERIC);
        
        return $resultData;
        
    }
    
    public function getTimeData($today) {
        //Время на сайте

        $prevDay = clone $today;
        $prevDay->modify('-1 month');
        
        $days = [$prevDay->format('Ymd'), $today->format('Ymd')];

        $m = new YMetric("deepness_time", "visitDurationInterval", "ym:s:visits,ym:s:pageviews,ym:s:bounceRate,ym:s:avgVisitDurationSeconds", $days, $this->siteKey);
        $mData = $m->getData();
        
        $resultData = [];
        
        foreach($mData->data as $data) {
            
            if(isset($resultData['data']) && array_key_exists($data->dimensions[1]->name, $resultData['data'])) {
                $resultData['data'][$data->dimensions[1]->name] = $this->array_custom_merge($resultData['data'][$data->dimensions[1]->name], $data->metrics);
            } else {
                $resultData['data'][$data->dimensions[1]->name] = $data->metrics;
                $resultData['data'][$data->dimensions[1]->name]["count"] = 1;
            }
        }
        
        $totals = $mData->totals;
        $totals[2] = round($totals[2], 2);
        $totals[3] = gmdate("H:i:s", ceil($totals[3]) % 86400);
        
        $resultData["totals"] = $totals;
        
        $resultData["data"] = $this->sortData($resultData["data"], 2, SORT_DESC, SORT_NUMERIC);
        
        return $resultData;
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

