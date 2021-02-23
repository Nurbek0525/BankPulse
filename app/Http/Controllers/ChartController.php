<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Bank;
use App\Headbank;
use App\Mainbank;
use App\Account_sheet;
use App\Cat_account_sheet;
use Illuminate\Support\Facades\Input;
use PDO;
use DateTime;
use DB;
use URL;
use Auth;
use Mail;
use App\Http\Controllers\excelImporter\SpreadsheetReader;
use App\Http\Controllers\excelImporter\Transliteration;


class ChartController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function pie_chart(Request $request){
        $title = trans('app.pie chart report');
        $user = Auth::user();
        $position = get_position($user);
        $regions = DB::table('regions');
        $cities = DB::table('cities');
        $mainbanks = DB::table('mainbanks');
        if($position != 'admin' || $position != 'country'){
            $regions = $regions->where('id', '=', $user->region_id);
            $cities = $cities->where('region_id', '=', $user->region_id);
            $banks = DB::table('banks')->
            select('mainbank_id')->where([['banks.region_work_id', '=', $user->region_id], ['banks.mainbank_id', '!=', 38]])
            ->groupBy('mainbank_id')->get()->toArray();
            $mainbanks = $mainbanks->where(function($query) use($banks){
                foreach ($banks as $bank) {
                    $query->orWhere('id', '=', $bank->mainbank_id);
                }
            });
        } 
        $regions = $regions->orderBy('name')->get()->toArray();
        $cities = $cities->orderBy('name')->get()->toArray();
        $mainbanks = $mainbanks->orderBy('name')->get()->toArray();
        $cat_accounts = DB::table('cat_account_sheets')->orderBy('account_id')->get()->toArray();
        if($request->post()){
            $bank = $request->get('fillial');
            $region = $request->get('region');
            $mainbank = $request->get('mainbank');
            $cat_account = $request->get('cataccount');
            $monthyear = $request->get('monthyear');
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $account_sheet = $request->get('accountsheet');
            $city = $request->get('city');
            if(!empty($account_sheet) && $account_sheet != 'all'){
                $account_sheet = DB::table('account_sheets')->where('id', '=', $account_sheet)->get()->first();
            }
            if(!empty($bank) && $bank != 'all'){
                $bank = DB::table('banks')->where('id', '=', $bank)->get()->first();
            }
            if(!empty($cat_account)){
                $account_sheets = DB::table('account_sheets')->where('cat_id', '=', $cat_account)->get()->toArray();
                $cat_account = DB::table('cat_account_sheets')->where('id', '=', $cat_account)->get()->first();
            }
            if(!empty($mainbank) && $mainbank != 'all'){
                $mainbank = DB::table('mainbanks')->where('id', '=', $mainbank)->get()->first();
            }
            if(!empty($mainbank) && $mainbank != 'all'){
                $fillials = DB::table('banks')->where([['mainbank_id', '=', $mainbank->id], ['region_id', '=', $region]])->get()->toArray();
            }
            $table = 'balance_'.$year;
            $data = DB::table($table)->
                select(
                    $table.'.*',
                    'banks.mfo_id',
                    'banks.name',
                    'banks.mainbank_id'
                )->
                join('banks', 'banks.id', '=', $table.'.bank_id')->
                where([[$table.'.month', '=', $month], [$table.'.year', '=', $year]]);
            if(!empty($bank)  && $bank != 'all'){
                $data = $data->where($table.'.bank_id', '=', $bank->id);
            }
            if((!empty($mainbank) && $mainbank != 'all') && (empty($bank) || $bank == 'all')){
                $data = $data->where(function($query) use($mainbank){
                    $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                });
            }
            if(!empty($region) && $region != 'all'){
                $data = $data->where('banks.region_work_id', '=', $region);
            }
            if(!empty($city) &&  $city != 'all'){
                $data = $data->where('banks.city_id', '=', $city);
            }
            $balance = $data->get()->toArray();
            if((empty($mainbank) || $mainbank == 'all') && ($city == 'all' || empty($city))){
                $mainbank_view = true;
            }elseif(!empty($city) && $city != 'all' && $mainbank == 'all'){
                $mainbank_view = false;
            }else{
                $mainbank_view = false;
            }
            if(((!empty($bank) && $bank != 'all' && empty($account_sheet)) || (!empty($mainbank) && $mainbank != 'all' && $account_sheet == 'all')) && !empty($cat_account)){
                $account_sheets_view = true;
            }else{
                $account_sheets_view = false;
            }
            if((!empty($account_sheet) && $account_sheet != 'all')){
                $account_sheet_view = true;
            }else{
                $account_sheet_view = false;
            }
            if((!empty($mainbank) && $mainbank != 'all') || (!empty($city) && $city != 'all')){
                $fillial_view = true;
            }else{
                $fillial_view = false;
            }
            $output = array();
            if(!empty($balance)){
                if($account_sheets_view){
                    foreach ($balance as $balance_data) {
                        if(!empty($output)){
                            $i = 0;
                            foreach ($output as $output_data) {
                                $sum = 0;
                                $currency = 0;
                                $balance_info = json_decode($balance_data->accounting);
                                foreach ($balance_info as $balance_sheet) {
                                    if($balance_sheet->account_sheet_id == $output_data['data_id']){
                                        $sum = $sum + intval($balance_sheet->sum);
                                        $currency = $currency + intval($balance_sheet->currency);
                                    }
                                }
                                $output[$i]['sum'] = $output[$i]['sum'] + $sum;
                                $output[$i]['currency'] = $output[$i]['currency'] + $currency;
                                $i++;
                            }
                        }else{ 
                            foreach ($account_sheets as $account_sheet){
                                $sum = 0;
                                $currency = 0;
                                $new_balance = array(
                                    'data_id' => $account_sheet->account_id,
                                    'data_name' => $account_sheet->name,
                                    'sum' => 0,
                                    'currency' => 0
                                );
                                $balance_info = json_decode($balance_data->accounting);
                                foreach ($balance_info as $balance_sheet) {
                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                        $sum = $sum + intval($balance_sheet->sum);
                                        $currency = $currency + intval($balance_sheet->currency);
                                    }
                                }
                                $new_balance['sum'] = $sum;
                                $new_balance['currency'] = $currency;
                                array_push($output, $new_balance);
                            }
                        }
                    }
                }else{
                    foreach ($balance as $balance_data) {
                        if(!empty($output)){
                            $i = 0;
                            foreach ($output as $output_data) {
                                $sum = 0;
                                $currency = 0;
                                if($output_data['data_id'] == $balance_data->mainbank_id && $mainbank_view){
                                    if($account_sheet_view){
                                            $balance_info = json_decode($balance_data->accounting);
                                            foreach ($balance_info as $balance_sheet) {
                                                if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                    $sum = $sum + intval($balance_sheet->sum);
                                                    $currency = $currency + intval($balance_sheet->currency);
                                                }
                                            }
                                        }else{
                                            foreach ($account_sheets as $account_sheet){
                                                $balance_info = json_decode($balance_data->accounting);
                                                foreach ($balance_info as $balance_sheet) {
                                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                        $sum = $sum + intval($balance_sheet->sum);
                                                        $currency = $currency + intval($balance_sheet->currency);
                                                    }
                                                }
                                            }
                                        }
                                    $output[$i]['sum'] = $output[$i]['sum'] + $sum;
                                    $output[$i]['currency'] = $output[$i]['currency'] + $currency;
                                }elseif($output_data['data_id'] == $balance_data->mfo_id){
                                    if($account_sheet_view){
                                            $balance_info = json_decode($balance_data->accounting);
                                            foreach ($balance_info as $balance_sheet) {
                                                if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                    $sum = $sum + intval($balance_sheet->sum);
                                                    $currency = $currency + intval($balance_sheet->currency);
                                                }
                                            }
                                        }else{
                                            foreach ($account_sheets as $account_sheet){
                                                $balance_info = json_decode($balance_data->accounting);
                                                foreach ($balance_info as $balance_sheet) {
                                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                        $sum = $sum + intval($balance_sheet->sum);
                                                        $currency = $currency + intval($balance_sheet->currency);
                                                    }
                                                }
                                            }
                                        }
                                    $output[$i]['sum'] = $output[$i]['sum'] + $sum;
                                    $output[$i]['currency'] = $output[$i]['currency'] + $currency;
                                }
                                $i++;
                            }
                        }else{
                            if($mainbank_view){
                                $banks = DB::table('banks')->
                                select('mainbank_id')->where('banks.mainbank_id', '!=', 38);
                                if($position == 'admin' || $position == 'country'){
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }else{
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }
                                if(!empty($city)  && $city != 'all'){
                                    $banks = $banks->where('banks.city_id', '=', $city);
                                }
                                $banks = $banks->groupBy('mainbank_id')->get()->toArray();
                                $mainbankss = DB::table('mainbanks')->where(function($query) use($banks){
                                    foreach ($banks as $bank_default) {
                                        $query->orWhere('id', '=', $bank_default->mainbank_id);
                                    }
                                })->orderBy('name')->get()->toArray();
                                foreach ($mainbankss as $mbank) {
                                    $sum = 0;
                                    $currency = 0;
                                    $new_balance = array(
                                        'data_id' => $mbank->id,
                                        'data_name' => $mbank->name,
                                        'sum' => 0,
                                        'currency' => 0
                                    );
                                    if($balance_data->mainbank_id == $mbank->id){
                                        if($account_sheet_view){
                                            $balance_info = json_decode($balance_data->accounting);
                                            foreach ($balance_info as $balance_sheet) {
                                                if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                    $sum = $sum + intval($balance_sheet->sum);
                                                    $currency = $currency + intval($balance_sheet->currency);
                                                }
                                            }
                                        }else{
                                            foreach ($account_sheets as $account_sheet){
                                                $balance_info = json_decode($balance_data->accounting);
                                                foreach ($balance_info as $balance_sheet) {
                                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                        $sum = $sum + intval($balance_sheet->sum);
                                                        $currency = $currency + intval($balance_sheet->currency);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $new_balance['sum'] = $sum;
                                    $new_balance['currency'] = $currency;
                                    array_push($output, $new_balance);
                                }
                            }else{
                                $banks = DB::table('banks');
                                if($position == 'admin' || $position == 'country'){
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }else{
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }
                                if(!empty($city)  && $city != 'all'){
                                    $banks = $banks->where('banks.city_id', '=', $city);
                                }
                                if(!empty($bank) && $bank != 'all'){
                                    $banks = $banks->where('banks.id', '=', $bank->id);
                                }
                                if(!empty($mainbank)  && $mainbank != 'all'){
                                    $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                }
                                $banks = $banks->get()->toArray();
                                foreach ($banks as $bank_default) {
                                    $sum = 0;
                                    $currency = 0;
                                    $new_balance = array(
                                        'data_id' => generateMfo($bank_default->mfo_id),
                                        'data_name' => $bank_default->name,
                                        'sum' => 0,
                                        'currency' => 0
                                    );
                                    if($balance_data->mfo_id == $bank_default->mfo_id){
                                        if($account_sheet_view){
                                            $balance_info = json_decode($balance_data->accounting);
                                            foreach ($balance_info as $balance_sheet) {
                                                if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                    $sum = $sum + intval($balance_sheet->sum);
                                                    $currency = $currency + intval($balance_sheet->currency);
                                                }
                                            }
                                        }else{
                                            foreach ($account_sheets as $account_sheet){
                                                $balance_info = json_decode($balance_data->accounting);
                                                foreach ($balance_info as $balance_sheet) {
                                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                        $sum = $sum + intval($balance_sheet->sum);
                                                        $currency = $currency + intval($balance_sheet->currency);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $new_balance['sum'] = $sum;
                                    $new_balance['currency'] = $currency;
                                    array_push($output, $new_balance);
                                }
                            }
                        }
                    }
                }
            }else{
                $output = 'empty';
            }
            if(!empty($region) && $region != 'all'){
                $region = DB::table('regions')->where('id', '=', $region)->get()->first();
                $cities = DB::table('cities')->where('region_id', '=', $region->id)->get()->toArray();
            }
            if(!empty($city) && $city != 'all'){
                $city = DB::table('cities')->where('id', '=', $city)->get()->first();
            }
            $place_title = null;
            $bank_title = null;
            $head_title = null;
            if(!empty($region) && $region != 'all' && (empty($city) || $city == 'all') ){
                $place_title = $region->name;
            }
            if(!empty($city) && $city != 'all'){
                $place_title = $city->name;
            }
            if(!empty($mainbank) && $mainbank != 'all' && (empty($bank) || $bank == 'all')){
                $bank_title = $mainbank->name;
            }
            if(!empty($bank) && $bank != 'all'){
                $bank_title = $bank->name." [".generateMfo($bank->mfo_id)."]";
            }
            if(!empty($cat_account) && (empty($request->get('accountsheet')) || $request->get('accountsheet') == 'all')){
                $head_title = $cat_account->name." [".$cat_account->account_id."]";
            }
            if(!empty($request->get('accountsheet')) && $request->get('accountsheet') != 'all'){
                $head_title = $account_sheet->name." [".$account_sheet->account_id."]";
            }
            if($mainbank == 'all' || (empty($mainbank) && empty($bank))){
                $bank_title = trans('app.all banks');
            }
            if(empty($place_title)){
                $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first();
                $place_title = $region->name;
            }
            $colors = getColors();
            $info = array(
                'chart' => $output,
                'head_title' => $head_title,
                'place_title' => $place_title,
                'bank_title' => $bank_title,
                'time_title' => trans('app.shortmonth'.intval($month))." ".$year,
                'fillial_view' => $fillial_view,
                'account_sheet_view' => $account_sheet_view,
                'colors' => $colors
            );
            $data = json_encode($info);
            echo $data;
            // return view('chart.piecharts', compact('title', 'cities', 'title_head', 'regions', 'mainbanks', 'cat_accounts', 'data', 'cat_account', 'mainbank', 'monthyear', 'fillials', 'region', 'account_sheets', 'account_sheet'));
        }else{
            return view('chart.piecharts', compact('title', 'regions', 'mainbanks', 'cat_accounts', 'cities'));
        }
        
    }


    public function line_chart(Request $request){
        $title = trans('app.linear chart report');
        $user = Auth::user();
        $position = get_position($user);
        $regions = DB::table('regions');
        $cities = DB::table('cities');
        $mainbanks = DB::table('mainbanks');
        if($position != 'admin' || $position != 'country'){
            $regions = $regions->where('id', '=', $user->region_id);
            $cities = $cities->where('region_id', '=', $user->region_id);
            $banks = DB::table('banks')->
            select('mainbank_id')->where([['banks.region_work_id', '=', $user->region_id], ['banks.mainbank_id', '!=', 38]])
            ->groupBy('mainbank_id')->get()->toArray();
            $mainbanks = $mainbanks->where(function($query) use($banks){
                foreach ($banks as $bank) {
                    $query->orWhere('id', '=', $bank->mainbank_id);
                }
            });
        } 
        $regions = $regions->orderBy('name')->get()->toArray();
        $cities = $cities->orderBy('name')->get()->toArray();
        $mainbanks = $mainbanks->orderBy('name')->get()->toArray();
        $cat_accounts = DB::table('cat_account_sheets')->orderBy('account_id')->get()->toArray();
        if($request->post()){
            $bank = $request->get('fillial');
            $region = $request->get('region');
            $city = $request->get('city');
            $mainbank = $request->get('mainbank');
            $cat_account = $request->get('cataccount');
            $startmonthyear = $request->get('startmonthyear');
            $endmonthyear = $request->get('endmonthyear');
            $account_sheet = $request->get('accountsheet');
            $startmonth = intval(date('m', strtotime($startmonthyear)));
            $startyear = date('Y', strtotime($startmonthyear));
            $endmonth = intval(date('m', strtotime($endmonthyear)));
            $endyear = date('Y', strtotime($endmonthyear));
            if(!empty($bank) && $bank != 'all'){
                $bank = DB::table('banks')->where('id', '=', $bank)->get()->first();
            }
            if($account_sheet != 'all'){
                $account_sheet = DB::table('account_sheets')->where('id', '=', $account_sheet)->get()->first();
            }
            if(!empty($cat_account) && $cat_account != 'all'){
                $account_sheets = DB::table('account_sheets')->where('cat_id', '=', $cat_account)->get()->toArray();
                $cat_account = DB::table('cat_account_sheets')->where('id', '=', $cat_account)->get()->first();
            }
            if(!empty($mainbank)  && $mainbank != 'all'){
                $mainbank = DB::table('mainbanks')->where('id', '=', $mainbank)->get()->first();
            }
            if(!empty($mainbank)  && $mainbank != 'all'){
                $fillials = DB::table('banks')->where('mainbank_id', '=', $mainbank->id);
                if(!empty($region) && $region != 'all'){
                    $fillials = $fillials->where('region_id', '=', $region);
                }
                $fillials = $fillials->get()->toArray();
            }
            $output = array();
            $mfo_s = array();
            $mainbank_s = array();
            $table = 'balance_'.$endyear;
            if((empty($mainbank) || $mainbank == 'all') && ($city == 'all' || empty($city))){
            	$mainbank_view = true;
            }elseif(!empty($city) && $city != 'all' && $mainbank == 'all'){
            	$mainbank_view = false;
            }else{
            	$mainbank_view = false;
            }
            if(((!empty($bank) && $bank != 'all' && $account_sheet == 'all') || (!empty($mainbank) && $mainbank != 'all' && $account_sheet == 'all')) && !empty($cat_account)){
                $account_sheets_view = true;
            }else{
                $account_sheets_view = false;
            }
            
            if($account_sheets_view){
                if($startyear != $endyear){
                    for ($y=$startyear; $y <= $endyear; $y++) {
                        $data = null;
                        $balance = null;
                        $table = 'balance_'.$y;
                        $data = DB::table($table)->
                            select(
                                $table.'.*',
                                'banks.mfo_id',
                                'banks.name',
                                'banks.mainbank_id'
                            )->
                            join('banks', 'banks.id', '=', $table.'.bank_id');
                        if($y == $endyear){
                            $data = $data->where([[$table.'.month', '<=', $endmonth], [$table.'.year', '=', $endyear]]);
                        }elseif($y == $startyear){
                            $data = $data->where([[$table.'.month', '>=', $startmonth], [$table.'.year', '=', $startyear]]);
                        }
                        
                        if(!empty($bank)  && $bank != 'all'){
                            $data = $data->where($table.'.bank_id', '=', $bank->id);
                        }
                        if(!empty($mainbank) && $mainbank != 'all'){
                            $data = $data->where(function($query) use($mainbank){
                                $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                            });
                        }
                        if(!empty($region) && $region != 'all'){
                            $data = $data->where('banks.region_work_id', '=', $region);
                        }
                        if(!empty($city) &&  $city != 'all'){
                            $data = $data->where('banks.city_id', '=', $city);
                        }
                        $balance = $data->orderBy('month', 'ASC')->get()->toArray();
                        if(!empty($balance)){
                            foreach ($balance as $balance_data) {
                                if(!empty($output)){
                                    $i = 0;
                                    foreach ($output as $output_data) {
                                        $sum = 0;
                                        $currency = 0;
                                        $balance_info = json_decode($balance_data->accounting);
                                        foreach ($balance_info as $balance_sheet) {
                                            if($balance_sheet->account_sheet_id == $output_data['data_id']){
                                                $sum = intval($balance_sheet->sum);
                                                $currency = intval($balance_sheet->currency);
                                            }
                                        }
                                        if(in_array(trans('app.shortmonth'.$balance_data->month)." ".$balance_data->year, $output_data['data_monthyear'])){
                                            foreach ($output_data['data_monthyear'] as $count => $info) {
                                                if($balance_data->month == $output_data['month'][$count] && $balance_data->year == $output_data['year'][$count]){
                                                    $output[$i]['sum'][$count] = $output[$i]['sum'][$count] + $sum;
                                                    $output[$i]['currency'][$count] = $output[$i]['currency'][$count] + $currency;
                                                }
                                            }
                                        }
                                        $i++;
                                    }
                                }else{
                                    $month_array = array();
                                    $year_array = array();
                                    $month_name_array = array();
                                    $sum_array = array();
                                    $currency_array = array();
                                    if($startyear != $endyear){
                                        for($b = $startyear; $b <= $endyear; $b++){
                                            if($b != $endyear){
                                                for($g = $startmonth; $g <= 12; $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($sum_array, 0);
                                                    array_push($currency_array, 0);
                                                }
                                            }else{
                                                for($g = 1; $g <= intval($endmonth); $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($sum_array, 0);
                                                    array_push($currency_array, 0);
                                                }
                                            }
                                        }
                                    }else{
                                        for($k = intval($startmonth); $k <= $endmonth; $k++){
                                            array_push($month_array, $k);
                                            array_push($year_array, $endyear);
                                            array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                            array_push($sum_array, 0);
                                            array_push($currency_array, 0);
                                        }
                                    }
                                    foreach ($account_sheets as $acc) {
                                        $sum = 0;
                                        $currency = 0;
                                        $new_balance = array(
                                            'data_id' => $acc->account_id,
                                            'data_name' => $acc->name,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'sum' => $sum_array,
                                            'currency' => $currency_array
                                        );
                                        $balance_info = json_decode($balance_data->accounting);
                                        foreach ($balance_info as $balance_sheet) {
                                            if($balance_sheet->account_sheet_id == $acc->account_id){
                                                $sum =  intval($balance_sheet->sum);
                                                $currency = intval($balance_sheet->currency);
                                            }
                                        }
                                        if($new_balance['data_monthyear'][0] === trans('app.shortmonth'.intval($balance_data->month))." ".$balance_data->year){
                                            $new_balance['sum'][0] = $sum;
                                            $new_balance['currency'][0] = $currency;
                                        }
                                        array_push($output, $new_balance);
                                    }
                                }
                            }
                        }
                    }
                }else{
                    $table = 'balance_'.$endyear;
                    $data = DB::table($table)->
                        select(
                            $table.'.*',
                            'banks.mfo_id',
                            'banks.name',
                            'banks.mainbank_id'
                        )->
                        join('banks', 'banks.id', '=', $table.'.bank_id')->
                    where([[$table.'.month', '>=', $startmonth], [$table.'.year', '>=', $startyear], [$table.'.month', '<=', $endmonth], [$table.'.year', '<=', $endyear]]);
                    if(!empty($bank)  && $bank != 'all'){
                        $data = $data->where($table.'.bank_id', '=', $bank->id);
                    }
                    if(!empty($mainbank) && $mainbank != 'all'){
                        $data = $data->where(function($query) use($mainbank){
                            $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                        });
                    }
                    if(!empty($region) && $region != 'all'){
                        $data = $data->where('banks.region_work_id', '=', $region);
                    }
                    if(!empty($city) &&  $city != 'all'){
                        $data = $data->where('banks.city_id', '=', $city);
                    }
                    $balance = $data->orderBy('month', 'ASC')->get()->toArray();
                    if(!empty($balance)){
                        foreach ($balance as $balance_data) {
                            if(!empty($output)){
                                $i = 0;
                                foreach ($output as $output_data) {
                                    $sum = 0;
                                    $currency = 0;
                                    $balance_info = json_decode($balance_data->accounting);
                                    foreach ($balance_info as $balance_sheet) {
                                        if($balance_sheet->account_sheet_id == $output_data['data_id']){
                                            $sum = intval($balance_sheet->sum);
                                            $currency = intval($balance_sheet->currency);
                                        }
                                    }
                                    if(in_array(trans('app.shortmonth'.$balance_data->month)." ".$balance_data->year, $output_data['data_monthyear'])){
                                        foreach ($output_data['data_monthyear'] as $count => $info) {
                                            if($balance_data->month == $output_data['month'][$count] && $balance_data->year == $output_data['year'][$count]){
                                                $output[$i]['sum'][$count] = $output[$i]['sum'][$count] + $sum;
                                                $output[$i]['currency'][$count] = $output[$i]['currency'][$count] + $currency;
                                            }
                                        }
                                    }
                                    $i++;
                                }
                            }else{
                                $month_array = array();
                                $year_array = array();
                                $month_name_array = array();
                                $sum_array = array();
                                $currency_array = array();
                                if($startyear != $endyear){
                                    for($b = $startyear; $b <= $endyear; $b++){
                                        if($b != $endyear){
                                            for($g = $startmonth; $g <= 12; $g++){
                                                array_push($month_array, $g);
                                                array_push($year_array, $b);
                                                array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                array_push($sum_array, 0);
                                                array_push($currency_array, 0);
                                            }
                                        }else{
                                            for($g = 1; $g <= intval($endmonth); $g++){
                                                array_push($month_array, $g);
                                                array_push($year_array, $b);
                                                array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                array_push($sum_array, 0);
                                                array_push($currency_array, 0);
                                            }
                                        }
                                    }
                                }else{
                                    for($k = intval($startmonth); $k <= $endmonth; $k++){
                                        array_push($month_array, $k);
                                        array_push($year_array, $endyear);
                                        array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                        array_push($sum_array, 0);
                                        array_push($currency_array, 0);
                                    }
                                }
                                foreach ($account_sheets as $acc) {
                                    $sum = 0;
                                    $currency = 0;
                                    $new_balance = array(
                                        'data_id' => $acc->account_id,
                                        'data_name' => $acc->name,
                                        'data_monthyear' => $month_name_array,
                                        'month' => $month_array,
                                        'year' => $year_array,
                                        'sum' => $sum_array,
                                        'currency' => $currency_array
                                    );
                                    $balance_info = json_decode($balance_data->accounting);
                                    foreach ($balance_info as $balance_sheet) {
                                        if($balance_sheet->account_sheet_id == $acc->account_id){
                                            $sum =  intval($balance_sheet->sum);
                                            $currency = intval($balance_sheet->currency);
                                        }
                                    }
                                    if($new_balance['data_monthyear'][0] === trans('app.shortmonth'.intval($balance_data->month))." ".$balance_data->year){
                                        $new_balance['sum'][0] = $sum;
                                        $new_balance['currency'][0] = $currency;
                                    }
                                    array_push($output, $new_balance);
                                }
                            }
                        }
                    }
                }
            }else{
                if($startyear != $endyear){
                    for ($y=$startyear; $y <= $endyear; $y++) {
                        $data = null;
                        $balance = null;
                        $table = 'balance_'.$y;
                        $data = DB::table($table)->
                            select(
                                $table.'.*',
                                'banks.mfo_id',
                                'banks.name',
                                'banks.mainbank_id'
                            )->
                            join('banks', 'banks.id', '=', $table.'.bank_id');
                        if($y == $endyear){
                            $data = $data->where([[$table.'.month', '<=', $endmonth], [$table.'.year', '=', $endyear]]);
                        }elseif($y == $startyear){
                            $data = $data->where([[$table.'.month', '>=', $startmonth], [$table.'.year', '=', $startyear]]);
                        }
                        
                        if(!empty($bank)  && $bank != 'all'){
                            $data = $data->where($table.'.bank_id', '=', $bank->id);
                        }
                        if(!empty($mainbank) && $mainbank != 'all'){
                            $data = $data->where(function($query) use($mainbank){
                                $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                            });
                        }
                        if(!empty($region) && $region != 'all'){
                            $data = $data->where('banks.region_work_id', '=', $region);
                        }
                        if(!empty($city) &&  $city != 'all'){
                            $data = $data->where('banks.city_id', '=', $city);
                        }
                        $balance = $data->orderBy('month', 'ASC')->get()->toArray();
                        //print_r($balance);
                        if($request->get('accountsheet') && $request->get('accountsheet') != 'all'){
                            if(!empty($balance)){
                                foreach ($balance as $balance_data) {
                                    if(!empty($output)){
                                        $i = 0;
                                        foreach ($output as $output_data) {
                                            if(!$mainbank_view && $output_data['data_id'] == generateMfo($balance_data->mfo_id)){
                                                $balance_info = json_decode($balance_data->accounting);
                                                $sum = 0;
                                                $currency = 0;
                                                foreach ($balance_info as $balance_sheet) {
                                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                        $sum = intval($balance_sheet->sum);
                                                        $currency = intval($balance_sheet->currency);
                                                        
                                                    } 
                                                }
                                                if(in_array(trans('app.shortmonth'.$balance_data->month)." ".$balance_data->year, $output_data['data_monthyear'])){
                                                    foreach ($output_data['data_monthyear'] as $count => $info) {
                                                        if($balance_data->month == $output_data['month'][$count] && $balance_data->year == $output_data['year'][$count]){
                                                            $output[$i]['sum'][$count] = $output[$i]['sum'][$count] + $sum;
                                                            $output[$i]['currency'][$count] = $output[$i]['currency'][$count] + $currency;
                                                        }
                                                    }
                                                }
                                                
                                            }elseif($mainbank_view && $output_data['data_id'] == $balance_data->mainbank_id){
                                                $sum = 0;
                                                $currency = 0;
                                                $balance_info = json_decode($balance_data->accounting);
                                                foreach ($balance_info as $balance_sheet) {
                                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                        $sum = $sum + intval($balance_sheet->sum);
                                                        $currency = $currency + intval($balance_sheet->currency);
                                                    }
                                                }
                                                if(in_array(trans('app.shortmonth'.$balance_data->month)." ".$balance_data->year, $output_data['data_monthyear'])){
                                                    foreach ($output_data['data_monthyear'] as $count => $info) {
                                                        if($balance_data->month == $output_data['month'][$count] && $balance_data->year == $output_data['year'][$count]){
                                                            $output[$i]['sum'][$count] = $output[$i]['sum'][$count] + $sum;
                                                            $output[$i]['currency'][$count] = $output[$i]['currency'][$count] + $currency;
                                                        }
                                                    }
                                                } 
                                            }
                                            $i++;
                                        }
                                    }else{
                                        $month_array = array();
                                        $year_array = array();
                                        $month_name_array = array();
                                        $sum_array = array();
                                        $currency_array = array();
                                        if($startyear != $endyear){
                                            for($b = $startyear; $b <= $endyear; $b++){
                                                if($b != $endyear){
                                                    for($g = $startmonth; $g <= 12; $g++){
                                                        array_push($month_array, $g);
                                                        array_push($year_array, $b);
                                                        array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                        array_push($sum_array, 0);
                                                        array_push($currency_array, 0);
                                                    }
                                                }else{
                                                    for($g = 1; $g <= intval($endmonth); $g++){
                                                        array_push($month_array, $g);
                                                        array_push($year_array, $b);
                                                        array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                        array_push($sum_array, 0);
                                                        array_push($currency_array, 0);
                                                    }
                                                }
                                            }
                                        }else{
                                            for($k = intval($startmonth); $k <= $endmonth; $k++){
                                                array_push($month_array, $k);
                                                array_push($year_array, $endyear);
                                                array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                                array_push($sum_array, 0);
                                                array_push($currency_array, 0);
                                            }
                                        }
                                        if($mainbank_view){
                                            $position = get_position($user);
                                            $banks = DB::table('banks')->
                                            select('mainbank_id')->where('banks.mainbank_id', '!=', 38);
                                            if($position == 'admin' || $position == 'country'){
                                                $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                            }else{
                                                $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                            }
                                            if(!empty($city)  && $city != 'all'){
                                                $banks = $banks->where('banks.city_id', '=', $city);
                                            }
                                            $banks = $banks->groupBy('mainbank_id')->get()->toArray();
                                            $mainbankss = DB::table('mainbanks')->where(function($query) use($banks){
                                                foreach ($banks as $bank_default) {
                                                    $query->orWhere('id', '=', $bank_default->mainbank_id);
                                                }
                                            })->orderBy('name')->get()->toArray();
                                            
                                            foreach ($mainbankss as $mbank) {
                                                $new_balance = array(
                                                    'data_id' => $mbank->id,
                                                    'data_name' => $mbank->name,
                                                    'data_monthyear' => $month_name_array,
                                                    'month' => $month_array,
                                                    'year' => $year_array,
                                                    'sum' => $sum_array,
                                                    'currency' => $currency_array
                                                );
                                                $sum = 0;
                                                $currency = 0;
                                                if($balance_data->mainbank_id == $mbank->id){
                                                    $balance_info = json_decode($balance_data->accounting);
                                                    foreach ($balance_info as $balance_sheet) {
                                                        if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                            $sum = $sum + intval($balance_sheet->sum);
                                                            $currency = $currency + intval($balance_sheet->currency);
                                                        }
                                                    }
                                                    if($new_balance['data_monthyear'][0] === trans('app.shortmonth'.intval($balance_data->month))." ".$balance_data->year){
                                                        $new_balance['sum'][0] = $sum;
                                                        $new_balance['currency'][0] = $currency;
                                                    }
                                                }
                                                array_push($output, $new_balance);
                                            }
                                        }else{
                                            $banks = DB::table('banks');
                                            if($position == 'admin' || $position == 'country'){
                                                $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                            }else{
                                                $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                            }
                                            if(!empty($city)  && $city != 'all'){
                                                $banks = $banks->where('banks.city_id', '=', $city);
                                            }
                                            if(!empty($bank) && $bank != 'all'){
                                                $banks = $banks->where('banks.id', '=', $bank->id);
                                            }
                                            if(!empty($mainbank)  && $mainbank != 'all'){
                                                $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                            }
                                            $banks = $banks->get()->toArray();
                                            foreach ($banks as $bank_default) {
                                                $new_balance = array(
                                                    'data_id' => generateMfo($bank_default->mfo_id),
                                                    'data_name' => $bank_default->name,
                                                    'data_monthyear' => $month_name_array,
                                                    'month' => $month_array,
                                                    'year' => $year_array,
                                                    'sum' => $sum_array,
                                                    'currency' => $currency_array
                                                );
                                                $sum = 0;
                                                $currency = 0;
                                                if($balance_data->mfo_id == $bank_default->mfo_id){
                                                    $balance_info = json_decode($balance_data->accounting);
                                                    foreach ($balance_info as $balance_sheet) {
                                                        if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                            $sum = $sum + intval($balance_sheet->sum);
                                                            $currency = $currency + intval($balance_sheet->currency);
                                                        }
                                                    }
                                                    if($new_balance['data_monthyear'][0] === trans('app.shortmonth'.intval($balance_data->month))." ".$balance_data->year){
                                                        $new_balance['sum'][0] = $sum;
                                                        $new_balance['currency'][0] = $currency;
                                                    }
                                                }
                                                array_push($mfo_s, $bank_default->mfo_id);
                                            }
                                        }
                                    }
                                }
                            }
                        }else{
                            if(!empty($balance)){
                                foreach ($balance as $balance_data) {
                                    if(!empty($output)){
                                        $i = 0;
                                        foreach ($output as $output_data) {
                                            if(!$mainbank_view && $output_data['data_id'] == generateMfo($balance_data->mfo_id)){
                                                $sum = 0;
                                                $currency = 0;
                                                foreach ($account_sheets as $account_sheet) {
                                                    $balance_info = json_decode($balance_data->accounting);
                                                    foreach ($balance_info as $balance_sheet) {
                                                        if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                            $sum = $sum + intval($balance_sheet->sum);
                                                            $currency = $currency + intval($balance_sheet->currency);
                                                        }
                                                    }
                                                }
                                                if(in_array(trans('app.shortmonth'.$balance_data->month)." ".$balance_data->year, $output_data['data_monthyear'])){
                                                    foreach ($output_data['data_monthyear'] as $count => $info) {
                                                        if($balance_data->month == $output_data['month'][$count] && $balance_data->year == $output_data['year'][$count]){
                                                            $output[$i]['sum'][$count] = $output[$i]['sum'][$count] + $sum;
                                                            $output[$i]['currency'][$count] = $output[$i]['currency'][$count] + $currency;
                                                        }
                                                    }
                                                }
                                                
                                            }elseif($mainbank_view && $output_data['data_id'] == $balance_data->mainbank_id){
                                                $sum = 0;
                                                $currency = 0;
                                                foreach ($account_sheets as $account_sheet) {
                                                    $balance_info = json_decode($balance_data->accounting);
                                                    foreach ($balance_info as $balance_sheet) {
                                                        if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                            $sum = $sum + intval($balance_sheet->sum);
                                                            $currency = $currency + intval($balance_sheet->currency);
                                                        }
                                                    }
                                                }
                                                if(in_array(trans('app.shortmonth'.$balance_data->month)." ".$balance_data->year, $output_data['data_monthyear'])){
                                                    foreach ($output_data['data_monthyear'] as $count => $info) {
                                                        if($balance_data->month == $output_data['month'][$count] && $balance_data->year == $output_data['year'][$count]){
                                                            $output[$i]['sum'][$count] = $output[$i]['sum'][$count] + $sum;
                                                            $output[$i]['currency'][$count] = $output[$i]['currency'][$count] + $currency;
                                                        }
                                                    }
                                                }
                                            }
                                            $i++;
                                        }
                                    }else{
                                        $month_array = array();
                                        $year_array = array();
                                        $month_name_array = array();
                                        $sum_array = array();
                                        $currency_array = array();
                                        if($startyear != $endyear){
                                            for($b = $startyear; $b <= $endyear; $b++){
                                                if($b != $endyear){
                                                    for($g = $startmonth; $g <= 12; $g++){
                                                        array_push($month_array, $g);
                                                        array_push($year_array, $b);
                                                        array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                        array_push($sum_array, 0);
                                                        array_push($currency_array, 0);
                                                    }
                                                }else{
                                                    for($g = 1; $g <= intval($endmonth); $g++){
                                                        array_push($month_array, $g);
                                                        array_push($year_array, $b);
                                                        array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                        array_push($sum_array, 0);
                                                        array_push($currency_array, 0);
                                                    }
                                                }
                                            }
                                        }else{
                                            for($k = intval($startmonth); $k <= $endmonth; $k++){
                                                array_push($month_array, $k);
                                                array_push($year_array, $endyear);
                                                array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                                array_push($sum_array, 0);
                                                array_push($currency_array, 0);
                                            }
                                        }
                                        if($mainbank_view){
                                            $position = get_position($user);
                                            $banks = DB::table('banks')->
                                            select('mainbank_id');
                                            if($position == 'admin' || $position == 'country'){
                                                $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                            }else{
                                                $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                            }
                                            if(!empty($city)  && $city != 'all'){
                                                $banks = $banks->where('banks.city_id', '=', $city);
                                            }
                                            $banks = $banks->groupBy('mainbank_id')->get()->toArray();
                                            $mainbankss = DB::table('mainbanks')->where(function($query) use($banks){
                                                foreach ($banks as $bank_default) {
                                                    $query->orWhere('id', '=', $bank_default->mainbank_id);
                                                }
                                            })->orderBy('name')->get()->toArray();
                                            foreach ($mainbankss as $mbank) {
                                                $new_balance = array(
                                                    'data_id' => $mbank->id,
                                                    'data_name' => $mbank->name,
                                                    'data_monthyear' => $month_name_array,
                                                    'month' => $month_array,
                                                    'year' => $year_array,
                                                    'sum' => $sum_array,
                                                    'currency' => $currency_array
                                                );
                                                $sum = 0;
                                                $currency = 0;
                                                if($balance_data->mainbank_id == $mbank->id){
                                                    foreach ($account_sheets as $account_sheet){
                                                        $balance_info = json_decode($balance_data->accounting);
                                                        foreach ($balance_info as $balance_sheet) {
                                                            if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                                $sum = $sum + intval($balance_sheet->sum);
                                                                $currency = $currency + intval($balance_sheet->currency);
                                                            }
                                                        }
                                                    }
                                                    if($new_balance['data_monthyear'][0] === trans('app.shortmonth'.intval($balance_data->month))." ".$balance_data->year){
                                                        $new_balance['sum'][0] = $sum;
                                                        $new_balance['currency'][0] = $currency;
                                                    }
                                                }
                                                array_push($output, $new_balance);
                                            }
                                        }else{
                                            $banks = DB::table('banks');
                                            if($position == 'admin' || $position == 'country'){
                                                $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                            }else{
                                                $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                            }
                                            if(!empty($city)  && $city != 'all'){
                                                $banks = $banks->where('banks.city_id', '=', $city);
                                            }
                                            if(!empty($bank) && $bank != 'all'){
                                                $banks = $banks->where('banks.id', '=', $bank->id);
                                            }
                                            if(!empty($mainbank)  && $mainbank != 'all'){
                                                $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                            }
                                            $banks = $banks->get()->toArray();
                                            foreach ($banks as $bank_default) {
                                                $sum = 0;
                                                $currency = 0;
                                                $new_balance = array(
                                                    'data_id' => generateMfo($bank_default->mfo_id),
                                                    'data_name' => $bank_default->name,
                                                    'data_monthyear' => $month_name_array,
                                                    'month' => $month_array,
                                                    'year' => $year_array,
                                                    'sum' => $sum_array,
                                                    'currency' => $currency_array
                                                );
                                                if($balance_data->mfo_id == $bank_default->mfo_id){
                                                    foreach ($account_sheets as $account_sheet){
                                                        $balance_info = json_decode($balance_data->accounting);
                                                        foreach ($balance_info as $balance_sheet) {
                                                            if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                                $sum = $sum + intval($balance_sheet->sum);
                                                                $currency = $currency + intval($balance_sheet->currency);
                                                            }
                                                        }
                                                    }
                                                    if($new_balance['data_monthyear'][0] === trans('app.shortmonth'.intval($balance_data->month))." ".$balance_data->year){
                                                        $new_balance['sum'][0] = $sum;
                                                        $new_balance['currency'][0] = $currency;
                                                    }
                                                }
                                                array_push($output, $new_balance);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }else{
                    $table = 'balance_'.$endyear;
                    $data = DB::table($table)->
                        select(
                            $table.'.*',
                            'banks.mfo_id',
                            'banks.name',
                            'banks.mainbank_id'
                        )->
                        join('banks', 'banks.id', '=', $table.'.bank_id')->
                    where([[$table.'.month', '>=', $startmonth], [$table.'.year', '>=', $startyear], [$table.'.month', '<=', $endmonth], [$table.'.year', '<=', $endyear]]);
                    if(!empty($bank)  && $bank != 'all'){
                        $data = $data->where($table.'.bank_id', '=', $bank->id);
                    }
                    if(!empty($mainbank) && $mainbank != 'all'){
                        $data = $data->where(function($query) use($mainbank){
                            $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                        });
                    }
                    if(!empty($region) && $region != 'all'){
                        $data = $data->where('banks.region_work_id', '=', $region);
                    }
                    if(!empty($city) &&  $city != 'all'){
                        $data = $data->where('banks.city_id', '=', $city);
                    }
                    $balance = $data->orderBy('month', 'ASC')->get()->toArray();
                    if($request->get('accountsheet') && $request->get('accountsheet') != 'all'){
                        if(!empty($balance)){
                            foreach ($balance as $balance_data) {
                                if(!empty($output)){
                                    $i = 0;
                                    foreach ($output as $output_data) {
                                        if(!$mainbank_view && $output_data['data_id'] == generateMfo($balance_data->mfo_id)){
                                            $balance_info = json_decode($balance_data->accounting);
                                            $sum = 0;
                                            $currency = 0;
                                            foreach ($balance_info as $balance_sheet) {
                                                if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                    $sum = intval($balance_sheet->sum);
                                                    $currency = intval($balance_sheet->currency);
                                                    
                                                } 
                                            }
                                            if(in_array(trans('app.shortmonth'.$balance_data->month)." ".$balance_data->year, $output_data['data_monthyear'])){
                                                foreach ($output_data['data_monthyear'] as $count => $info) {
                                                    if($balance_data->month == $output_data['month'][$count] && $balance_data->year == $output_data['year'][$count]){
                                                        $output[$i]['sum'][$count] = $output[$i]['sum'][$count] + $sum;
                                                        $output[$i]['currency'][$count] = $output[$i]['currency'][$count] + $currency;
                                                    }
                                                }
                                            }
                                            
                                        }elseif($mainbank_view && $output_data['data_id'] == $balance_data->mainbank_id){
                                            $sum = 0;
                                            $currency = 0;
                                            $balance_info = json_decode($balance_data->accounting);
                                            foreach ($balance_info as $balance_sheet) {
                                                if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                    $sum = $sum + intval($balance_sheet->sum);
                                                    $currency = $currency + intval($balance_sheet->currency);
                                                }
                                            }
                                            if(in_array(trans('app.shortmonth'.$balance_data->month)." ".$balance_data->year, $output_data['data_monthyear'])){
                                                foreach ($output_data['data_monthyear'] as $count => $info) {
                                                    if($balance_data->month == $output_data['month'][$count] && $balance_data->year == $output_data['year'][$count]){
                                                        $output[$i]['sum'][$count] = $output[$i]['sum'][$count] + $sum;
                                                        $output[$i]['currency'][$count] = $output[$i]['currency'][$count] + $currency;
                                                    }
                                                }
                                            } 
                                        }
                                        $i++;
                                    }
                                }else{
                                    $month_array = array();
                                    $year_array = array();
                                    $month_name_array = array();
                                    $sum_array = array();
                                    $currency_array = array();
                                    if($startyear != $endyear){
                                        for($b = $startyear; $b <= $endyear; $b++){
                                            if($b != $endyear){
                                                for($g = $startmonth; $g <= 12; $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($sum_array, 0);
                                                    array_push($currency_array, 0);
                                                }
                                            }else{
                                                for($g = 1; $g <= intval($endmonth); $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($sum_array, 0);
                                                    array_push($currency_array, 0);
                                                }
                                            }
                                        }
                                    }else{
                                        for($k = intval($startmonth); $k <= $endmonth; $k++){
                                            array_push($month_array, $k);
                                            array_push($year_array, $endyear);
                                            array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                            array_push($sum_array, 0);
                                            array_push($currency_array, 0);
                                        }
                                    }
                                    if($mainbank_view){
                                        $position = get_position($user);
                                        $banks = DB::table('banks')->
                                        select('mainbank_id');
                                        if($position == 'admin' || $position == 'country'){
                                            $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                        }else{
                                            $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                        }
                                        if(!empty($city)  && $city != 'all'){
                                            $banks = $banks->where('banks.city_id', '=', $city);
                                        }
                                        $banks = $banks->groupBy('mainbank_id')->get()->toArray();
                                        $mainbankss = DB::table('mainbanks')->where(function($query) use($banks){
                                            foreach ($banks as $bank_default) {
                                                $query->orWhere('id', '=', $bank_default->mainbank_id);
                                            }
                                        })->orderBy('name')->get()->toArray();
                                        
                                        foreach ($mainbankss as $mbank) {
                                            $new_balance = array(
                                                'data_id' => $mbank->id,
                                                'data_name' => $mbank->name,
                                                'data_monthyear' => $month_name_array,
                                                'month' => $month_array,
                                                'year' => $year_array,
                                                'sum' => $sum_array,
                                                'currency' => $currency_array
                                            );
                                            $sum = 0;
                                            $currency = 0;
                                            if($balance_data->mainbank_id == $mbank->id){
                                                $balance_info = json_decode($balance_data->accounting);
                                                foreach ($balance_info as $balance_sheet) {
                                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                        $sum = $sum + intval($balance_sheet->sum);
                                                        $currency = $currency + intval($balance_sheet->currency);
                                                    }
                                                }
                                                if($new_balance['data_monthyear'][0] === trans('app.shortmonth'.intval($balance_data->month))." ".$balance_data->year){
                                                    $new_balance['sum'][0] = $sum;
                                                    $new_balance['currency'][0] = $currency;
                                                }
                                            }
                                            array_push($output, $new_balance);
                                        }
                                    }else{
                                        $banks = DB::table('banks');
                                        if($position == 'admin' || $position == 'country'){
                                            $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                        }else{
                                            $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                        }
                                        if(!empty($city)  && $city != 'all'){
                                            $banks = $banks->where('banks.city_id', '=', $city);
                                        }
                                        if(!empty($bank) && $bank != 'all'){
                                            $banks = $banks->where('banks.id', '=', $bank->id);
                                        }
                                        if(!empty($mainbank)  && $mainbank != 'all'){
                                            $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                        }
                                        $banks = $banks->get()->toArray();
                                        foreach ($banks as $bank_default) {
                                            $new_balance = array(
                                                'data_id' => generateMfo($bank_default->mfo_id),
                                                'data_name' => $bank_default->name,
                                                'data_monthyear' => $month_name_array,
                                                'month' => $month_array,
                                                'year' => $year_array,
                                                'sum' => $sum_array,
                                                'currency' => $currency_array
                                            );
                                            $sum = 0;
                                            $currency = 0;
                                            if($balance_data->mfo_id == $bank_default->mfo_id){
                                                $balance_info = json_decode($balance_data->accounting);
                                                foreach ($balance_info as $balance_sheet) {
                                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                        $sum = $sum + intval($balance_sheet->sum);
                                                        $currency = $currency + intval($balance_sheet->currency);
                                                    }
                                                }
                                                if($new_balance['data_monthyear'][0] === trans('app.shortmonth'.intval($balance_data->month))." ".$balance_data->year){
                                                    $new_balance['sum'][0] = $sum;
                                                    $new_balance['currency'][0] = $currency;
                                                }
                                            }
                                            array_push($output, $new_balance);
                                        }
                                    }
                                }
                            }
                        }
                    }else{
                        if(!empty($balance)){
                            foreach ($balance as $balance_data) {
                                if(!empty($output)){
                                    $i = 0;
                                    foreach ($output as $output_data) {
                                        if(!$mainbank_view && $output_data['data_id'] == generateMfo($balance_data->mfo_id)){
                                            $sum = 0;
                                            $currency = 0;
                                            foreach ($account_sheets as $account_sheet) {
                                                $balance_info = json_decode($balance_data->accounting);
                                                foreach ($balance_info as $balance_sheet) {
                                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                        $sum = $sum + intval($balance_sheet->sum);
                                                        $currency = $currency + intval($balance_sheet->currency);
                                                    }
                                                }
                                            }
                                            if(in_array(trans('app.shortmonth'.$balance_data->month)." ".$balance_data->year, $output_data['data_monthyear'])){
                                                foreach ($output_data['data_monthyear'] as $count => $info) {
                                                    if($balance_data->month == $output_data['month'][$count] && $balance_data->year == $output_data['year'][$count]){
                                                        $output[$i]['sum'][$count] = $output[$i]['sum'][$count] + $sum;
                                                        $output[$i]['currency'][$count] = $output[$i]['currency'][$count] + $currency;
                                                    }
                                                }
                                            }
                                            
                                        }elseif($mainbank_view && $output_data['data_id'] == $balance_data->mainbank_id){
                                            $sum = 0;
                                            $currency = 0;
                                            foreach ($account_sheets as $account_sheet) {
                                                $balance_info = json_decode($balance_data->accounting);
                                                foreach ($balance_info as $balance_sheet) {
                                                    if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                        $sum = $sum + intval($balance_sheet->sum);
                                                        $currency = $currency + intval($balance_sheet->currency);
                                                    }
                                                }
                                            }
                                            if(in_array(trans('app.shortmonth'.$balance_data->month)." ".$balance_data->year, $output_data['data_monthyear'])){
                                                foreach ($output_data['data_monthyear'] as $count => $info) {
                                                    if($balance_data->month == $output_data['month'][$count] && $balance_data->year == $output_data['year'][$count]){
                                                        $output[$i]['sum'][$count] = $output[$i]['sum'][$count] + $sum;
                                                        $output[$i]['currency'][$count] = $output[$i]['currency'][$count] + $currency;
                                                    }
                                                }
                                            }
                                        }
                                        $i++;
                                    }
                                }else{
                                    $month_array = array();
                                    $year_array = array();
                                    $month_name_array = array();
                                    $sum_array = array();
                                    $currency_array = array();
                                    if($startyear != $endyear){
                                        for($b = $startyear; $b <= $endyear; $b++){
                                            if($b != $endyear){
                                                for($g = 1; $g <= 12; $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($sum_array, 0);
                                                    array_push($currency_array, 0);
                                                }
                                            }else{
                                                for($g = 1; $g <= intval($endmonth); $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($sum_array, 0);
                                                    array_push($currency_array, 0);
                                                }
                                            }
                                        }
                                    }else{
                                        for($k = intval($startmonth); $k <= $endmonth; $k++){
                                            array_push($month_array, $k);
                                            array_push($year_array, $endyear);
                                            array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                            array_push($sum_array, 0);
                                            array_push($currency_array, 0);
                                        }
                                    }
                                    if($mainbank_view){
                                        $position = get_position($user);
                                        $banks = DB::table('banks')->
                                        select('mainbank_id')->where('banks.mainbank_id', '!=', 38);
                                        if($position == 'admin' || $position == 'country'){
                                            $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                        }else{
                                            $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                        }
                                        if(!empty($city)  && $city != 'all'){
                                            $banks = $banks->where('banks.city_id', '=', $city);
                                        }
                                        $banks = $banks->groupBy('mainbank_id')->get()->toArray();
                                        $mainbankss = DB::table('mainbanks')->where(function($query) use($banks){
                                            foreach ($banks as $bank_default) {
                                                $query->orWhere('id', '=', $bank_default->mainbank_id);
                                            }
                                        })->orderBy('name')->get()->toArray();
                                        foreach ($mainbankss as $mbank) {
                                            $new_balance = array(
                                                'data_id' => $mbank->id,
                                                'data_name' => $mbank->name,
                                                'data_monthyear' => $month_name_array,
                                                'month' => $month_array,
                                                'year' => $year_array,
                                                'sum' => $sum_array,
                                                'currency' => $currency_array
                                            );
                                            $sum = 0;
                                            $currency = 0;
                                            if($balance_data->mainbank_id == $mbank->id){
                                                foreach ($account_sheets as $account_sheet){
                                                    $balance_info = json_decode($balance_data->accounting);
                                                    foreach ($balance_info as $balance_sheet) {
                                                        if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                            $sum = $sum + intval($balance_sheet->sum);
                                                            $currency = $currency + intval($balance_sheet->currency);
                                                        }
                                                    }
                                                }
                                                if($new_balance['data_monthyear'][0] === trans('app.shortmonth'.intval($balance_data->month))." ".$balance_data->year){
                                                    $new_balance['sum'][0] = $sum;
                                                    $new_balance['currency'][0] = $currency;
                                                }
                                            }
                                            array_push($output, $new_balance);
                                        }
                                    }else{
                                        
                                        $banks = DB::table('banks');
                                        if($position == 'admin' || $position == 'country'){
                                            $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                        }else{
                                            $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                        }
                                        if(!empty($city)  && $city != 'all'){
                                            $banks = $banks->where('banks.city_id', '=', $city);
                                        }
                                        if(!empty($bank) && $bank != 'all'){
                                            $banks = $banks->where('banks.id', '=', $bank->id);
                                        }
                                        if(!empty($mainbank)  && $mainbank != 'all'){
                                            $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                        }
                                        $banks = $banks->get()->toArray();
                                        foreach ($banks as $bank_default) {
                                            $sum = 0;
                                            $currency = 0;
                                            $new_balance = array(
                                                'data_id' => generateMfo($bank_default->mfo_id),
                                                'data_name' => $bank_default->name,
                                                'data_monthyear' => $month_name_array,
                                                'month' => $month_array,
                                                'year' => $year_array,
                                                'sum' => $sum_array,
                                                'currency' => $currency_array
                                            );
                                            if($balance_data->mfo_id == $bank_default->mfo_id){
                                                foreach ($account_sheets as $account_sheet){
                                                    $balance_info = json_decode($balance_data->accounting);
                                                    foreach ($balance_info as $balance_sheet) {
                                                        if($balance_sheet->account_sheet_id == $account_sheet->account_id){
                                                            $sum = $sum + intval($balance_sheet->sum);
                                                            $currency = $currency + intval($balance_sheet->currency);
                                                        }
                                                    }
                                                }
                                                if($new_balance['data_monthyear'][0] === trans('app.shortmonth'.intval($balance_data->month))." ".$balance_data->year){
                                                    $new_balance['sum'][0] = $sum;
                                                    $new_balance['currency'][0] = $currency;
                                                }
                                            }
                                            array_push($output, $new_balance);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($region) && $region != 'all'){
                $region = DB::table('regions')->where('id', '=', $region)->get()->first();
            }
            if(!empty($city) && $city != 'all'){
                $city = DB::table('cities')->where('id', '=', $city)->get()->first();
            }
            $place_title = null;
            $bank_title = null;
            $head_title = null;
            if(!empty($region) && $region != 'all' && (empty($city) || $city == 'all') ){
                $place_title = $region->name;
            }
            if(!empty($city) && $city != 'all'){
                $place_title = $city->name;
            }
            if(!empty($mainbank) && $mainbank != 'all' && (empty($bank) || $bank == 'all')){
                $bank_title = $mainbank->name;
            }
            if(!empty($bank) && $bank != 'all'){
                $bank_title = $bank->name." [".generateMfo($bank->mfo_id)."]";
            }
            if(!empty($cat_account) && (empty($request->get('accountsheet')) || $request->get('accountsheet') == 'all')){
                $head_title = $cat_account->name." [".$cat_account->account_id."]";
            }
            if(!empty($request->get('accountsheet')) && $request->get('accountsheet') != 'all'){
                $head_title = $account_sheet->name." [".$account_sheet->account_id."]";
            }
            if(($mainbank == 'all' && $bank == 'all') || (empty($mainbank) && empty($bank)) || (empty($mainbank) && $bank == 'all') || ($mainbank == 'all' && empty($bank))){
                $bank_title = trans('app.all banks');
            }
            if(empty($place_title)){
                $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first();
                $place_title = $region->name;
            }
            //print_r($balance);
            $colors = getColors();
            $info = array(
                'chart' => $output,
                'head_title' => $head_title,
                'place_title' => $place_title,
                'bank_title' => $bank_title,
                'mainbank_view' => $mainbank_view,
                'time_title' => trans('app.shortmonth'.intval($startmonth))." ".$startyear." - ".trans('app.shortmonth'.intval($endmonth))." ".$endyear,
                'colors' => $colors
            );
            $data = json_encode($info);
            echo $data;
            //return view('chart.linechart', compact('title', 'regions', 'mainbanks', 'cat_accounts', 'data', 'bank', 'cat_account', 'mainbank', 'monthyear', 'fillials', 'region', 'account_sheets', 'account_sheet'));
        }else{
            return view('chart.linechart', compact('title', 'regions', 'mainbanks', 'cat_accounts', 'cities'));
        }
        
    }

    public function rating_chart(Request $request){
        $title = trans('app.rating analysis in chart');
        $user = Auth::user();
        $position = get_position($user);
        $regions = DB::table('regions');
        $cities = DB::table('cities');
        $mainbanks = DB::table('mainbanks');
        if($position != 'admin' || $position != 'country'){
            $regions = $regions->where('id', '=', $user->region_id);
            $cities = $cities->where('region_id', '=', $user->region_id);
            $banks = DB::table('banks')->
            select('mainbank_id')->where('banks.region_work_id', '=', $user->region_id)
            ->groupBy('mainbank_id')->get()->toArray();
            $mainbanks = $mainbanks->where(function($query) use($banks){
                foreach ($banks as $bank) {
                    $query->orWhere('id', '=', $bank->mainbank_id);
                }
            });
        } 
        $regions = $regions->orderBy('name')->get()->toArray();
        $cities = $cities->orderBy('name')->get()->toArray();
        $mainbanks = $mainbanks->where('id', '!=', 38)->orderBy('name')->get()->toArray();
        $rating_types = DB::table('departments')->get()->toArray();
        if($request->post()){
            $bank = $request->get('fillial');
            if($position != 'admin' || $position != 'country'){
                $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first()->id;
            }else{
                $region = $request->get('region');
            }
            $city = $request->get('city');
            $mainbank = $request->get('mainbank');
            $rating_type = $request->get('ratingtype');
            $sub_department = $request->get('sub_department');
            $startmonthyear = $request->get('startmonthyear');
            $endmonthyear = $request->get('endmonthyear');
            $startmonth = date('m', strtotime($startmonthyear));
            $startyear = date('Y', strtotime($startmonthyear));
            $endmonth = date('m', strtotime($endmonthyear));
            $endyear = date('Y', strtotime($endmonthyear));
            if(!empty($bank) && $bank != 'all'){
                $bank = DB::table('banks')->where('id', '=', $bank)->get()->first();
            }
            if(!empty($mainbank)  && $mainbank != 'all'){
                $mainbank = DB::table('mainbanks')->where('id', '=', $mainbank)->get()->first();
            }
            if(!empty($mainbank)  && $mainbank != 'all'){
                $fillials = DB::table('banks')->where('mainbank_id', '=', $mainbank->id);
                if(!empty($region) && $region != 'all'){
                    $fillials = $fillials->where('region_work_id', '=', $region);
                }
                $fillials = $fillials->get()->toArray();
            }
            $output = array();
            $mfo_s = array();
            $mainbank_s = array();
            $table = 'report_'.$endyear;
            if(checkTable('report', $endyear)){
                $ratings = DB::table($table)->
                select(
                    $table.'.*',
                    'banks.name',
                    'banks.mainbank_id'
                )->
                join('banks', 'banks.id', '=', $table.'.bank_id');
                if(!empty($bank)  && $bank != 'all'){
                    $ratings = $ratings->where($table.'.bank_id', '=', $bank->id);
                }
                if(!empty($mainbank) && $mainbank != 'all'){
                    $ratings = $ratings->where(function($query) use($mainbank){
                        $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                    });
                }
                if(!empty($region) && $region != 'all'){
                    $ratings = $ratings->where('banks.region_work_id', '=', $region);
                }
                if(!empty($city) &&  $city != 'all'){
                    $ratings = $ratings->where('banks.city_id', '=', $city);
                }
                $ratings = $ratings->where([[$table.'.month', '>=', $startmonth], [$table.'.month', '<=', $endmonth]])
                ->orderBy('month', 'ASC')->get()->toArray();
                if(!empty($ratings)){
                	if((empty($city) || $city == 'all') && (empty($mainbank) || $mainbank == 'all')){
                		$mainbank_view = true;
                	}elseif(!empty($city) && $city != 'all' && $mainbank == 'all'){
		            	$mainbank_view = false;
		            }else{
		            	$mainbank_view = false;
		            }
		            if(((!empty($mainbank) && $mainbank != 'all') || (!empty($city) && $city != 'all')) && ((empty($bank) || $bank =='all' )|| (!empty($bank) || $bank !='all')) && $rating_type != 'all' && $sub_department !='all'){
		            	$fillial_view = true;
		            }else{
		            	$fillial_view = false;
		            }

		            if((!empty($bank) && $bank != 'all' && $rating_type == 'all') || (!empty($mainbank) && $mainbank != 'all' && $rating_type == 'all')){
		            	$department_view = true;
		            }else{
		            	$department_view = false;
                    }

		            if(((!empty($bank) && $bank != 'all') || (!empty($mainbank) && $mainbank != 'all'))  && $rating_type != 'all' && $rating_type != 'monthly' && $sub_department =='all'){
		            	$subdepartment_view = true;
		            }else{
		            	$subdepartment_view = false;
		            }

		            if($fillial_view){
                        //echo "fill";
		            	if($rating_type != 'all' && !empty($rating_type) && $rating_type != 'monthly'){
	                		$rating_type = DB::table('departments')->where('id', '=', $rating_type)->get()->first();
	                	}
	                	if(!empty($sub_department) && $sub_department != 'all'){
	                		$rating_type = DB::table('sub_departments')->where('id', '=', $sub_department)->get()->first();
	                	}
	                	if($rating_type == 'monthly'){
                            $deparmtents_all = DB::table('departments')->get()->toArray();
	                		foreach ($ratings as $rating) {
                    			if(!empty($output)){
                                    $i = 0;
                                	foreach ($output as $output_data) {
                                        if($output_data['data_id'] == generateMfo($rating->mfo_id)){
                                            $final_result = 0;
                                            foreach ($deparmtents_all as $dep) {
                                                foreach ($rating as $code => $rating_data) {
                                                    if($dep->key == $code){
                                                        if(!empty($rating_data)){
                                                            $rating_info = json_decode($rating_data);
                                                            $final_result = $final_result + number_format($rating_info->final_result, 2);
                                                        }
                                                        
                                                    }
                                                }
                                            }
                                        	if(in_array(trans('app.shortmonth'.$rating->month)." ".$rating->year, $output_data['data_monthyear'])){
                                                foreach ($output_data['data_monthyear'] as $count => $info) {
                                                    //print_r($output);
                                                    if($rating->month == $output_data['month'][$count] && $rating->year == $output_data['year'][$count]){
                                                        $output[$i]['final_result'][$count] = $output[$i]['final_result'][$count] + $final_result;
                                                    }
                                                }
                                                
                                            }
                                             
                                        }
                                        $i++;
                                    }
                                }else{
                                	$position = get_position($user);
                                    $banks = DB::table('banks');
                                    if($position == 'admin' || $position == 'country'){
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }else{
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }
                                    if(!empty($city)  && $city != 'all'){
                                        $banks = $banks->where('banks.city_id', '=', $city);
                                    }
                                    if(!empty($bank) && $bank != 'all'){
                                    	$banks = $banks->where('banks.id', '=', $bank->id);
                                    }
                                    if(!empty($mainbank)  && $mainbank != 'all'){
                                        $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                    }
                                    $banks = $banks->get()->toArray();
                                    $month_array = array();
                                    $year_array = array();
                                    $month_name_array = array();
                                    $final_array = array();
                                    if($startyear != $endyear){
                                        for($b = $startyear; $b <= $endyear; $b++){
                                            if($b != $endyear){
                                                for($g = 1; $g <= 12; $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($final_array, 0);
                                                }
                                            }else{
                                                for($g = 1; $g <= intval($endmonth); $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($final_array, 0);
                                                }
                                            }
                                        }
                                    }else{
                                        for($k = intval($startmonth); $k <= $endmonth; $k++){
                                            array_push($month_array, $k);
                                            array_push($year_array, $endyear);
                                            array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                            array_push($final_array, 0);
                                        }
                                    }
                                    foreach ($banks as $bank_default) {
                                    	$new_rating = array(
                                            'data_id' => generateMfo($bank_default->mfo_id),
                                            'data_name' => $bank_default->name,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'final_result' => $final_array
                                        );
                                        $final_result = 0;
                                    	if($rating->mfo_id == $bank_default->mfo_id){
                                            foreach ($deparmtents_all as $dep) {
                                                foreach ($rating as $code => $rating_data) {
                                                    if($dep->key == $code){
                                                        if(!empty($rating_data)){
                                                            $rating_info = json_decode($rating_data);
                                                            $final_result = $final_result + number_format($rating_info->final_result, 2);
                                                        }
                                                        
                                                    }
                                                }
                                            }
                                    	}
                                    	if($new_rating['data_monthyear'][0] === trans('app.shortmonth'.intval($rating->month))." ".$rating->year){
                                              $new_rating['final_result'][0] = $final_result;
                                        }
                                        array_push($output, $new_rating);
                                    }
                                }
		                    }
	                	}else{
                            foreach ($ratings as $rating) {
                                if(!empty($output)){
                                    $i = 0;
                                    foreach ($output as $output_data) {
                                        if($output_data['data_id'] == generateMfo($rating->mfo_id)){
                                            $final_result = 0;
                                            foreach ($rating as $code => $rating_data){
                                                if($code == $rating_type->key){
                                                    if(!empty($rating_data)){
                                                        $rating_info = json_decode($rating_data);
                                                        $final_result =  number_format($rating_info->final_result, 2);
                                                    }
                                                    if(in_array(trans('app.shortmonth'.$rating->month)." ".$rating->year, $output_data['data_monthyear'])){
                                                        foreach ($output_data['data_monthyear'] as $count => $info) {
                                                            if($rating->month == $output_data['month'][$count] && $rating->year == $output_data['year'][$count]){
                                                                $output[$i]['final_result'][$count] = $output[$i]['final_result'][$count] + $final_result;
                                                            }
                                                        }
                                                        
                                                    }
                                                }
                                            }
                                        }
                                        $i++;
                                    }
                                }else{
                                    $position = get_position($user);
                                    $banks = DB::table('banks');
                                    if($position == 'admin' || $position == 'country'){
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }else{
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }
                                    if(!empty($city)  && $city != 'all'){
                                        $banks = $banks->where('banks.city_id', '=', $city);
                                    }
                                    if(!empty($bank) && $bank != 'all'){
                                        $banks = $banks->where('banks.id', '=', $bank->id);
                                    }
                                    if(!empty($mainbank)  && $mainbank != 'all'){
                                        $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                    }
                                    $banks = $banks->get()->toArray();
                                    $month_array = array();
                                    $year_array = array();
                                    $month_name_array = array();
                                    $final_array = array();
                                    if($startyear != $endyear){
                                        for($b = $startyear; $b <= $endyear; $b++){
                                            if($b != $endyear){
                                                for($g = 1; $g <= 12; $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($final_array, 0);
                                                }
                                            }else{
                                                for($g = 1; $g <= intval($endmonth); $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($final_array, 0);
                                                }
                                            }
                                        }
                                    }else{
                                        for($k = intval($startmonth); $k <= $endmonth; $k++){
                                            array_push($month_array, $k);
                                            array_push($year_array, $endyear);
                                            array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                            array_push($final_array, 0);
                                        }
                                    }
                                    foreach ($banks as $bank_default) {
                                        $new_rating = array(
                                            'data_id' => generateMfo($bank_default->mfo_id),
                                            'data_name' => $bank_default->name,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'final_result' => $final_array
                                        );
                                        $final_result = 0;
                                        if($rating->mfo_id == $bank_default->mfo_id){
                                            foreach ($rating as $code => $rating_data){
                                                if($code == $rating_type->key){
                                                    if(!empty($rating_data)){
                                                        $rating_info = json_decode($rating_data);
                                                        $final_result = number_format($rating_info->final_result);
                                                    }
                                                }
                                            }
                                        }
                                        if($new_rating['data_monthyear'][0] === trans('app.shortmonth'.intval($rating->month))." ".$rating->year){
                                              $new_rating['final_result'][0] = $final_result;
                                        }
                                        array_push($output, $new_rating);
                                    }
                                }
                            }
                        }
	                    
		            }elseif($subdepartment_view){
                        //echo "sub";
                        foreach ($ratings as $rating) {
            				if(!empty($output)){
                                $i = 0;
                            	foreach ($output as $output_data) {
                            		foreach ($rating as $code => $rating_data) {
                            			if($output_data['data_id'] == $code){
                                        	$final_result = 0;
                                        	if(!empty($rating_data)){
                                        		$rating_info = json_decode($rating_data);
                                        		if($mainbank != 'all' && !empty($mainbank) && (empty($bank) || $bank == 'all')){
	                                            	$final_result =  number_format($rating_info->final_result, 2)/$output_data['number'];
                                        		}else{
	                                            	$final_result =  number_format($rating_info->final_result, 2);
                                        		}
                                        		
                                        	}
                                        	if(in_array(trans('app.shortmonth'.$rating->month)." ".$rating->year, $output_data['data_monthyear'])){
                                                foreach ($output_data['data_monthyear'] as $count => $info) {
                                                    if($rating->month == $output_data['month'][$count] && $rating->year == $output_data['year'][$count]){
                                                        $output[$i]['final_result'][$count] = $output[$i]['final_result'][$count] + $final_result;
                                                    }
                                                }
                                            }
                                        }
                            		}
                                    $i++;
                                }
                            }else{
                            	$sub_departments = DB::table('sub_departments')->where('department_id', '=', $rating_type)->get()->toArray();
                                $month_array = array();
                                $year_array = array();
                                $month_name_array = array();
                                $final_array = array();
                                if($startyear != $endyear){
                                    for($b = $startyear; $b <= $endyear; $b++){
                                        if($b != $endyear){
                                            for($g = 1; $g <= 12; $g++){
                                                array_push($month_array, $g);
                                                array_push($year_array, $b);
                                                array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                array_push($final_array, 0);
                                            }
                                        }else{
                                            for($g = 1; $g <= intval($endmonth); $g++){
                                                array_push($month_array, $g);
                                                array_push($year_array, $b);
                                                array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                array_push($final_array, 0);
                                            }
                                        }
                                    }
                                }else{
                                    for($k = intval($startmonth); $k <= $endmonth; $k++){
                                        array_push($month_array, $k);
                                        array_push($year_array, $endyear);
                                        array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                        array_push($final_array, 0);
                                    }
                                }
                            	if($mainbank != 'all' && !empty($mainbank) && (empty($bank) || $bank == 'all')){
                            		$position = get_position($user);
                                    $mainbankss = DB::table('banks')->
                                    select(
                                        DB::raw('count(banks.id) as number'),
                                        'banks.mainbank_id',
                                        'mainbanks.name'
                                    )->
                                    join('mainbanks', 'mainbanks.id', '=', 'banks.mainbank_id');
                                    if($position == 'admin' || $position == 'country'){
                                        $mainbankss = $mainbankss->where('banks.region_work_id', '=', $region_id);
                                    }else{
                                        $mainbankss = $mainbankss->where('banks.region_work_id', '=', $user->region_id);
                                    }
                                    $mainbankss = $mainbankss->where('banks.mainbank_id', '=', $mainbank->id)->groupBy('banks.mainbank_id', 'mainbanks.name')->get()->first();
                                    foreach ($sub_departments as $dep) {
                                    	$new_rating = array(
                                            'data_id' => $dep->key,
                                            'data_name' => $dep->name,
                                            'number' => $mainbankss->number,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'final_result' => $final_array
                                        );
                                        $final_result = 0;
                                        foreach ($rating as $code => $rating_data) {
                                        	if($dep->key == $code){
	                                    		if(!empty($rating_data)){
		                                        	$rating_info = json_decode($rating_data);
		                                            $final_result = number_format($rating_info->final_result, 2)/$mainbankss->number;
		                                        }
	                                    	}
                                        }
                                    	if($new_rating['data_monthyear'][0] === trans('app.shortmonth'.intval($rating->month))." ".$rating->year){
                                              $new_rating['final_result'][0] = $final_result;
                                        }
                                        array_push($output, $new_rating);
                                    }
                                }else{
                                	foreach ($sub_departments as $dep) {
                                    	$new_rating = array(
                                            'data_id' => $dep->key,
                                            'data_name' => $dep->name,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'final_result' => $final_array
                                        );
                                        $final_result = 0;
                                        foreach ($rating as $code => $rating_data){
                                        	if($dep->key == $code){
	                                    		if(!empty($rating_data)){
		                                        	$rating_info = json_decode($rating_data);
		                                            $final_result = number_format($rating_info->final_result);
		                                        }
	                                    	}
                                        }
                                    	if($new_rating['data_monthyear'][0] === trans('app.shortmonth'.intval($rating->month))." ".$rating->year){
                                              $new_rating['final_result'][0] = $final_result;
                                        }
                                        array_push($output, $new_rating);
                                    }
                                }
                            }
                    	}
		            }elseif($department_view){
                        //echo "dep";
                        $all_departments = DB::table('departments')->get()->toArray();
		            	foreach ($ratings as $rating) {
            				if(!empty($output)){
                                $i = 0;
                            	foreach ($output as $output_data) {
                                    foreach ($rating as $code => $rating_data){
                                        if($output_data['data_id'] == $code){
                                        	$percent = 0;
                                        	$final_result = 0;
                                        	if(!empty($rating_data)){
                                                if(!empty($mainbank) && $mainbank != 'all'){
                                                    $rating_info = json_decode($rating_data);
                                                    if($code != 'i_others'){
                                                        $percent = number_format($rating_info->percent, 2)/$output_data['number'];
                                                    }
                                                    $final_result =  number_format($rating_info->final_result, 2)/$output_data['number'];
                                                }else{
                                                    $rating_info = json_decode($rating_data);
                                                    if($code != 'i_others'){
                                                        $percent = number_format($rating_info->percent, 2);
                                                    }
                                                    $final_result =  number_format($rating_info->final_result, 2);
                                                }
                                        		
                                        	}
                                        	if(in_array(trans('app.shortmonth'.$rating->month)." ".$rating->year, $output_data['data_monthyear'])){
                                                foreach ($output_data['data_monthyear'] as $count => $info) {
                                                    if($rating->month == $output_data['month'][$count] && $rating->year == $output_data['year'][$count]){
                                                        $output[$i]['percent'][$count] = $output[$i]['percent'][$count] + $percent;
                                                        $output[$i]['final_result'][$count] = $output[$i]['final_result'][$count] + $final_result;
                                                    }
                                                }
                                                
                                            }
                                        }
                                    }
                                    $i++;
                                }
                            }else{
                                $month_array = array();
                                $year_array = array();
                                $month_name_array = array();
                                $percent_array = array();
                                $final_array = array();
                                if($startyear != $endyear){
                                    for($b = $startyear; $b <= $endyear; $b++){
                                        if($b != $endyear){
                                            for($g = 1; $g <= 12; $g++){
                                                array_push($month_array, $g);
                                                array_push($year_array, $b);
                                                array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                array_push($percent_array, 0);
                                                array_push($final_array, 0);
                                            }
                                        }else{
                                            for($g = 1; $g <= intval($endmonth); $g++){
                                                array_push($month_array, $g);
                                                array_push($year_array, $b);
                                                array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                array_push($percent_array, 0);
                                                array_push($final_array, 0);
                                            }
                                        }
                                    }
                                }else{
                                    for($k = intval($startmonth); $k <= $endmonth; $k++){
                                        array_push($month_array, $k);
                                        array_push($year_array, $endyear);
                                        array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                        array_push($percent_array, 0);
                                        array_push($final_array, 0);
                                    }
                                }
                                if(!empty($mainbank) && $mainbank != 'all'){
                                    $position = get_position($user);
                                    $mainbankss = DB::table('banks')->
                                    select(
                                        DB::raw('count(banks.id) as number'),
                                        'banks.mainbank_id',
                                        'mainbanks.name'
                                    )->
                                    join('mainbanks', 'mainbanks.id', '=', 'banks.mainbank_id');
                                    if($position == 'admin' || $position == 'country'){
                                        $mainbankss = $mainbankss->where('banks.region_work_id', '=', $region_id);
                                    }else{
                                        $mainbankss = $mainbankss->where('banks.region_work_id', '=', $user->region_id);
                                    }
                                    $mainbankss = $mainbankss->where('banks.mainbank_id', '=', $mainbank->id)->groupBy('banks.mainbank_id', 'mainbanks.name')->get()->first();
                                    foreach ($all_departments as $dep) {
                                        $new_rating = array(
                                            'data_id' => $dep->key,
                                            'data_name' => $dep->name,
                                            'number' => $mainbankss->number,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'percent' => $percent_array,
                                            'final_result' => $final_array
                                        );
                                        $percent = 0;
                                        $final_result = 0;
                                        foreach ($rating as $code => $rating_data){
                                            if($dep->key == $code){
                                                if(!empty($rating_data)){
                                                    $rating_info = json_decode($rating_data);
                                                    $percent = number_format($rating_info->percent)/$mainbankss->number;
                                                    $final_result = number_format($rating_info->final_result)/$mainbankss->number;
                                                }
                                            }
                                        }
                                        if($new_rating['data_monthyear'][0] === trans('app.shortmonth'.intval($rating->month))." ".$rating->year){
                                              $new_rating['percent'][0] = $percent;
                                              $new_rating['final_result'][0] = $final_result;
                                        }
                                        array_push($output, $new_rating);
                                    }
                                }else{
                                    $departments = DB::table('departments')->get()->toArray();
                                    foreach ($departments as $dep) {
                                        $new_rating = array(
                                            'data_id' => $dep->key,
                                            'data_name' => $dep->name,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'percent' => $percent_array,
                                            'final_result' => $final_array
                                        );
                                        $percent = 0;
                                        $final_result = 0;
                                        foreach ($rating as $code => $rating_data){
                                            if($dep->key == $code){
                                                if(!empty($rating_data)){
                                                    $rating_info = json_decode($rating_data);
                                                    $percent = number_format($rating_info->percent);
                                                    $final_result = number_format($rating_info->final_result);
                                                }
                                            }
                                        }
                                        if($new_rating['data_monthyear'][0] === trans('app.shortmonth'.intval($rating->month))." ".$rating->year){
                                              $new_rating['percent'][0] = $percent;
                                              $new_rating['final_result'][0] = $final_result;
                                        }
                                        array_push($output, $new_rating);
                                    }
                                }
                            	
                            }
                    	}
		            }elseif($mainbank_view && !$subdepartment_view && !$department_view && !$fillial_view){
                        //echo "mainbank";
                        if($rating_type != 'all' && !empty($rating_type) && $rating_type != 'monthly'){
                            $rating_type = DB::table('departments')->where('id', '=', $rating_type)->get()->first();
                        }
                        if(!empty($sub_department) && $sub_department != 'all'){
                            $rating_type = DB::table('sub_departments')->where('id', '=', $sub_department)->get()->first();
                        }
                        if($rating_type == 'monthly'){
                            $deparmtents_all = DB::table('departments')->get()->toArray();
                            foreach ($ratings as $rating){
                                if(!empty($output)){
                                    $i = 0;
                                    foreach ($output as $output_data) {
                                        if($output_data['data_id'] == generateMfo($rating->mainbank_id)){
                                            $percent = 0;
                                            $final_result = 0;
                                            if(!empty($rating)){
                                                $final_result = $final_result + number_format($rating->rate)/$output_data['number'];
                                            }
                                            if(in_array(trans('app.shortmonth'.$rating->month)." ".$rating->year, $output_data['data_monthyear'])){
                                                foreach ($output_data['data_monthyear'] as $count => $info) {
                                                    if($rating->month == $output_data['month'][$count] && $rating->year == $output_data['year'][$count]){
                                                        $output[$i]['final_result'][$count] = $output[$i]['final_result'][$count] + $final_result;
                                                    }
                                                }
                                                
                                            }
                                        }
                                        $i++;
                                    }
                                }else{
                                    $position = get_position($user);
                                    $mainbankss = DB::table('banks')->
                                    select(
                                        DB::raw('count(banks.id) as number'),
                                        'banks.mainbank_id',
                                        'mainbanks.name'
                                    )->
                                    join('mainbanks', 'mainbanks.id', '=', 'banks.mainbank_id');
                                    if($position == 'admin' || $position == 'country'){
                                        $mainbankss = $mainbankss->where('banks.region_work_id', '=', $region_id);
                                    }else{
                                        $mainbankss = $mainbankss->where('banks.region_work_id', '=', $user->region_id);
                                    }
                                    $mainbankss = $mainbankss->where('mainbanks.id', '!=', 38)->
                                    groupBy('banks.mainbank_id', 'mainbanks.name')->get()->toArray();

                                    $month_array = array();
                                    $year_array = array();
                                    $month_name_array = array();
                                    $final_array = array();
                                    if($startyear != $endyear){
                                        for($b = $startyear; $b <= $endyear; $b++){
                                            if($b != $endyear){
                                                for($g = 1; $g <= 12; $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($final_array, 0);
                                                }
                                            }else{
                                                for($g = 1; $g <= intval($endmonth); $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($final_array, 0);
                                                }
                                            }
                                        }
                                    }else{
                                        for($k = intval($startmonth); $k <= $endmonth; $k++){
                                            array_push($month_array, $k);
                                            array_push($year_array, $endyear);
                                            array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                            array_push($final_array, 0);
                                        }
                                    }
                                    foreach ($mainbankss as $mbank) {
                                        $new_rating = array(
                                            'data_id' => $mbank->mainbank_id,
                                            'data_name' => $mbank->name,
                                            'number' => $mbank->number,        
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'final_result' => $final_array
                                        );
                                        $final_result = 0;
                                        if($rating->mainbank_id == $mbank->mainbank_id){
                                            if(!empty($rating)){
                                                $final_result = $final_result + number_format($rating->rate)/$mbank->number;
                                            }
                                            
                                        }
                                        if($new_rating['data_monthyear'][0] === trans('app.shortmonth'.intval($rating->month))." ".$rating->year){
                                              $new_rating['final_result'][0] = $final_result;
                                        }
                                        array_push($output, $new_rating);
                                    }
                                }
                            } 
                        }else{
                            foreach ($ratings as $rating) {
                                if(!empty($output)){
                                    $i = 0;
                                    foreach ($output as $output_data) {
                                        if($output_data['data_id'] == generateMfo($rating->mainbank_id)){
                                            $final_result = 0;
                                            foreach ($rating as $code => $rating_data) {
                                                if($rating_type->key == $code){
                                                    if(!empty($rating_data)){
                                                        $rating_info = json_decode($rating_data);
                                                        $final_result =  number_format($rating_info->final_result, 2)/$output_data['number'];
                                                    }
                                                    if(in_array(trans('app.shortmonth'.$rating->month)." ".$rating->year, $output_data['data_monthyear'])){
                                                        foreach ($output_data['data_monthyear'] as $count => $info) {
                                                            if($rating->month == $output_data['month'][$count] && $rating->year == $output_data['year'][$count]){
                                                                $output[$i]['final_result'][$count] = $output[$i]['final_result'][$count] + $final_result;
                                                            }
                                                        }
                                                        
                                                    }
                                                }
                                            }
                                        }
                                        $i++;
                                    }
                                }else{
                                    $position = get_position($user);
                                    $mainbankss = DB::table('banks')->
                                    select(
                                        DB::raw('count(banks.id) as number'),
                                        'banks.mainbank_id',
                                        'mainbanks.name'
                                    )->
                                    join('mainbanks', 'mainbanks.id', '=', 'banks.mainbank_id');
                                    if($position == 'admin' || $position == 'country'){
                                        $mainbankss = $mainbankss->where('banks.region_work_id', '=', $region_id);
                                    }else{
                                        $mainbankss = $mainbankss->where('banks.region_work_id', '=', $user->region_id);
                                    }
                                    $mainbankss = $mainbankss->where('mainbanks.id', '!=', 38)->
                                    groupBy('banks.mainbank_id', 'mainbanks.name')->get()->toArray();

                                    $month_array = array();
                                    $year_array = array();
                                    $month_name_array = array();
                                    $percent_array = array();
                                    $final_array = array();
                                    if($startyear != $endyear){
                                        for($b = $startyear; $b <= $endyear; $b++){
                                            if($b != $endyear){
                                                for($g = 1; $g <= 12; $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($percent_array, 0);
                                                    array_push($final_array, 0);
                                                }
                                            }else{
                                                for($g = 1; $g <= intval($endmonth); $g++){
                                                    array_push($month_array, $g);
                                                    array_push($year_array, $b);
                                                    array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                    array_push($percent_array, 0);
                                                    array_push($final_array, 0);
                                                }
                                            }
                                        }
                                    }else{
                                        for($k = intval($startmonth); $k <= $endmonth; $k++){
                                            array_push($month_array, $k);
                                            array_push($year_array, $endyear);
                                            array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                            array_push($final_array, 0);
                                        }
                                    }
                                    foreach ($mainbankss as $mbank) {
                                        $new_rating = array(
                                            'data_id' => $mbank->mainbank_id,
                                            'data_name' => $mbank->name,
                                            'number' => $mbank->number,        
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'final_result' => $final_array
                                        );
                                        $percent = 0;
                                        $final_result = 0;
                                        
                                        if($rating->mainbank_id == $mbank->mainbank_id){
                                            foreach ($rating as $code => $rating_data) {
                                                if($rating_type->key == $code){
                                                    if(!empty($rating_data)){
                                                        $rating_info = json_decode($rating_data);
                                                        $final_result = number_format($rating_info->final_result)/$mbank->number;
                                                    }
                                                }
                                            }
                                        }
                                        if($new_rating['data_monthyear'][0] === trans('app.shortmonth'.intval($rating->month))." ".$rating->year){
                                              $new_rating['final_result'][0] = $final_result;
                                        }
                                        array_push($output, $new_rating);
                                    }
                                }
                            }
                        }
                        
                    }
                }
            }
            if(!empty($region) && $region != 'all'){
                $region = DB::table('regions')->where('id', '=', $region)->get()->first();
            }
            if(!empty($city) && $city != 'all'){
                $city = DB::table('cities')->where('id', '=', $city)->get()->first();
            }
            $place_title = null;
            $bank_title = null;
            $head_title = null;
            if(!empty($region) && $region != 'all' && (empty($city) || $city == 'all') ){
                $place_title = $region->name;
            }
            if(!empty($city) && $city != 'all'){
                $place_title = $city->name;
            }
            if(!empty($mainbank) && $mainbank != 'all' && (empty($bank) || $bank == 'all')){
                $bank_title = $mainbank->name;
            }
            if(!empty($bank) && $bank != 'all'){
                $bank_title = $bank->name." [".generateMfo($bank->mfo_id)."]";
            }
            if((!empty($request->get('ratingtype')) && $request->get('ratingtype') != 'monthly' && $request->get('ratingtype') != 'all') && (empty($request->get('sub_department')) || $request->get('sub_department') == 'all')){
                $rating_type = DB::table('departments')->where('id', '=', $request->get('ratingtype'))->get()->first();
                $head_title = $rating_type->name;
            }
            if($request->get('ratingtype') == 'monthly'){
                $head_title = trans('app.monthly rating');
            }
            if(!empty($sub_department) && $sub_department != 'all'){
                $sub_department = DB::table('sub_departments')->where('id', '=', $sub_department)->get()->first();
                $head_title = $sub_department->name;
            }
            if(($mainbank == 'all' && $bank == 'all') || (empty($mainbank) && empty($bank)) || (empty($mainbank) && $bank == 'all') || ($mainbank == 'all' && empty($bank))){
                $bank_title = trans('app.all banks');
            }
            if(empty($place_title)){
                $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first();
                $place_title = $region->name;
            }
            //print_r($output);
            $colors = getColors();
            $info = array(
            	'fillial_view' => $fillial_view,
                'chart' => $output,
                'head_title' => $head_title,
                'place_title' => $place_title,
                'bank_title' => $bank_title,
                'time_title' => trans('app.shortmonth'.intval($startmonth))." ".$startyear." - ".trans('app.shortmonth'.intval($endmonth))." ".$endyear,
                'colors' => $colors
            );
            $data = json_encode($info);
            echo $data;
        }else{
            return view('chart.ratingchart', compact('title', 'regions', 'cities', 'mainbanks', 'rating_types'));
        }
    }

    public function loan_pie_credit(Request $request){
        $title = trans('app.loan credit report');
        $user = Auth::user();
        $position = get_position($user);
        $regions = DB::table('regions');
        $cities = DB::table('cities');
        $mainbanks = DB::table('mainbanks');
        if($position != 'admin' || $position != 'country'){
            $regions = $regions->where('id', '=', $user->region_id);
            $cities = $cities->where('region_id', '=', $user->region_id);
            $banks = DB::table('banks')->
            select('mainbank_id')->where('banks.region_work_id', '=', $user->region_id)
            ->groupBy('mainbank_id')->get()->toArray();
            $mainbanks = $mainbanks->where(function($query) use($banks){
                foreach ($banks as $bank) {
                    $query->orWhere('id', '=', $bank->mainbank_id);
                }
            });
        } 
        $regions = $regions->orderBy('name')->get()->toArray();
        $cities = $cities->orderBy('name')->get()->toArray();
        $mainbanks = $mainbanks->where('id', '!=', 38)->orderBy('name')->get()->toArray();
        $activities = DB::table('activity_codes')->get()->toArray();
        $goal_codes = DB::table('goal_codes')->get()->toArray();
        if($request->post()){
            $bank = $request->get('fillial');
            if($position != 'admin' || $position != 'country'){
                $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first()->id;
            }else{
                $region = $request->get('region');
            }
            $city = $request->get('city');
            $mainbank = $request->get('mainbank');
            $goal_code = $request->get('goal_code');
            $activity_code = $request->get('activity_code');
            $monthyear = date('d-m-Y', strtotime($request->get('monthyear')));
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            if(!empty($bank) && $bank != 'all'){
                $bank = DB::table('banks')->where('id', '=', $bank)->get()->first();
            }
            if(!empty($mainbank)  && $mainbank != 'all'){
                $mainbank = DB::table('mainbanks')->where('id', '=', $mainbank)->get()->first();
            }
            if((!empty($mainbank)  && $mainbank != 'all') || (!empty($city) && $city != 'all')){
                $fillials = DB::table('banks');
                if(!empty($region) && $region != 'all'){
                    $fillials = $fillials->where('region_work_id', '=', $region);
                }
                if(!empty($city) && $city != 'all'){
                    $fillials = $fillials->where('city_id', '=', $city);
                }
                if(!empty($mainbank)  && $mainbank != 'all'){
                    $fillials = $fillials->where('mainbank_id', '=', $mainbank->id);
                }
                if(!empty($bank) && $bank != 'all'){
                    $fillials = $fillials->where('id', '=', $bank->id);
                }
                $fillials = $fillials->get()->toArray();
            }
            $output = array();
            $table = 'portfolio_'.$year;
            $credits = DB::table($table)->
            select(
                $table.'.*',
                'banks.name',
                'banks.mainbank_id'
            )->
            join('banks', 'banks.id', '=', $table.'.bank_id')->
            where([[$table.'.month', '=', $month], [$table.'.year', '=', $year]]);
            if(!empty($bank)  && $bank != 'all'){
                $credits = $credits->where($table.'.bank_id', '=', $bank->id);
            }
            if(!empty($mainbank) && $mainbank != 'all'){
                $credits = $credits->where(function($query) use($mainbank){
                    $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                });
            }
            if(!empty($region) && $region != 'all'){
                $credits = $credits->where('banks.region_work_id', '=', $region);
            }
            if(!empty($city) &&  $city != 'all'){
                $credits = $credits->where('banks.city_id', '=', $city);
            }
            if(!empty($activity_code) && $activity_code != "all"){
                $credits = $credits->where($table.'.activity_code', '=', intval($activity_code));
            }
            if(!empty($goal_code) && $goal_code != "all"){
                $credits = $credits->where($table.'.goal_code', '=', intval($goal_code));
            }
            $credits = $credits->get()->toArray();
            if(!empty($credits)){
                if((empty($city) || $city == 'all') && (empty($mainbank) || $mainbank == 'all')){
                    $mainbank_view = true;
                }elseif(!empty($city) && $city != 'all' && $mainbank == 'all'){
                    $mainbank_view = false;
                }else{
                    $mainbank_view = false;
                }

                if( ( (!empty($mainbank) && $mainbank != 'all') || (!empty($city) && $city != 'all') ) && (empty($bank) || $bank =='all') || (!empty($bank) && $bank != 'all')){
                    $fillial_view = true;
                }else{
                    $fillial_view = false;
                }
                if($fillial_view){
                    foreach ($fillials as $fillial) {
                        $newarr = array(
                            'data_id' => generateMfo($fillial->mfo_id),
                            'data_name' => $fillial->name,
                            'data_credit' => 0,
                        );
                        array_push($output, $newarr);
                    }
                    foreach ($credits as $credit) {
                        $g = 0;
                        foreach ($output as  $data) {
                            if($data['data_id'] == generateMfo($credit->mfo_id)){
                                $json_data = json_decode($credit->portfolio);
                                $json_data->remainder = $json_data->remainder?$json_data->remainder:0;
                                $output[$g]['data_credit'] = $data['data_credit'] + $json_data->remainder;
                            }
                            $g++;
                        }
                    }
                }elseif($mainbank_view){
                    foreach ($mainbanks as $mainbank) {
                        $newarr = array(
                            'data_id' => $mainbank->id,
                            'data_name' => $mainbank->name,
                            'data_credit' => 0,
                        );
                        array_push($output, $newarr);
                    }
                    foreach ($credits as $credit) {
                        $g = 0;
                        foreach ($output as  $data) {
                            if($data['data_id'] == $credit->mainbank_id){
                                $json_data = json_decode($credit->portfolio);
                                $json_data->remainder = $json_data->remainder?$json_data->remainder:0;
                                $output[$g]['data_credit'] = $data['data_credit'] + $json_data->remainder;
                            }
                            $g++;
                        }
                    }
                }


                if(!empty($region) && $region != 'all'){
                    $region = DB::table('regions')->where('id', '=', $region)->get()->first();
                }
                if(!empty($city) && $city != 'all'){
                    $city = DB::table('cities')->where('id', '=', $city)->get()->first();
                }
                $place_title = null;
                $bank_title = null;
                $head_title = null;
                if(!empty($region) && $region != 'all' && (empty($city) || $city == 'all') ){
                    $place_title = $region->name;
                }
                if(!empty($city) && $city != 'all'){
                    $place_title = $city->name;
                }
                if(!empty($request->get('mainbank')) && $request->get('mainbank') != 'all' && (empty($request->get('fillial')) || $request->get('fillial') == 'all')){
                    $bank_title = DB::table('mainbanks')->where('id', '=', $request->get('mainbank'))->get()->first()->name;
                }else{
                    $bank_title = trans('app.all banks');
                }
                if(!empty($request->get('fillial')) && $request->get('fillial') != 'all'){
                    $bank_title = $bank->name." [".generateMfo($bank->mfo_id)."]";
                }
                if(($mainbank == 'all' && $bank == 'all') || (empty($mainbank) && empty($bank)) || (empty($mainbank) && $bank == 'all') || ($mainbank == 'all' && empty($bank))){
                    $bank_title = trans('app.all banks');
                }
                if(!empty($request->get('activity_code')) && $request->get('activity_code') != 'all'){
                    $head_title = DB::table('activity_codes')->where('code', '=', $request->get('activity_code'))->get()->first()->name;
                }else{
                    $head_title = trans('app.general');
                }
                if(!empty($request->get('goal_code')) && $request->get('goal_code') != 'all'){
                    $head_title = DB::table('goal_codes')->where('code', '=', $request->get('goal_code'))->get()->first()->name;
                }
                if(empty($place_title)){
                    $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first();
                    $place_title = $region->name;
                }
                //print_r($credits);
                $colors = getColors();
                $info = array(
                    'fillial_view' => $fillial_view,
                    'chart' => $output,
                    'head_title' => $head_title,
                    'place_title' => $place_title,
                    'bank_title' => $bank_title,
                    'time_title' => trans('app.shortmonth'.intval($month))."  ".$year,
                    'colors' => $colors
                );
                $data = json_encode($info);
                echo $data;
            }else{
                $place_title = null;
                $bank_title = null;
                $head_title = null;
                if(!empty($request->get('region')) && $request->get('region') != 'all' && (empty($request->get('city')) || $request->get('city') == 'all') ){
                    $place_title = $region->name;
                }
                if(!empty($request->get('city')) && $request->get('city') != 'all'){
                    $city = DB::table('cities')->where('id', '=', $request->get('city'))->get()->first();
                    $place_title = $city->name;
                }
                if(!empty($request->get('mainbank')) && $request->get('mainbank') != 'all' && (empty($request->get('fillial')) || $request->get('fillial') == 'all')){
                    $bank_title = DB::table('mainbanks')->where('id', '=', $request->get('mainbank'))->get()->first()->name;
                }else{
                    $bank_title = trans('app.all banks');
                }
                if(!empty($request->get('fillial')) && $request->get('fillial') != 'all'){
                    $bank_title = $bank->name." [".generateMfo($bank->mfo_id)."]";
                }
                if(($mainbank == 'all' && $bank == 'all') || (empty($mainbank) && empty($bank)) || (empty($mainbank) && $bank == 'all') || ($mainbank == 'all' && empty($bank))){
                    $bank_title = trans('app.all banks');
                }
                if(!empty($request->get('activity_code')) && $request->get('activity_code') != 'all'){
                    $head_title = DB::table('activity_codes')->where('code', '=', $request->get('activity_code'))->get()->first()->name;
                }else{
                    $head_title = trans('app.general');
                }
                if(!empty($request->get('goal_code')) && $request->get('goal_code') != 'all'){
                    $head_title = DB::table('goal_codes')->where('code', '=', $request->get('goal_code'))->get()->first()->name;
                }
                if(empty($place_title)){
                    $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first();
                    $place_title = $region->name;
                }
                $info = array(
                    'data' => "empty",
                    'head_title' => $head_title,
                    'place_title' => $place_title,
                    'bank_title' => $bank_title,
                    'time_title' => trans('app.shortmonth'.intval($month))."  ".$year
                );
                echo json_encode($info);
            }


            
        }else{
            return view('chart.loanpiecredit', compact('title', 'regions', 'cities', 'mainbanks', 'activities', 'goal_codes'));
        }
    }

    public function loan_pie_problem(Request $request){
        $title = trans('app.loan problem credit report');
        $user = Auth::user();
        $position = get_position($user);
        $regions = DB::table('regions');
        $cities = DB::table('cities');
        $mainbanks = DB::table('mainbanks');
        if($position != 'admin' || $position != 'country'){
            $regions = $regions->where('id', '=', $user->region_id);
            $cities = $cities->where('region_id', '=', $user->region_id);
            $banks = DB::table('banks')->
            select('mainbank_id')->where('banks.region_work_id', '=', $user->region_id)
            ->groupBy('mainbank_id')->get()->toArray();
            $mainbanks = $mainbanks->where(function($query) use($banks){
                foreach ($banks as $bank) {
                    $query->orWhere('id', '=', $bank->mainbank_id);
                }
            });
        } 
        $regions = $regions->orderBy('name')->get()->toArray();
        $cities = $cities->orderBy('name')->get()->toArray();
        $mainbanks = $mainbanks->orderBy('name')->get()->toArray();
        $activities = DB::table('activity_codes')->get()->toArray();
        $goal_codes = DB::table('goal_codes')->get()->toArray();
        if($request->post()){
            $bank = $request->get('fillial');
            if($position != 'admin' || $position != 'country'){
                $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first()->id;
            }else{
                $region = $request->get('region');
            }
            $city = $request->get('city');
            $mainbank = $request->get('mainbank');
            $goal_code = $request->get('goal_code');
            $activity_code = $request->get('activity_code');
            $monthyear = date('d-m-Y', strtotime($request->get('monthyear')));
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            if(!empty($bank) && $bank != 'all'){
                $bank = DB::table('banks')->where('id', '=', $bank)->get()->first();
            }
            if(!empty($mainbank)  && $mainbank != 'all'){
                $mainbank = DB::table('mainbanks')->where('id', '=', $mainbank)->get()->first();
            }
            if((!empty($mainbank)  && $mainbank != 'all') || (!empty($city) && $city != 'all')){
                $fillials = DB::table('banks');
                if(!empty($region) && $region != 'all'){
                    $fillials = $fillials->where('region_work_id', '=', $region);
                }
                if(!empty($city) && $city != 'all'){
                    $fillials = $fillials->where('city_id', '=', $city);
                }
                if(!empty($mainbank)  && $mainbank != 'all'){
                    $fillials = $fillials->where('mainbank_id', '=', $mainbank->id);
                }
                if(!empty($bank) && $bank != 'all'){
                    $fillials = $fillials->where('id', '=', $bank->id);
                }
                $fillials = $fillials->get()->toArray();
            }
            $output = array();
            $table = 'portfolio_'.$year;
            $credits = DB::table($table)->
            select(
                $table.'.*',
                'banks.name',
                'banks.mainbank_id'
            )->
            join('banks', 'banks.id', '=', $table.'.bank_id')->
            where([[$table.'.month', '=', $month], [$table.'.year', '=', $year]]);
            if(!empty($bank)  && $bank != 'all'){
                $credits = $credits->where($table.'.bank_id', '=', $bank->id);
            }
            if(!empty($mainbank) && $mainbank != 'all'){
                $credits = $credits->where(function($query) use($mainbank){
                    $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                });
            }
            if(!empty($region) && $region != 'all'){
                $credits = $credits->where('banks.region_work_id', '=', $region);
            }
            if(!empty($city) &&  $city != 'all'){
                $credits = $credits->where('banks.city_id', '=', $city);
            }
            if(!empty($activity_code) && $activity_code != 'all'){
                $credits = $credits->where($table.'.activity_code', '=', intval($activity_code));
            }
            if(!empty($goal_code) && $goal_code != 'all'){
                $credits = $credits->where($table.'.goal_code', '=', intval($goal_code));
            }
            $credits = $credits->where($table.'.status', '=', 'problem')->get()->toArray();
            if(!empty($credits)){
                if((empty($city) || $city == 'all') && (empty($mainbank) || $mainbank == 'all')){
                    $mainbank_view = true;
                }elseif(!empty($city) && $city != 'all' && $mainbank == 'all'){
                    $mainbank_view = false;
                }else{
                    $mainbank_view = false;
                }

                if( ( (!empty($mainbank) && $mainbank != 'all') || (!empty($city) && $city != 'all') ) && (empty($bank) || $bank =='all') || (!empty($bank) && $bank != 'all')){
                    $fillial_view = true;
                }else{
                    $fillial_view = false;
                }
                if($fillial_view){
                    
                    foreach ($fillials as $fillial) {
                        $newarr = array(
                            'data_id' => generateMfo($fillial->mfo_id),
                            'data_name' => $fillial->name,
                            'data_problem_credit' => 0,
                        );
                        array_push($output, $newarr);
                    }
                    foreach ($credits as $credit) {
                        $g = 0;
                        foreach ($output as  $data) {
                            if($data['data_id'] == generateMfo($credit->mfo_id)){
                                $json_data = json_decode($credit->portfolio);
                                $json_data->out_of = $json_data->out_of?$json_data->out_of:0;
                                $output[$g]['data_problem_credit'] = $data['data_problem_credit'] + $json_data->out_of;
                            }
                            $g++;
                        }
                    }
                }elseif($mainbank_view){
                    
                    foreach ($mainbanks as $mainbank) {
                        $newarr = array(
                            'data_id' => $mainbank->id,
                            'data_name' => $mainbank->name,
                            'data_problem_credit' => 0,
                        );
                        array_push($output, $newarr);
                    }
                    foreach ($credits as $credit) {
                        $g = 0;
                        foreach ($output as  $data) {
                            if($data['data_id'] == $credit->mainbank_id){
                                $json_data = json_decode($credit->portfolio);
                                $json_data->out_of = $json_data->out_of?$json_data->out_of:0;
                                $output[$g]['data_problem_credit'] = $data['data_problem_credit'] + $json_data->out_of;
                            }
                            $g++;
                        }
                    }
                }


                if(!empty($region) && $region != 'all'){
                    $region = DB::table('regions')->where('id', '=', $region)->get()->first();
                }
                if(!empty($city) && $city != 'all'){
                    $city = DB::table('cities')->where('id', '=', $city)->get()->first();
                }
                $place_title = null;
                $bank_title = null;
                $head_title = null;
                if(!empty($region) && $region != 'all' && (empty($city) || $city == 'all') ){
                    $place_title = $region->name;
                }
                if(!empty($city) && $city != 'all'){
                    $place_title = $city->name;
                }
                if(!empty($request->get('mainbank')) && $request->get('mainbank') != 'all' && (empty($request->get('fillial')) || $request->get('fillial') == 'all')){
                    $bank_title = DB::table('mainbanks')->where('id', '=', $request->get('mainbank'))->get()->first()->name;
                }else{
                    $bank_title = trans('app.all banks');
                }
                if(!empty($request->get('fillial')) && $request->get('fillial') != 'all'){
                    $bank_title = $bank->name." [".generateMfo($bank->mfo_id)."]";
                }
                if(!empty($request->get('activity_code')) && $request->get('activity_code') != 'all'){
                    $head_title = DB::table('activity_codes')->where('code', '=', $request->get('activity_code'))->get()->first()->name;
                }else{
                    $head_title = trans('app.general');
                }
                if(!empty($request->get('goal_code')) && $request->get('goal_code') != 'all'){
                    $head_title = DB::table('goal_codes')->where('code', '=', $request->get('goal_code'))->get()->first()->name;
                }
                if(($mainbank == 'all' && $bank == 'all') || (empty($mainbank) && empty($bank)) || (empty($mainbank) && $bank == 'all') || ($mainbank == 'all' && empty($bank))){
                    $bank_title = trans('app.all banks');
                }
                if(empty($place_title)){
                    $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first();
                    $place_title = $region->name;
                }
                //print_r($credits);
                $colors = getColors();
                $info = array(
                    'fillial_view' => $fillial_view,
                    'chart' => $output,
                    'head_title' => $head_title,
                    'place_title' => $place_title,
                    'bank_title' => $bank_title,
                    'time_title' => trans('app.shortmonth'.intval($month))."  ".$year,
                    'colors' => $colors
                );
                $data = json_encode($info);
                echo $data;
            }else{
                $place_title = null;
                $bank_title = null;
                $head_title = null;
                if(!empty($request->get('region')) && $request->get('region') != 'all' && (empty($request->get('city')) || $request->get('city') == 'all') ){
                    $place_title = $region->name;
                }
                if(!empty($request->get('city')) && $request->get('city') != 'all'){
                    $city = DB::table('cities')->where('id', '=', $request->get('city'))->get()->first();
                    $place_title = $city->name;
                }
                if(!empty($request->get('mainbank')) && $request->get('mainbank') != 'all' && (empty($request->get('fillial')) || $request->get('fillial') == 'all')){
                    $bank_title = DB::table('mainbanks')->where('id', '=', $request->get('mainbank'))->get()->first()->name;
                }else{
                    $bank_title = trans('app.all banks');
                }
                if(!empty($request->get('fillial')) && $request->get('fillial') != 'all'){
                    $bank_title = $bank->name." [".generateMfo($bank->mfo_id)."]";
                }
                if(($mainbank == 'all' && $bank == 'all') || (empty($mainbank) && empty($bank)) || (empty($mainbank) && $bank == 'all') || ($mainbank == 'all' && empty($bank))){
                    $bank_title = trans('app.all banks');
                }
                if(!empty($request->get('activity_code')) && $request->get('activity_code') != 'all'){
                    $head_title = DB::table('activity_codes')->where('code', '=', $request->get('activity_code'))->get()->first()->name;
                }else{
                    $head_title = trans('app.general');
                }
                if(!empty($request->get('goal_code')) && $request->get('goal_code') != 'all'){
                    $head_title = DB::table('goal_codes')->where('code', '=', $request->get('goal_code'))->get()->first()->name;
                }
                if(empty($place_title)){
                    $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first();
                    $place_title = $region->name;
                }
                $info = array(
                    'data' => "empty",
                    'head_title' => $head_title,
                    'place_title' => $place_title,
                    'bank_title' => $bank_title,
                    'time_title' => trans('app.shortmonth'.intval($month))."  ".$year
                );
                echo json_encode($info);
            }


            
        }else{
            return view('chart.loanpieproblem', compact('title', 'regions', 'cities', 'mainbanks', 'activities', 'goal_codes'));
        }
    }

    public function loan_line_portfolio(Request $request){
        $title = trans('app.loan portfolio analyse');
        $user = Auth::user();
        $position = get_position($user);
        $regions = DB::table('regions');
        $cities = DB::table('cities');
        $mainbanks = DB::table('mainbanks');
        if($position != 'admin' || $position != 'country'){
            $regions = $regions->where('id', '=', $user->region_id);
            $cities = $cities->where('region_id', '=', $user->region_id);
            $banks = DB::table('banks')->
            select('mainbank_id')->where('banks.region_work_id', '=', $user->region_id)
            ->groupBy('mainbank_id')->get()->toArray();
            $mainbanks = $mainbanks->where(function($query) use($banks){
                foreach ($banks as $bank) {
                    $query->orWhere('id', '=', $bank->mainbank_id);
                }
            });
        } 
        $regions = $regions->orderBy('name')->get()->toArray();
        $cities = $cities->orderBy('name')->get()->toArray();
        $mainbanks = $mainbanks->orderBy('name')->get()->toArray();
        $activities = DB::table('activity_codes')->get()->toArray();
        $goal_codes = DB::table('goal_codes')->get()->toArray();
        if($request->post()){
            $bank = $request->get('fillial');
            $region = $request->get('region');
            $city = $request->get('city');
            $mainbank = $request->get('mainbank');
            $startmonthyear = $request->get('startmonthyear');
            $endmonthyear = $request->get('endmonthyear');
            $activity_code = $request->get('activity_code');
            $goal_code = $request->get('goal_code');
            $startmonth = date('m', strtotime($startmonthyear));
            $startyear = date('Y', strtotime($startmonthyear));
            $endmonth = date('m', strtotime($endmonthyear));
            $endyear = date('Y', strtotime($endmonthyear));
            if(!empty($bank) && $bank != 'all'){
                $bank = DB::table('banks')->where('id', '=', $bank)->get()->first();
            }
            if(!empty($mainbank)  && $mainbank != 'all'){
                $mainbank = DB::table('mainbanks')->where('id', '=', $mainbank)->get()->first();
            }
            if(!empty($mainbank)  && $mainbank != 'all'){
                $fillials = DB::table('banks')->where('mainbank_id', '=', $mainbank->id);
                if(!empty($region) && $region != 'all'){
                    $fillials = $fillials->where('region_id', '=', $region);
                }
                $fillials = $fillials->get()->toArray();
            }
            $output = array();
            $table = 'balance_'.$endyear;
            if((empty($mainbank) || $mainbank == 'all') && ($city == 'all' || empty($city))){
            	$mainbank_view = true;
            }elseif(!empty($city) && $city != 'all' && $mainbank == 'all'){
            	$mainbank_view = false;
            }else{
            	$mainbank_view = false;
            }
            
            if($startyear != $endyear){
                for ($y=$startyear; $y <= $endyear; $y++) {
                    $data = null;
                    $balance = null;
                    $table = 'portfolio_'.$y;
                    $data = DB::table($table)->
                        select(
                            $table.'.*',
                            'banks.mfo_id',
                            'banks.name',
                            'banks.mainbank_id'
                        )->
                        join('banks', 'banks.id', '=', $table.'.bank_id');
                    if($y == $endyear){
                        $data = $data->where([[$table.'.month', '<=', $endmonth], [$table.'.year', '=', $endyear]]);
                    }elseif($y == $startyear){
                        $data = $data->where([[$table.'.month', '>=', $startmonth], [$table.'.year', '=', $startyear]]);
                    }
                    
                    if(!empty($bank)  && $bank != 'all'){
                        $data = $data->where($table.'.bank_id', '=', $bank->id);
                    }
                    if(!empty($mainbank) && $mainbank != 'all'){
                        $data = $data->where(function($query) use($mainbank){
                            $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                        });
                    }
                    if(!empty($region) && $region != 'all'){
                        $data = $data->where('banks.region_work_id', '=', $region);
                    }
                    if(!empty($city) &&  $city != 'all'){
                        $data = $data->where('banks.city_id', '=', $city);
                    }
                    if(!empty($goal_code) &&  $goal_code != 'all'){
                        $data = $data->where($table.'.goal_code', '=', $goal_code);
                    }
                    if(!empty($activity_code) &&  $activity_code != 'all'){
                        $data = $data->where($table.'.activity_code', '=', $activity_code);
                    }
                    $portfolio = $data->orderBy('month', 'ASC')->get()->toArray();
                    if(!empty($portfolio)){
                        foreach ($portfolio as $portfolio_data) {
                            if(!empty($output)){
                                $i = 0;
                                foreach ($output as $output_data) {
                                    $portfolio_sum = 0;
                                    $portfolio_info = json_decode($portfolio_data->portfolio);
                                    $portfolio_sum = intval($balance_info->remainder);
                                    if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $output_data['data_monthyear'])){
                                        foreach ($output_data['data_monthyear'] as $count => $info) {
                                            if($portfolio_data->month == $output_data['month'][$count] && $portfolio_data->year == $output_data['year'][$count]){
                                                $output[$i]['portfolio'][$count] = $output[$i]['portfolio'][$count] + $portfolio_sum;
                                            }
                                        }
                                    }
                                    $i++;
                                }
                            }else{
                                $month_array = array();
                                $year_array = array();
                                $month_name_array = array();
                                $portfolio_array = array();
                                if($startyear != $endyear){
                                    for($b = $startyear; $b <= $endyear; $b++){
                                        if($b != $endyear){
                                            for($g = 1; $g <= 12; $g++){
                                                array_push($month_array, $g);
                                                array_push($year_array, $b);
                                                array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                array_push($portfolio_array, 0);
                                            }
                                        }else{
                                            for($g = 1; $g <= intval($endmonth); $g++){
                                                array_push($month_array, $g);
                                                array_push($year_array, $b);
                                                array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                array_push($portfolio_array, 0);
                                            }
                                        }
                                    }
                                }else{
                                    for($k = intval($startmonth); $k <= $endmonth; $k++){
                                        array_push($month_array, $k);
                                        array_push($year_array, $endyear);
                                        array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                        array_push($portfolio_array, 0);
                                    }
                                }
                                if($mainbank_view){
                                    $position = get_position($user);
                                    $banks = DB::table('banks')->
                                    select('mainbank_id');
                                    if($position == 'admin' || $position == 'country'){
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }else{
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }
                                    if(!empty($city)  && $city != 'all'){
                                        $banks = $banks->where('banks.city_id', '=', $city);
                                    }
                                    $banks = $banks->groupBy('mainbank_id')->get()->toArray();
                                    $mainbankss = DB::table('mainbanks')->where(function($query) use($banks){
                                        foreach ($banks as $bank_default) {
                                            $query->orWhere('id', '=', $bank_default->mainbank_id);
                                        }
                                    })->orderBy('name')->get()->toArray();
                                    foreach ($mainbankss as $mbank) {
                                        $new_portfolio = array(
                                            'data_id' => $mbank->id,
                                            'data_name' => $mbank->name,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'portfolio' => $portfolio_array,
                                        );
                                        $portfolio_sum = 0;
                                        if($portfolio_data->mainbank_id == $mbank->id){
                                            $portfolio_info = json_decode($portfolio_data->portfolio);
                                            $portfolio_sum = $portfolio_sum + intval($portfolio_info->remainder);
                                            if($new_portfolio['data_monthyear'][0] === trans('app.shortmonth'.intval($portfolio_data->month))." ".$portfolio_data->year){
                                                $new_portfolio['portfolio'][0] = $portfolio_sum;
                                            }
                                        }
                                        array_push($output, $new_portfolio);
                                    }
                                }else{
                                    $banks = DB::table('banks');
                                    if($position == 'admin' || $position == 'country'){
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }else{
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }
                                    if(!empty($city)  && $city != 'all'){
                                        $banks = $banks->where('banks.city_id', '=', $city);
                                    }
                                    if(!empty($bank) && $bank != 'all'){
                                        $banks = $banks->where('banks.id', '=', $bank->id);
                                    }
                                    if(!empty($mainbank)  && $mainbank != 'all'){
                                        $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                    }
                                    $banks = $banks->get()->toArray();
                                    foreach ($banks as $bank_default) {
                                        $portfolio_sum = 0;
                                        $new_portfolio = array(
                                            'data_id' => generateMfo($bank_default->mfo_id),
                                            'data_name' => $bank_default->name,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'portfolio' => $portfolio_array,
                                        );
                                        if($portfolio_data->mfo_id == $bank_default->mfo_id){
                                            $portfolio_info = json_decode($portfolio_data->portfolio);
                                            $portfolio_sum = $portfolio_sum + intval($portfolio_info->remainder);
                                            if($new_portfolio['data_monthyear'][0] === trans('app.shortmonth'.intval($portfolio_data->month))." ".$portfolio_data->year){
                                                $new_portfolio['portfolio'][0] = $portfolio_sum;
                                            }
                                        }
                                        array_push($output, $new_portfolio);
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                $table = 'portfolio_'.$endyear;
                $data = DB::table($table)->
                    select(
                        $table.'.*',
                        'banks.mfo_id',
                        'banks.name',
                        'banks.mainbank_id'
                    )->
                join('banks', 'banks.id', '=', $table.'.bank_id')->
                where([[$table.'.month', '>=', $startmonth], [$table.'.year', '>=', $startyear], [$table.'.month', '<=', $endmonth], [$table.'.year', '<=', $endyear]]);
                if(!empty($bank)  && $bank != 'all'){
                    $data = $data->where($table.'.bank_id', '=', $bank->id);
                }
                if(!empty($mainbank) && $mainbank != 'all'){
                    $data = $data->where(function($query) use($mainbank){
                        $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                    });
                }
                if(!empty($region) && $region != 'all'){
                    $data = $data->where('banks.region_work_id', '=', $region);
                }
                if(!empty($city) &&  $city != 'all'){
                    $data = $data->where('banks.city_id', '=', $city);
                }
                if(!empty($goal_code) &&  $goal_code != 'all'){
                    $data = $data->where($table.'.goal_code', '=', $goal_code);
                }
                if(!empty($activity_code) &&  $activity_code != 'all'){
                    $data = $data->where($table.'.activity_code', '=', $activity_code);
                }
                $portfolio = $data->orderBy('month', 'ASC')->get()->toArray();
                if(!empty($portfolio)){
                    foreach ($portfolio as $portfolio_data) {
                        if(!empty($output)){
                            $i = 0;
                            foreach ($output as $output_data) {
                                if(!$mainbank_view && $output_data['data_id'] == generateMfo($portfolio_data->mfo_id)){
                                    $portfolio_sum = 0;
                                    $portfolio_info = json_decode($portfolio_data->portfolio);
                                    $portfolio_sum = intval($portfolio_info->remainder);
                                    if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $output_data['data_monthyear'])){
                                        foreach ($output_data['data_monthyear'] as $count => $info) {
                                            if($portfolio_data->month == $output_data['month'][$count] && $portfolio_data->year == $output_data['year'][$count]){
                                                $output[$i]['portfolio'][$count] = $output[$i]['portfolio'][$count] + $portfolio_sum;
                                            }
                                        }
                                    }
                                }elseif($mainbank_view && $output_data['data_id'] == $portfolio_data->mainbank_id){
                                    $portfolio_sum = 0;
                                    $portfolio_info = json_decode($portfolio_data->portfolio);
                                    $portfolio_sum = intval($portfolio_info->remainder);
                                    if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $output_data['data_monthyear'])){
                                        foreach ($output_data['data_monthyear'] as $count => $info) {
                                            if($portfolio_data->month == $output_data['month'][$count] && $portfolio_data->year == $output_data['year'][$count]){
                                                $output[$i]['portfolio'][$count] = $output[$i]['portfolio'][$count] + $portfolio_sum;
                                            }
                                        }
                                    }
                                }
                                
                                $i++;
                            }
                        }else{
                            $month_array = array();
                            $year_array = array();
                            $month_name_array = array();
                            $portfolio_array = array();
                            if($startyear != $endyear){
                                for($b = $startyear; $b <= $endyear; $b++){
                                    if($b != $endyear){
                                        for($g = 1; $g <= 12; $g++){
                                            array_push($month_array, $g);
                                            array_push($year_array, $b);
                                            array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                            array_push($portfolio_array, 0);
                                        }
                                    }else{
                                        for($g = 1; $g <= intval($endmonth); $g++){
                                            array_push($month_array, $g);
                                            array_push($year_array, $b);
                                            array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                            array_push($portfolio_array, 0);
                                        }
                                    }
                                }
                            }else{
                                for($k = intval($startmonth); $k <= $endmonth; $k++){
                                    array_push($month_array, $k);
                                    array_push($year_array, $endyear);
                                    array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                    array_push($portfolio_array, 0);
                                }
                            }
                            if($mainbank_view){
                                $position = get_position($user);
                                $banks = DB::table('banks')->
                                select('mainbank_id');
                                if($position == 'admin' || $position == 'country'){
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }else{
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }
                                if(!empty($city)  && $city != 'all'){
                                    $banks = $banks->where('banks.city_id', '=', $city);
                                }
                                $banks = $banks->groupBy('mainbank_id')->get()->toArray();
                                $mainbankss = DB::table('mainbanks')->where(function($query) use($banks){
                                    foreach ($banks as $bank_default) {
                                        $query->orWhere('id', '=', $bank_default->mainbank_id);
                                    }
                                })->orderBy('name')->get()->toArray();
                                foreach ($mainbankss as $mbank) {
                                    $new_portfolio = array(
                                        'data_id' => $mbank->id,
                                        'data_name' => $mbank->name,
                                        'data_monthyear' => $month_name_array,
                                        'month' => $month_array,
                                        'year' => $year_array,
                                        'portfolio' => $portfolio_array,
                                    );
                                    $portfolio_sum = 0;
                                    if($portfolio_data->mainbank_id == $mbank->id){
                                        $portfolio_info = json_decode($portfolio_data->portfolio);
                                        $portfolio_sum = $portfolio_sum + intval($portfolio_info->remainder);
                                        if($new_portfolio['data_monthyear'][0] === trans('app.shortmonth'.intval($portfolio_data->month))." ".$portfolio_data->year){
                                            $new_portfolio['portfolio'][0] = $portfolio_sum;
                                        }
                                    }
                                    array_push($output, $new_portfolio);
                                }
                            }else{
                                $banks = DB::table('banks');
                                if($position == 'admin' || $position == 'country'){
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }else{
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }
                                if(!empty($city)  && $city != 'all'){
                                    $banks = $banks->where('banks.city_id', '=', $city);
                                }
                                if(!empty($bank) && $bank != 'all'){
                                    $banks = $banks->where('banks.id', '=', $bank->id);
                                }
                                if(!empty($mainbank)  && $mainbank != 'all'){
                                    $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                }
                                $banks = $banks->get()->toArray();
                                foreach ($banks as $bank_default) {
                                    $portfolio_sum = 0;
                                    $new_portfolio = array(
                                        'data_id' => generateMfo($bank_default->mfo_id),
                                        'data_name' => $bank_default->name,
                                        'data_monthyear' => $month_name_array,
                                        'month' => $month_array,
                                        'year' => $year_array,
                                        'portfolio' => $portfolio_array,
                                    );
                                    if($portfolio_data->mfo_id == $bank_default->mfo_id){
                                        $portfolio_info = json_decode($portfolio_data->portfolio);
                                        $portfolio_sum = $portfolio_sum + intval($portfolio_info->remainder);
                                        if($new_portfolio['data_monthyear'][0] === trans('app.shortmonth'.intval($portfolio_data->month))." ".$portfolio_data->year){
                                            $new_portfolio['portfolio'][0] = $portfolio_sum;
                                        }
                                    }
                                    array_push($output, $new_portfolio);
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($region) && $region != 'all'){
                $region = DB::table('regions')->where('id', '=', $region)->get()->first();
            }
            if(!empty($city) && $city != 'all'){
                $city = DB::table('cities')->where('id', '=', $city)->get()->first();
            }
            $place_title = null;
            $bank_title = null;
            $head_title = null;
            if(!empty($region) && $region != 'all' && (empty($city) || $city == 'all') ){
                $place_title = $region->name;
            }
            if(!empty($city) && $city != 'all'){
                $place_title = $city->name;
            }
            if(!empty($mainbank) && $mainbank != 'all' && (empty($bank) || $bank == 'all')){
                $bank_title = $mainbank->name;
            }
            if(!empty($bank) && $bank != 'all'){
                $bank_title = $bank->name." [".generateMfo($bank->mfo_id)."]";
            }
            if(($mainbank == 'all' && $bank == 'all') || (empty($mainbank) && empty($bank)) || (empty($mainbank) && $bank == 'all') || ($mainbank == 'all' && empty($bank))){
                $bank_title = trans('app.all banks');
            }
            if(empty($place_title)){
                $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first();
                $place_title = $region->name;
            }
            if(!empty($request->get('activity_code')) && $request->get('activity_code') != 'all'){
                $head_title = DB::table('activity_codes')->where('code', '=', $request->get('activity_code'))->get()->first()->name;
            }else{
                $head_title = "";
            }
            if(!empty($request->get('goal_code')) && $request->get('goal_code') != 'all'){
                $head_title = DB::table('goal_codes')->where('code', '=', $request->get('goal_code'))->get()->first()->name;
            }
            $max = 0;
            foreach($output as $output_data){
                foreach($output_data['portfolio'] as $max_port){
                    if($max_port > $max){
                        $max = $max_port;
                    }
                }
            }
            $number_dividing = number_formatting($max);
            $text_number = $number_dividing->text." UZS";
            foreach($output as $count => $output_data){
                foreach($output_data['portfolio'] as $key => $max_port){
                    if($max_port > 0){
                        $output[$count]['portfolio'][$key] = $output[$count]['portfolio'][$key]/$number_dividing->dividing;
                    }
                }
            }

            //print_r($balance);
            $colors = getColors();
            $info = array(
                'chart' => $output,
                'head_title' => $head_title,
                'place_title' => $place_title,
                'bank_title' => $bank_title,
                'mainbank_view' => $mainbank_view,
                'dividing' => $number_dividing->dividing,
                'divide_text' => $text_number,
                'time_title' => trans('app.shortmonth'.intval($startmonth))." ".$startyear." - ".trans('app.shortmonth'.intval($endmonth))." ".$endyear,
                'colors' => $colors
            );
            $data = json_encode($info);
            echo $data;
            //return view('chart.linechart', compact('title', 'regions', 'mainbanks', 'cat_accounts', 'data', 'bank', 'cat_account', 'mainbank', 'monthyear', 'fillials', 'region', 'account_sheets', 'account_sheet'));
        }else{
            return view('chart.loanlineportfolio', compact('title', 'regions', 'mainbanks', 'cities', 'activities', 'goal_codes'));
        }
    }

    public function loan_line_problem(Request $request){
        $title = trans('app.problem loan portfolio');
        $user = Auth::user();
        $position = get_position($user);
        $regions = DB::table('regions');
        $cities = DB::table('cities');
        $mainbanks = DB::table('mainbanks');
        if($position != 'admin' || $position != 'country'){
            $regions = $regions->where('id', '=', $user->region_id);
            $cities = $cities->where('region_id', '=', $user->region_id);
            $banks = DB::table('banks')->
            select('mainbank_id')->where('banks.region_work_id', '=', $user->region_id)
            ->groupBy('mainbank_id')->get()->toArray();
            $mainbanks = $mainbanks->where(function($query) use($banks){
                foreach ($banks as $bank) {
                    $query->orWhere('id', '=', $bank->mainbank_id);
                }
            });
        } 
        $regions = $regions->orderBy('name')->get()->toArray();
        $cities = $cities->orderBy('name')->get()->toArray();
        $mainbanks = $mainbanks->orderBy('name')->get()->toArray();
        $activities = DB::table('activity_codes')->get()->toArray();
        $goal_codes = DB::table('goal_codes')->get()->toArray();
        if($request->post()){
            $bank = $request->get('fillial');
            $region = $request->get('region');
            $city = $request->get('city');
            $mainbank = $request->get('mainbank');
            $startmonthyear = $request->get('startmonthyear');
            $endmonthyear = $request->get('endmonthyear');
            $activity_code = $request->get('activity_code');
            $goal_code = $request->get('goal_code');
            $startmonth = date('m', strtotime($startmonthyear));
            $startyear = date('Y', strtotime($startmonthyear));
            $endmonth = date('m', strtotime($endmonthyear));
            $endyear = date('Y', strtotime($endmonthyear));
            if(!empty($bank) && $bank != 'all'){
                $bank = DB::table('banks')->where('id', '=', $bank)->get()->first();
            }
            if(!empty($mainbank)  && $mainbank != 'all'){
                $mainbank = DB::table('mainbanks')->where('id', '=', $mainbank)->get()->first();
            }
            if(!empty($mainbank)  && $mainbank != 'all'){
                $fillials = DB::table('banks')->where('mainbank_id', '=', $mainbank->id);
                if(!empty($region) && $region != 'all'){
                    $fillials = $fillials->where('region_id', '=', $region);
                }
                $fillials = $fillials->get()->toArray();
            }
            $output = array();
            $table = 'balance_'.$endyear;
            if((empty($mainbank) || $mainbank == 'all') && ($city == 'all' || empty($city))){
            	$mainbank_view = true;
            }elseif(!empty($city) && $city != 'all' && $mainbank == 'all'){
            	$mainbank_view = false;
            }else{
            	$mainbank_view = false;
            }
            
            if($startyear != $endyear){
                for ($y=$startyear; $y <= $endyear; $y++) {
                    $data = null;
                    $balance = null;
                    $table = 'portfolio_'.$endyear;
                    $data = DB::table($table)->
                        select(
                            $table.'.*',
                            'banks.mfo_id',
                            'banks.name',
                            'banks.mainbank_id'
                        )->
                    join('banks', 'banks.id', '=', $table.'.bank_id')->
                    where([[$table.'.month', '>=', $startmonth], [$table.'.year', '>=', $startyear], [$table.'.month', '<=', $endmonth], [$table.'.year', '<=', $endyear], [$table.'.status', '=', 'problem']]);
                    if(!empty($bank)  && $bank != 'all'){
                        $data = $data->where($table.'.bank_id', '=', $bank->id);
                    }
                    if(!empty($mainbank) && $mainbank != 'all'){
                        $data = $data->where(function($query) use($mainbank){
                            $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                        });
                    }
                    if(!empty($region) && $region != 'all'){
                        $data = $data->where('banks.region_work_id', '=', $region);
                    }
                    if(!empty($city) &&  $city != 'all'){
                        $data = $data->where('banks.city_id', '=', $city);
                    }
                    if(!empty($goal_code) &&  $goal_code != 'all'){
                        $data = $data->where($table.'.goal_code', '=', $goal_code);
                    }
                    if(!empty($activity_code) &&  $activity_code != 'all'){
                        $data = $data->where($table.'.activity_code', '=', $activity_code);
                    }
                    $portfolio = $data->orderBy('month', 'ASC')->get()->toArray();
                    $custom_portfolio=0;
                    if(!empty($portfolio)){
                        //print_r($portfolio);
                        foreach ($portfolio as $vf=> $portfolio_data) {
                            if(!empty($output)){
                                $i = 0;
                                foreach ($output as $output_data) {
                                    if(!$mainbank_view && $output_data['data_id'] == generateMfo($portfolio_data->mfo_id)){
                                        $portfolio_data_sum = 0;
                                        $portfolio_info = json_decode($portfolio_data->portfolio);
                                        $portfolio_data_sum = intval($portfolio_info->out_of);
                                        if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $output_data['data_monthyear'])){
                                            foreach ($output_data['data_monthyear'] as $count => $info) {
                                                if($portfolio_data->month == $output_data['month'][$count] && $portfolio_data->year == $output_data['year'][$count]){
                                                    $output[$i]['portfolio'][$count] = $output[$i]['portfolio'][$count] + $portfolio_data_sum;
                                                }
                                            }
                                        }
                                    }elseif($mainbank_view && $output_data['data_id'] == $portfolio_data->mainbank_id){
                                        $portfolio_sum = 0;
                                        $portfolio_info = json_decode($portfolio_data->portfolio);
                                        $portfolio_sum = intval($portfolio_info->out_of);
                                        if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $output_data['data_monthyear'])){
                                            foreach ($output_data['data_monthyear'] as $count => $info) {
                                                if($portfolio_data->month == $output_data['month'][$count] && $portfolio_data->year == $output_data['year'][$count]){
                                                    $output[$i]['portfolio'][$count] = $output[$i]['portfolio'][$count] + $portfolio_sum;
                                                }
                                            }
                                        }
                                    }
                                    $i++;
                                }
                            }else{
                                $month_array = array();
                                $year_array = array();
                                $month_name_array = array();
                                $portfolio_array = array();
                                if($startyear != $endyear){
                                    for($b = $startyear; $b <= $endyear; $b++){
                                        if($b != $endyear){
                                            for($g = $startmonth; $g <= 12; $g++){
                                                array_push($month_array, $g);
                                                array_push($year_array, $b);
                                                array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                array_push($portfolio_array, 0);
                                            }
                                        }else{
                                            for($g = 1; $g <= intval($endmonth); $g++){
                                                array_push($month_array, $g);
                                                array_push($year_array, $b);
                                                array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                                array_push($portfolio_array, 0);
                                            }
                                        }
                                    }
                                }else{
                                    for($k = intval($startmonth); $k <= $endmonth; $k++){
                                        array_push($month_array, $k);
                                        array_push($year_array, $endyear);
                                        array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                        array_push($portfolio_array, 0);
                                    }
                                }
                                if($mainbank_view){
                                    $position = get_position($user);
                                    $banks = DB::table('banks')->
                                    select('mainbank_id');
                                    if($position == 'admin' || $position == 'country'){
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }else{
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }
                                    if(!empty($city)  && $city != 'all'){
                                        $banks = $banks->where('banks.city_id', '=', $city);
                                    }
                                    $banks = $banks->groupBy('mainbank_id')->get()->toArray();
                                    $mainbankss = DB::table('mainbanks')->where(function($query) use($banks){
                                        foreach ($banks as $bank_default) {
                                            $query->orWhere('id', '=', $bank_default->mainbank_id);
                                        }
                                    })->orderBy('name')->get()->toArray();
                                    foreach ($mainbankss as $mbank) {
                                        $new_portfolio = array(
                                            'data_id' => $mbank->id,
                                            'data_name' => $mbank->name,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'portfolio' => $portfolio_array,
                                        );
                                        $portfolio_sum = 0;
                                        if($portfolio_data->mainbank_id == $mbank->id){
                                            $portfolio_info = json_decode($portfolio_data->portfolio);
                                            $portfolio_sum = $portfolio_sum + intval($portfolio_info->out_of);
                                            if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $new_portfolio['data_monthyear'])){
                                                foreach ($new_portfolio['data_monthyear'] as $count => $info) {
                                                    if($portfolio_data->month == $new_portfolio['month'][$count] && $portfolio_data->year == $new_portfolio['year'][$count]){
                                                        $new_portfolio['portfolio'][$count] = $new_portfolio['portfolio'][$count] + $portfolio_sum;
                                                    }
                                                }
                                            }
                                        }
                                        array_push($output, $new_portfolio);
                                    }
                                }else{
                                    $banks = DB::table('banks');
                                    if($position == 'admin' || $position == 'country'){
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }else{
                                        $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                    }
                                    if(!empty($city)  && $city != 'all'){
                                        $banks = $banks->where('banks.city_id', '=', $city);
                                    }
                                    if(!empty($bank) && $bank != 'all'){
                                        $banks = $banks->where('banks.id', '=', $bank->id);
                                    }
                                    if(!empty($mainbank)  && $mainbank != 'all'){
                                        $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                    }
                                    $banks = $banks->get()->toArray();
                                    foreach ($banks as $bank_default) {
                                        $portfolio_sum = 0;
                                        $new_portfolio = array(
                                            'data_id' => generateMfo($bank_default->mfo_id),
                                            'data_name' => $bank_default->name,
                                            'data_monthyear' => $month_name_array,
                                            'month' => $month_array,
                                            'year' => $year_array,
                                            'portfolio' => $portfolio_array,
                                        );
                                        if($portfolio_data->mfo_id == $bank_default->mfo_id){
                                            $portfolio_info = json_decode($portfolio_data->portfolio);
                                            $portfolio_sum = $portfolio_sum + intval($portfolio_info->out_of);
                                            if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $new_portfolio['data_monthyear'])){
                                                foreach ($new_portfolio['data_monthyear'] as $count => $info) {
                                                    if($portfolio_data->month == $new_portfolio['month'][$count] && $portfolio_data->year == $new_portfolio['year'][$count]){
                                                        $new_portfolio['portfolio'][$count] = $new_portfolio['portfolio'][$count] + $portfolio_sum;
                                                    }
                                                }
                                            }
                                        }
                                        array_push($output, $new_portfolio);
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                $table = 'portfolio_'.$endyear;
                $data = DB::table($table)->
                    select(
                        $table.'.*',
                        'banks.mfo_id',
                        'banks.name',
                        'banks.mainbank_id'
                    )->
                join('banks', 'banks.id', '=', $table.'.bank_id')->
                where([[$table.'.month', '>=', $startmonth], [$table.'.year', '>=', $startyear], [$table.'.month', '<=', $endmonth], [$table.'.year', '<=', $endyear], [$table.'.status', '=', 'problem']]);
                if(!empty($bank)  && $bank != 'all'){
                    $data = $data->where($table.'.bank_id', '=', $bank->id);
                }
                if(!empty($mainbank) && $mainbank != 'all'){
                    $data = $data->where(function($query) use($mainbank){
                        $query->orWhere('banks.mainbank_id', '=', $mainbank->id);
                    });
                }
                if(!empty($region) && $region != 'all'){
                    $data = $data->where('banks.region_work_id', '=', $region);
                }
                if(!empty($city) &&  $city != 'all'){
                    $data = $data->where('banks.city_id', '=', $city);
                }
                if(!empty($goal_code) &&  $goal_code != 'all'){
                    $data = $data->where($table.'.goal_code', '=', $goal_code);
                }
                if(!empty($activity_code) &&  $activity_code != 'all'){
                    $data = $data->where($table.'.activity_code', '=', $activity_code);
                }
                $portfolio = $data->orderBy('month', 'ASC')->get()->toArray();
                $custom_portfolio=0;
                if(!empty($portfolio)){
                    //print_r($portfolio);
                    foreach ($portfolio as $vf=> $portfolio_data) {
                        if(!empty($output)){
                            $i = 0;
                            foreach ($output as $output_data) {
                                if(!$mainbank_view && $output_data['data_id'] == generateMfo($portfolio_data->mfo_id)){
                                    $portfolio_data_sum = 0;
                                    $portfolio_info = json_decode($portfolio_data->portfolio);
                                    $portfolio_data_sum = intval($portfolio_info->out_of);
                                    if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $output_data['data_monthyear'])){
                                        foreach ($output_data['data_monthyear'] as $count => $info) {
                                            if($portfolio_data->month == $output_data['month'][$count] && $portfolio_data->year == $output_data['year'][$count]){
                                                $output[$i]['portfolio'][$count] = $output[$i]['portfolio'][$count] + $portfolio_data_sum;
                                            }
                                        }
                                    }
                                }elseif($mainbank_view && $output_data['data_id'] == $portfolio_data->mainbank_id){
                                    $portfolio_sum = 0;
                                    $portfolio_info = json_decode($portfolio_data->portfolio);
                                    $portfolio_sum = intval($portfolio_info->out_of);
                                    if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $output_data['data_monthyear'])){
                                        foreach ($output_data['data_monthyear'] as $count => $info) {
                                            if($portfolio_data->month == $output_data['month'][$count] && $portfolio_data->year == $output_data['year'][$count]){
                                                $output[$i]['portfolio'][$count] = $output[$i]['portfolio'][$count] + $portfolio_sum;
                                            }
                                        }
                                    }
                                }
                                $i++;
                            }
                        }else{
                            $month_array = array();
                            $year_array = array();
                            $month_name_array = array();
                            $portfolio_array = array();
                            if($startyear != $endyear){
                                for($b = $startyear; $b <= $endyear; $b++){
                                    if($b != $endyear){
                                        for($g = $startmonth; $g <= 12; $g++){
                                            array_push($month_array, $g);
                                            array_push($year_array, $b);
                                            array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                            array_push($portfolio_array, 0);
                                        }
                                    }else{
                                        for($g = 1; $g <= intval($endmonth); $g++){
                                            array_push($month_array, $g);
                                            array_push($year_array, $b);
                                            array_push($month_name_array, trans('app.shortmonth'.$g)." ".$b);
                                            array_push($portfolio_array, 0);
                                        }
                                    }
                                }
                            }else{
                                for($k = intval($startmonth); $k <= $endmonth; $k++){
                                    array_push($month_array, $k);
                                    array_push($year_array, $endyear);
                                    array_push($month_name_array, trans('app.shortmonth'.$k)." ".$endyear);
                                    array_push($portfolio_array, 0);
                                }
                            }
                            if($mainbank_view){
                                $position = get_position($user);
                                $banks = DB::table('banks')->
                                select('mainbank_id');
                                if($position == 'admin' || $position == 'country'){
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }else{
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }
                                if(!empty($city)  && $city != 'all'){
                                    $banks = $banks->where('banks.city_id', '=', $city);
                                }
                                $banks = $banks->groupBy('mainbank_id')->get()->toArray();
                                $mainbankss = DB::table('mainbanks')->where(function($query) use($banks){
                                    foreach ($banks as $bank_default) {
                                        $query->orWhere('id', '=', $bank_default->mainbank_id);
                                    }
                                })->orderBy('name')->get()->toArray();
                                foreach ($mainbankss as $mbank) {
                                    $new_portfolio = array(
                                        'data_id' => $mbank->id,
                                        'data_name' => $mbank->name,
                                        'data_monthyear' => $month_name_array,
                                        'month' => $month_array,
                                        'year' => $year_array,
                                        'portfolio' => $portfolio_array,
                                    );
                                    $portfolio_sum = 0;
                                    if($portfolio_data->mainbank_id == $mbank->id){
                                        $portfolio_info = json_decode($portfolio_data->portfolio);
                                        $portfolio_sum = $portfolio_sum + intval($portfolio_info->out_of);
                                        if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $new_portfolio['data_monthyear'])){
                                            foreach ($new_portfolio['data_monthyear'] as $count => $info) {
                                                if($portfolio_data->month == $new_portfolio['month'][$count] && $portfolio_data->year == $new_portfolio['year'][$count]){
                                                    $new_portfolio['portfolio'][$count] = $new_portfolio['portfolio'][$count] + $portfolio_sum;
                                                }
                                            }
                                        }
                                    }
                                    array_push($output, $new_portfolio);
                                }
                            }else{
                                $banks = DB::table('banks');
                                if($position == 'admin' || $position == 'country'){
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }else{
                                    $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
                                }
                                if(!empty($city)  && $city != 'all'){
                                    $banks = $banks->where('banks.city_id', '=', $city);
                                }
                                if(!empty($bank) && $bank != 'all'){
                                    $banks = $banks->where('banks.id', '=', $bank->id);
                                }
                                if(!empty($mainbank)  && $mainbank != 'all'){
                                    $banks = $banks->where('banks.mainbank_id', '=', $mainbank->id);
                                }
                                $banks = $banks->get()->toArray();
                                foreach ($banks as $bank_default) {
                                    $portfolio_sum = 0;
                                    $new_portfolio = array(
                                        'data_id' => generateMfo($bank_default->mfo_id),
                                        'data_name' => $bank_default->name,
                                        'data_monthyear' => $month_name_array,
                                        'month' => $month_array,
                                        'year' => $year_array,
                                        'portfolio' => $portfolio_array,
                                    );
                                    if($portfolio_data->mfo_id == $bank_default->mfo_id){
                                        $portfolio_info = json_decode($portfolio_data->portfolio);
                                        $portfolio_sum = $portfolio_sum + intval($portfolio_info->out_of);
                                        if(in_array(trans('app.shortmonth'.$portfolio_data->month)." ".$portfolio_data->year, $new_portfolio['data_monthyear'])){
                                            foreach ($new_portfolio['data_monthyear'] as $count => $info) {
                                                if($portfolio_data->month == $new_portfolio['month'][$count] && $portfolio_data->year == $new_portfolio['year'][$count]){
                                                    $new_portfolio['portfolio'][$count] = $new_portfolio['portfolio'][$count] + $portfolio_sum;
                                                }
                                            }
                                        }
                                    }
                                    array_push($output, $new_portfolio);
                                }
                            }
                        }
                    }
                }
            }
            //print_r($output);
            if(!empty($region) && $region != 'all'){
                $region = DB::table('regions')->where('id', '=', $region)->get()->first();
            }
            if(!empty($city) && $city != 'all'){
                $city = DB::table('cities')->where('id', '=', $city)->get()->first();
            }
            $place_title = null;
            $bank_title = null;
            $head_title = null;
            if(!empty($region) && $region != 'all' && (empty($city) || $city == 'all') ){
                $place_title = $region->name;
            }
            if(!empty($city) && $city != 'all'){
                $place_title = $city->name;
            }
            if(!empty($mainbank) && $mainbank != 'all' && (empty($bank) || $bank == 'all')){
                $bank_title = $mainbank->name;
            }
            if(!empty($bank) && $bank != 'all'){
                $bank_title = $bank->name." [".generateMfo($bank->mfo_id)."]";
            }
            if(($mainbank == 'all' && $bank == 'all') || (empty($mainbank) && empty($bank)) || (empty($mainbank) && $bank == 'all') || ($mainbank == 'all' && empty($bank))){
                $bank_title = trans('app.all banks');
            }
            if(empty($place_title)){
                $region = DB::table('regions')->where('id', '=', $user->region_id)->get()->first();
                $place_title = $region->name;
            }
            if(!empty($request->get('activity_code')) && $request->get('activity_code') != 'all'){
                $head_title = DB::table('activity_codes')->where('code', '=', $request->get('activity_code'))->get()->first()->name;
            }else{
                $head_title = "";
            }
            if(!empty($request->get('goal_code')) && $request->get('goal_code') != 'all'){
                $head_title = DB::table('goal_codes')->where('code', '=', $request->get('goal_code'))->get()->first()->name;
            }
            $max = 0;
            foreach($output as $output_data){
                foreach($output_data['portfolio'] as $max_port){
                    if($max_port > $max){
                        $max = $max_port;
                    }
                }
            }
            $number_dividing = number_formatting($max);
            $text_number = $number_dividing->text." UZS";
            foreach($output as $count => $output_data){
                foreach($output_data['portfolio'] as $key => $max_port){
                    if($max_port > 0){
                        $output[$count]['portfolio'][$key] = $output[$count]['portfolio'][$key]/$number_dividing->dividing;
                    }
                }
            }

            //print_r($output);
            $colors = getColors();
            $info = array(
                'chart' => $output,
                'head_title' => $head_title,
                'place_title' => $place_title,
                'bank_title' => $bank_title,
                'mainbank_view' => $mainbank_view,
                'dividing' => $number_dividing->dividing,
                'divide_text' => $text_number,
                'time_title' => trans('app.shortmonth'.intval($startmonth))." ".$startyear." - ".trans('app.shortmonth'.intval($endmonth))." ".$endyear,
                'colors' => $colors,
                'custom_portfolio' => $custom_portfolio
            );
            $data = json_encode($info);
            echo $data;
            //return view('chart.linechart', compact('title', 'regions', 'mainbanks', 'cat_accounts', 'data', 'bank', 'cat_account', 'mainbank', 'monthyear', 'fillials', 'region', 'account_sheets', 'account_sheet'));
        }else{
            return view('chart.loanlineproblem', compact('title', 'regions', 'cities', 'mainbanks', 'activities', 'goal_codes'));
        }
    }

    
    
}