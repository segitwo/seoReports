<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OAuth extends Controller
{
    function makeSERankingToken(){

        $user = Auth::user();
        $params = array(
            'method' => 'login',
            /*'data' => json_encode([
                'login' => 'info@st-lt.ru',
                'pass' => '0119d24ae438239af0146511bb8e31e9'
            ])*/
            'data' => json_encode([
                'login' => $user->profile->se_login,
                'pass' => md5($user->profile->se_password)
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

    function makeYMCode(){
        $user = Auth::user();
        $params = array(
            'response_type' => 'code',
            'client_id' => env('YM_CLIENT_ID'),
            'device_id' => md5($user->email),
            'device_name' => $user->email
        );

        return Redirect::to('https://oauth.yandex.ru/authorize?' . http_build_query($params));
    }

    //Токен для яндекс метрики
    function makeYMToken(Request $request){
        $user = Auth::user();

        if($request->has('error')){
            return \Redirect::route('projects.index')->with('message', $request->get('error_description'));
        }
        $params = array(
            'grant_type' => 'authorization_code',
            'code' => $request->get('code'),
            'client_id' => env('YM_CLIENT_ID'),
            'client_secret' => env('YM_CLIENT_PASS'),
            'device_id' => md5($user->email),
            'device_name' => $user->email
        );
        $result = file_get_contents('https://oauth.yandex.ru/token', false, stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($params)
            )
        )));
        $result = json_decode($result, 1);

        $user->oAuthToken->ym_token = $result['access_token'];
        $user->oAuthToken->save();
        return \Redirect::route('projects.index')->with('message', 'Аккаунт добавлен');
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
