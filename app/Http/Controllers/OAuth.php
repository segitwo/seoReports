<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OAuth extends Controller
{
    function mekeSERankingToken(Request $request){
        $params = array(
            'method' => 'login',
            'data' => json_encode([
                'login' => 'info@st-lt.ru',
                'pass' => '0119d24ae438239af0146511bb8e31e9'
            ])
        );

        $result = file_get_contents('http://online.seranking.com/structure/clientapi/v2.php', false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));

        return $result;

    }

    //Токен для яндекс метрики
    function makeToken(Request $request){

        $params = array(
            'grant_type' => 'authorization_code',
            'code' => $request->get('code'),
            'client_id' => '9dae6191db9c402eb2407bc7ab2be6c7',
            'client_secret' => '2d69a61cbfa545f5a31095d5cd960ea9',
            'device_id' => 'a16f71b8-6c92-11e7-907b-a6006ad3dba1',
            'device_name' => 'ONReports'
        );
        $result = file_get_contents('https://oauth.yandex.ru/token', false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));

        return $result;
    }

    function makePartnerToken(Request $request){

        $params = array(
            'grant_type' => 'authorization_code',
            'code' => $request->get('code'),
            'client_id' => '031d6540482b4b4f803654a2101f4c35',
            'client_secret' => 'bfa931dfc61f4c5e99a091ee3aeb1478',
            'device_id' => 'a16f71b8-6c92-11e7-907b-a6006ad3dba1',
            'device_name' => 'ONReports'
        );
        $result = file_get_contents('https://oauth.yandex.ru/token', false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));

        return $result;
    }
    //https://oauth.yandex.ru/authorize?response_type=code&client_id=031d6540482b4b4f803654a2101f4c35&device_id=a16f71b8-6c92-11e7-907b-a6006ad3dba1&device_name=ONReports&login_hint=mousemarika
}
