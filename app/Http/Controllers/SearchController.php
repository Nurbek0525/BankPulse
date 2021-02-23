<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Weight_of_report;
use App\Account_sheet;
use App\Access_right;
use App\Role;
use DateTime;
use DB;
use URL;
use Auth;
use Mail;
use App;
class SearchController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function search_fillials(Request $request){
        $key = $request->get('q');
        $state = $request->get('region');
        $city = $request->get('city');
        $mainbank = $request->get('mainbank');
        if(!empty($key)){
            $fillial = DB::table('banks')->
            select(
                'banks.*'
            );
            if(!empty($region_id)  && $region_id != 'all'){
                $fillial = $fillial->where('region_id', '=', $region_id);
            }
            if(!empty($mainbank_id) && $mainbank_id != 'all'){
                $fillial = $fillial->where('mainbank_id', '=', $mainbank_id);
            }
            if(!empty($city_id)  && $city_id != 'all'){
                $fillial = $fillial->where('city_id', '=', $city_id);
            }
            $fillial = $fillial->where(function($query) use($key){
                $query->where('mfo_id', '=', $key)->
                orWhere('name', 'like', '%'.$key.'%')->
                orWhere('short_name', 'like', '%'.$key.'%')->
                orWhere('stir_inn', 'like', '%'.$key.'%');
            })->get()->toArray();
            echo json_encode($fillial);
        }
    }


}
