<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 17.07.2017
 * Time: 19:55
 */

namespace App\Stats;

use Illuminate\Support\Facades\Auth;

class YMetric
{
    public static function getData($siteKey, $params = []) {
        //$preset, $dimension = '', $metric = '', $days = 14, $siteKey, $filterOperator = '!=null', $group = '', $filter = true, $link = ''

        /*$preset = isset($params['preset']) ? '&preset=' . $params['preset'] : '';
        $link = isset($params['link']) ? $params['link'] : '';
        $group = isset($params['group']) ? '&group=' . $params['group'] : '&group=month';
        $dimensions = isset($params['dimensions']) ? '&dimensions=' . $params['dimensions'] : '&dimensions=ym:s:date';
        $filters = isset($params['filter']) ? '&filters=' . $params['filters'] : '';
        $metric = isset($params['metric']) ? "&metrics=" . $params['metric'] : '';
        $days = isset($params['days']) ? $params['days'] : '';
        $attribution = isset($params['attribution']) ? '&attribution=' . $params['attribution'] : '';
        $sort = isset($params['sort']) ? '&sort=' . $params['sort'] : '';
        $percentage = isset($params['percentage']) ? '&percentage=' . $params['percentage'] : '';*/

        $user = Auth::user();

        $link = isset($params['link']) ? $params['link'] : '';

        $default = [
            'id' => $siteKey,
            'group' => 'month',
            'dimensions' => 'ym:s:date',
            'limit' => 1000,
            'oauth_token' => $user->oAuthToken->ym_token
        ];

        if(isset($params['days']) && is_array($params['days'])){
            $default['date1'] = $params['days'][0];
            $default['date2'] = $params['days'][1];
            /*$today = $days[1];
            $daysAgo = $days[0];*/
        } else {
            $default['date1'] = date ('Ymd', time() - 86400 * ($params['days'] - 1));
            $default['date2'] = date("Ymd");
            /*$today = date("Ymd");
            $daysAgo = date ('Ymd', time() - 86400 * ($days - 1));*/
        }
        $metrika_url = 'https://api-metrika.yandex.ru/stat/v1/data' . $link . '?' . http_build_query(array_merge($default, $params));

        //$metrika_url = 'https://api-metrika.yandex.ru/stat/v1/data' . $link . '?id=' . $siteKey . $sort . $percentage . '&date1=' . $daysAgo . '&date2=' . $today . '&limit=1000' . $group . $preset . $attribution . $dimensions . $metric . $filters . '&oauth_token=AQAAAAAFr1TtAANPEBXXD8EmGk0ymRXroOa0etg';
        $metrika = (new self)->curlData($metrika_url);

        return json_decode($metrika);
    }

    public static function getMetricsList(){
        $user = Auth::user();
        $metrika_url = 'https://api-metrika.yandex.ru/management/v1/counters?oauth_token=' . $user->oAuthToken->ym_token;
        $metrika = (new self)->curlData($metrika_url);

        return json_decode($metrika);
    }

    private function curlData($metrika_url){
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $metrika_url);
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
        curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
        //curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $metrika = curl_exec ($ch);
        curl_close($ch);

        return $metrika;
    }
}