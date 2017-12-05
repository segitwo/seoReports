<?php

namespace App\Stats;

use App\User;
use Illuminate\Support\Facades\Auth;

class SERanking {

    public static function getData($params){
        $user = Auth::user();
        if(is_object($user) && $user instanceof User){
            if ($user->oAuthToken && $user->oAuthToken->se_token) {
                if(!$params['token'] = $user->oAuthToken->se_token){
                    return \Redirect::route('oauth/seranking');
                }
                //$params['token'] = '612ea230b747859754f193d879e18e9e';
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

            return ['error' => 'Нет подключения к SE Ranking'];
        }
    }

    public static function makeToken(){
        $user = Auth::user();
        $params = array(
            'method' => 'login',
            'login' => $user->profile->se_login,
            'pass' => md5($user->profile->se_password)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://online.seranking.com/structure/clientapi/v2.php?" . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        $result = json_decode($result, 1);

        if(isset($result['token'])){
            $user->oAuthToken->se_token = $result;
            $user->save();
            return [
                'message' => 'Токен обновлен',
                'status' => '1'
            ];
        } elseif (isset($result['message'])) {
            return [
                'message' => $result['message'],
                'status' => '0'
            ];
        }

    }

}