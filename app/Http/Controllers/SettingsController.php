<?php

namespace App\Http\Controllers;

use App\Stats\SERanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('settings')->with('user', $user);
    }

    public function update(Request $request){

        $user = Auth::user();

        $user->profile->se_login = $request->get('se_login');
        $user->profile->se_password = $request->get('se_password');
        $user->profile->save();

        $response = SERanking::makeToken();
        return \Redirect::route('settings')->with($response);

    }
}
