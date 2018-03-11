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
        } else {
            $default['date1'] = date ('Ymd', time() - 86400 * ($params['days'] - 1));
            $default['date2'] = date("Ymd");
        }
        $metrika_url = 'https://api-metrika.yandex.ru/stat/v1/data' . $link . '?' . http_build_query(array_merge($default, $params));
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
        $metrika = curl_exec($ch);
        curl_close($ch);

        return $metrika;
    }
}