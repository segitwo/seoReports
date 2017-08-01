<?php

namespace App\Stats;

class YMarket {

    public static function getRegion($id){

        $metrika_url = 'https://api.partner.market.yandex.ru/v2/regions/' . $id . '.json?&oauth_token=AQAAAAAFr1TtAARymCze9uTmjUsnnM0xsY1SqsE&oauth_client_id=031d6540482b4b4f803654a2101f4c35';
        $metrika = (new self)->curlData($metrika_url);

        return json_decode($metrika);

        //https://api.partner.market.yandex.ru/v2/regions/213.xml?oauth_token=AQAAAAAFr1TtAARymCze9uTmjUsnnM0xsY1SqsE&oauth_client_id=031d6540482b4b4f803654a2101f4c35
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