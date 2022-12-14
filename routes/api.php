<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
function modifyBit($n, $p, $b)
{
    $mask = 1 << $p;
    return ($n & ~$mask) |
        (($b << $p) & $mask);
}
Route::post('/testing', function (Request $request) {
    $tempRequest = $request->all();
    $temp = json_decode($request->get('objectJSON'));
    $name = $request->get('deviceName');
    if(property_exists($temp, 'master')){
        if($temp->master == 144 && $name != "tri-m-59fcc45e"){
            $temp->master = 128;
            $tempRequest['objectJSON'] = json_encode($temp);
        }
        /*if($temp->master == 239 && $name == "tri-m-59fcc45e"){
            $temp->master = 255;
            $tempRequest['objectJSON'] = json_encode($temp);
        }*/
        if(($temp->master & (1 << 5)) && ($temp->master & (1 << 6)) && $name == "tri-m-59fcc45e"){
            $temp->master = modifyBit($temp->master, 4,1);
            $tempRequest['objectJSON'] = json_encode($temp);
        }else if(!($temp->master & (1 << 5)) && !($temp->master & (1 << 6)) && $name == "tri-m-59fcc45e"){
            $temp->master = modifyBit($temp->master, 4,0);
            $tempRequest['objectJSON'] = json_encode($temp);
        }
        if(($temp->master & (1 << 2)) && ($temp->master & (1 << 1)) && $name == "tri-m-59fcc45e"){
            $temp->master = modifyBit($temp->master, 3,1);
            $tempRequest['objectJSON'] = json_encode($temp);
        }else if(!($temp->master & (1 << 2)) && !($temp->master & (1 << 1)) && $name == "tri-m-59fcc45e"){
            $temp->master = modifyBit($temp->master, 3,0);
            $tempRequest['objectJSON'] = json_encode($temp);//test
        }
    }
    Http::post('https://campsabout.com/api/helium', $tempRequest);
    return 'yes';
});


