<?php

namespace App\Stats;

class SERanking {

    public static function getData($params){
        $params['token'] = '612ea230b747859754f193d879e18e9e';
        if(isset($params['data'])){
            $params['data'] = json_encode(
                $params['data']
            );
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://online.seranking.com/structure/clientapi/v2.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec ($ch);
        curl_close ($ch);

        return json_decode($result);
    }

}