<?php

namespace App\Stats;

use Illuminate\Support\Facades\Auth;

class YGoals {

    public static function getList($id){
        $user = Auth::user();
        $metrika_url = 'https://api-metrika.yandex.ru/management/v1/counter/' . $id . '/goals?oauth_token=' . $user->oAuthToken->ym_token;
        $list = (new self)->curlData($metrika_url);

        return json_decode($list);
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