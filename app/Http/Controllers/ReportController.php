<?php

namespace App\Http\Controllers;

use DB;
use URL;
use Auth;
use Mail;
use DateTime;
use stdClass;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function cash_report(Request $request){
        $title = trans('app.cash rating');
        $monthyear = $request->get('monthyear');
        $mfo_id = $request->get('mfo');
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));

            // $data = DB::table('data_'.$year)->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // all_report($monthyear, $data);
            // cash_report($monthyear, $data);


            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            $data_table = 'data_'.$year;
            $report_table = 'report_'.$year;
            $check = Schema::hasTable($report_table);
            if($check){
                $reports = DB::table($report_table)->
                select(
                    $report_table.'.*',
                    DB::raw('("cash"->>\'percent\')::numeric as percent'),
                    'banks.short_name as name'
                )->
                join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]]);
                if(!empty($mfo_id)){
                    $reports = $reports->where('banks.mfo_id', '=', $mfo_id);
                }
                $reports = $reports->orderBy('percent', 'desc')->get()->toArray();
                if(!empty($reports)){
                    if($month == 1){
                        $last_month = 12;
                        $last_year = $year-1;
                        $last_report_table = 'report_'.$last_year;
                    }else{
                        $last_month = $month - 1;
                        $last_year = $year;
                        $last_report_table = 'report_'.$last_year;
                    }
                    $check = Schema::hasTable($last_report_table);
                    if($check){
                        $last_reports = DB::table($last_report_table)->select('*',DB::raw('("cash"->>\'percent\')::numeric as percent'))->
                        where([['month', '=', $last_month], ['year', '=', $last_year]])->orderBy('percent', 'desc')->get()->toArray();
                        if(!empty($last_reports) && !empty($reports)){
                            $count = 1;
                            foreach($reports as $report){
                                $percent = 0;
                                $rate_diff = 0;
                                $last_count = 1;
                                foreach($last_reports as $last_report){
                                    if($report->mfo_id == $last_report->mfo_id){
                                        if(!empty($last_report->cash) && !empty($report->cash)){
                                            $report_js = json_decode($report->cash);
                                            $last_report_js = json_decode($last_report->cash);
                                            $percent = number_format(($report_js->percent - $last_report_js->percent??0), 2);
                                            $rate_diff = $last_count - $count;
                                            
                                        }
                                    }
                                    $last_count++;
                                }
                                $report->rate_percent = $percent;
                                $report->rate_diff = $rate_diff;
                                $count++;
                            }
                        }
                    }
                    $type = 'success';
                    $message = "Malumotlar yuklandi";
                    return view('report.cash.cash', compact('reports', 'title', 'type', 'message', 'monthyear', 'weight'));
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.cash.cash', compact('title', 'type', 'message', 'monthyear', 'weight'));
                }
            }else{
                $type = 'error';
                $message = trans('app.data not found at date that you entered');
                return view('report.cash.cash', compact('title', 'type', 'message', 'monthyear', 'weight'));
            }  
        }else{
            return view('report.cash.cash', compact('title', 'monthyear', 'weight'));
        }
    }

    public function inspeksiya_report(Request $request){
        $title = trans('app.inspeksiya rating');
        $monthyear = $request->get('monthyear');
        $mfo_id = $request->get('mfo');
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){

            // $month = date('m', strtotime($monthyear));
            // $year = date('Y', strtotime($monthyear));
            // $data = DB::table('data_'.$year)->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // inspeksiya_report($monthyear, $data);
            // all_report($monthyear, $data);


            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            $data_table = 'data_'.$year;
            $report_table = 'report_'.$year;
            $check = Schema::hasTable($report_table);
            if($check){
                $reports = DB::table($report_table)->
                select(
                    $report_table.'.*',
                    DB::raw('("inspeksiya"->>\'percent\')::numeric as percent'),
                    'banks.short_name as name'
                )->
                join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]]);
                if(!empty($mfo_id)){
                    $reports = $reports->where('banks.mfo_id', '=', $mfo_id);
                }
                $reports = $reports->orderBy('percent', 'desc')->get()->toArray();
                if(!empty($reports)){
                    if($month == 1){
                        $last_month = 12;
                        $last_year = $year-1;
                        $last_report_table = 'report_'.$last_year;
                    }else{
                        $last_month = $month - 1;
                        $last_year = $year;
                        $last_report_table = 'report_'.$last_year;
                    }
                    $check = Schema::hasTable($last_report_table);
                    if($check){
                        $last_reports = DB::table($last_report_table)->select('*',DB::raw('("inspeksiya"->>\'percent\')::numeric as percent'))->
                        where([['month', '=', $last_month], ['year', '=', $last_year]])->orderBy('percent', 'desc')->get()->toArray();
                        if(!empty($last_reports) && !empty($reports)){
                            $count = 1;
                            foreach($reports as $report){
                                $percent = 0;
                                $rate_diff = 0;
                                $last_count = 1;
                                foreach($last_reports as $last_report){
                                    if($report->mfo_id == $last_report->mfo_id){
                                        if(!empty($last_report->inspeksiya) && !empty($report->inspeksiya)){
                                            $report_js = json_decode($report->inspeksiya);
                                            $last_report_js = json_decode($last_report->inspeksiya);
                                            $percent = number_format(($report_js->percent - $last_report_js->percent??0), 2);
                                            $rate_diff = $last_count - $count;
                                            
                                        }
                                    }
                                    $last_count++;
                                }
                                $report->rate_percent = $percent;
                                $report->rate_diff = $rate_diff;
                                $count++;
                            }
                        }
                    }
                    $type = 'success';
                    $message = "Malumotlar yuklandi";
                    return view('report.inspeksiya.inspeksiya', compact('reports', 'title', 'type', 'message', 'monthyear', 'weight'));
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.inspeksiya.inspeksiya', compact('title', 'type', 'message', 'monthyear', 'weight'));
                }
            }else{
                $type = 'error';
                $message = trans('app.data not found at date that you entered');
                return view('report.inspeksiya.inspeksiya', compact('title', 'type', 'message', 'monthyear', 'weight'));
            } 
        }else{
            return view('report.inspeksiya.inspeksiya', compact('title', 'monthyear', 'weight'));
        }
    }

    public function currency_report(Request $request){
        $title = trans('app.currency rating');
        $monthyear = $request->get('monthyear');
        $mfo_id = $request->get('mfo');
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){

            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));


            // $data = DB::table('data_'.$year)->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // currency_report($monthyear, $data);
            // all_report($monthyear, $data);


            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            $data_table = 'data_'.$year;
            $report_table = 'report_'.$year;
            if(checkTable('data', $year)){
                $checking_data = $data = DB::table($data_table)->
                where([['month', '=', $month], ['year', '=', $year]])->latest()->first();
                $check = Schema::hasTable($report_table);
                if($check){
                    $reports = DB::table($report_table)->
                    select(
                        $report_table.'.*',
                        DB::raw('("currency"->>\'percent\')::numeric as percent'),
                        'banks.short_name as name'
                    )->
                    join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                    where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]]);
                    if(!empty($mfo_id)){
                        $reports = $reports->where('banks.mfo_id', '=', $mfo_id);
                    }
                    $reports = $reports->orderBy('percent', 'desc')->get()->toArray();
                    if(!empty($reports)){
                        if($month == 1){
                            $last_month = 12;
                            $last_year = $year-1;
                            $last_report_table = 'report_'.$last_year;
                        }else{
                            $last_month = $month - 1;
                            $last_year = $year;
                            $last_report_table = 'report_'.$last_year;
                        }
                        $check = Schema::hasTable($last_report_table);
                        if($check){
                            $last_reports = DB::table($last_report_table)->select(DB::raw('("currency"->>\'percent\')::numeric as percent'), '*')->
                            where([['month', '=', $last_month], ['year', '=', $last_year]])->orderBy('percent', 'desc')->get()->toArray();
                            if(!empty($last_reports) && !empty($reports)){
                                $count = 1;
                                foreach($reports as $report){
                                    $percent = 0;
                                    $rate_diff = 0;
                                    $last_count = 1;
                                    foreach($last_reports as $last_report){
                                        if($report->mfo_id == $last_report->mfo_id){
                                            if(!empty($last_report->currency) && !empty($report->currency)){
                                                $report_js = json_decode($report->currency);
                                                $last_report_js = json_decode($last_report->currency);
                                                $percent = number_format(($report_js->percent - $last_report_js->percent??0), 2);
                                                $rate_diff = $last_count - $count;
                                                
                                            }
                                        }
                                        $last_count++;
                                    }
                                    $report->rate_percent = $percent;
                                    $report->rate_diff = $rate_diff;
                                    $count++;
                                }
                            }
                        }
                        $type = 'success';
                        $message = "Malumotlar yuklandi";
                        return view('report.currency.currency', compact('reports', 'title', 'type', 'message', 'monthyear', 'weight'));
                    }else{
                        $type = 'error';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.currency.currency', compact('title', 'type', 'message', 'monthyear', 'weight'));
                    }
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.currency.currency', compact('title', 'type', 'message', 'monthyear', 'weight'));
                }
            }else{
                $type = 'error';
                $message = trans('app.data not found at date that you entered');
                return view('report.currency.currency', compact('title', 'type', 'message', 'monthyear', 'weight'));
            } 
        }else{
            return view('report.currency.currency', compact('title', 'monthyear', 'weight'));
        }
    }

    public function business_report(Request $request){
        $title = trans('app.business rating');
        $monthyear = $request->get('monthyear');
        $mfo_id = $request->get('mfo');
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){

            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));

            // $data = DB::table('data_'.$year)->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // business_report($monthyear, $data);
            // all_report($monthyear, $data);

            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            $data_table = 'data_'.$year;
            $report_table = 'report_'.$year;
            $check = Schema::hasTable($report_table);
            if($check){
                $reports = DB::table($report_table)->
                select(
                    $report_table.'.*',
                    DB::raw('("business"->>\'percent\')::numeric as percent'),
                    'banks.short_name as name'
                )->
                join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]]);
                if(!empty($mfo_id)){
                    $reports = $reports->where('banks.mfo_id', '=', $mfo_id);
                }
                $reports = $reports->orderBy('percent', 'desc')->get()->toArray();
                //print_r($reports);
                if(!empty($reports)){
                    if($month == 1){
                        $last_month = 12;
                        $last_year = $year-1;
                        $last_report_table = 'report_'.$last_year;
                    }else{
                        $last_month = $month - 1;
                        $last_year = $year;
                        $last_report_table = 'report_'.$last_year;
                    }
                    $check = Schema::hasTable($last_report_table);
                    if($check){
                        $last_reports = DB::table($last_report_table)->select(DB::raw('("business"->>\'percent\')::numeric as percent'), '*')->
                        where([['month', '=', $last_month], ['year', '=', $last_year]])->orderBy('percent', 'desc')->get()->toArray();
                        echo "                      ";
                        //print_r($last_reports);
                        if(!empty($last_reports) && !empty($reports)){
                            $count = 1;
                            foreach($reports as $report){
                                $percent = 0;
                                $rate_diff = 0;
                                $last_count = 1;
                                foreach($last_reports as $last_report){
                                    if($report->mfo_id == $last_report->mfo_id){
                                        if(!empty($last_report->business) && !empty($report->business)){
                                            $report_js = json_decode($report->business);
                                            $last_report_js = json_decode($last_report->business);
                                            $percent = number_format(($report_js->percent - $last_report_js->percent??0), 2);
                                            $rate_diff = $last_count - $count;
                                        }
                                    }
                                    $last_count++;
                                }
                                $report->rate_percent = $percent;
                                $report->rate_diff = $rate_diff;
                                $count++;
                            }
                        }
                    }
                    $type = 'success';
                    $message = "Malumotlar yuklandi";
                    return view('report.business.business', compact('reports', 'title', 'type', 'message', 'monthyear', 'weight'));
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.business.business', compact('title', 'type', 'message', 'monthyear', 'weight'));
                }
            }else{
                $type = 'error';
                $message = trans('app.data not found at date that you entered');
                return view('report.business.business', compact('title', 'type', 'message', 'monthyear', 'weight'));
            }
        }else{
            return view('report.business.business', compact('title', 'monthyear', 'weight'));
        }
    }

    public function ijro_report(Request $request){
        $title = trans('app.ijro rating');
        $monthyear = $request->get('monthyear');
        $mfo_id = $request->get('mfo');
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){

            // $month = date('m', strtotime($monthyear));
            // $year = date('Y', strtotime($monthyear));
            // $data = DB::table('data_'.$year)->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // all_report($monthyear, $data);
            // ijro_report($monthyear, $data);


            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            $data_table = 'data_'.$year;
            $report_table = 'report_'.$year;
            $check = Schema::hasTable($data_table);
            if($check){
                $reports = DB::table($data_table)->
                select(
                    $data_table.'.*',
                    DB::raw('('.$report_table.'.ijro->>\'percent\')::numeric as percent'),
                    'banks.short_name as name'
                )->
                join('banks', 'banks.id', '=', $data_table.'.bank_id')->
                join($report_table, function($join) use($report_table, $month, $year){
                    $join->on('banks.id', '=', $report_table.'.bank_id')->
                    where([[$report_table.'.month', '=', $month], [$report_table.'.year', '=', $year]]);
                })->
                where([[$data_table.'.month', '=', $month], [$data_table.'.year', '=', $year], ['banks.mainbank_id', '!=', 38]]);
                if(!empty($mfo_id)){
                    $reports = $reports->where('banks.mfo_id', '=', $mfo_id);
                }
                $reports = $reports->orderBy('percent', 'desc')->get()->toArray();
                if(!empty($reports)){
                    if($month == 1){
                        $last_month = 12;
                        $last_year = $year-1;
                        $last_report_table = 'data_'.$last_year;
                    }else{
                        $last_month = $month - 1;
                        $last_year = $year;
                        $last_report_table = 'data_'.$last_year;
                    }
                    $check = Schema::hasTable($last_report_table);
                    if($check){
                        $last_reports = DB::table($last_report_table)->select(DB::raw('('.$report_table.'.ijro->>\'percent\')::numeric as percent'), '*')->
                        join($report_table, function($join) use($report_table, $last_month, $last_year, $last_report_table){
                            $join->on($last_report_table.'.bank_id', '=', $report_table.'.bank_id')->
                            where([[$report_table.'.month', '=', $last_month], [$report_table.'.year', '=', $last_year]]);
                        })->
                        where([[$last_report_table.'.month', '=', $last_month], [$last_report_table.'.year', '=', $last_year]])->orderBy('percent', 'desc')->get()->toArray();
                        if(!empty($last_reports) && !empty($reports)){
                            $count = 1;
                            foreach($reports as $report){
                                $percent = 0;
                                $rate_diff = 0;
                                $last_count = 1;
                                foreach($last_reports as $last_report){
                                    if($report->mfo_id == $last_report->mfo_id){
                                        if(!empty($last_report->ijro) && !empty($report->ijro)){
                                            $report_js = json_decode($report->ijro);
                                            $last_report_js = json_decode($last_report->ijro);
                                            $percent = number_format(($report_js->percent??0 - $last_report_js->percent??0), 2);
                                            $rate_diff = $last_count - $count;
                                            
                                        }
                                    }
                                    $last_count++;
                                }
                                $report->rate_percent = $percent;
                                $report->rate_diff = $rate_diff;
                                $count++;
                            }
                        }
                    }
                    $type = 'success';
                    $message = "Malumotlar yuklandi";
                    return view('report.ijro.ijro', compact('reports', 'title', 'type', 'message', 'monthyear', 'weight'));
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.ijro.ijro', compact('title', 'type', 'message', 'monthyear', 'weight'));
                }
            }else{
                $type = 'error';
                $message = trans('app.data not found at date that you entered');
                return view('report.ijro.ijro', compact('title', 'type', 'message', 'monthyear', 'weight'));
            }
        }else{
            return view('report.ijro.ijro', compact('title', 'monthyear', 'weight'));
        }
    }

    public function final_report(Request $request){
        $title = trans('app.all rating monthly');
        $monthyear = $request->get('monthyear');
        // $currentmonth = date('m');
        // for ($b = 1; $b < $currentmonth; $b++){
        //     $gen_month = '2020-'.$b;
        //     $data = DB::table('data_2020')->where([['month', '=', $b], ['year', '=', 2020]])->get()->toArray();
        //     inspeksiya_report($gen_month, $data);
        //     business_report($gen_month, $data);
        //     currency_report($gen_month, $data);
        //     cash_report($gen_month, $data);
        //     ijro_report($gen_month, $data);
        //     all_report($gen_month, $data);
        // }
            
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){
            $month = intval(date('m', strtotime($monthyear)));
            $year = date('Y', strtotime($monthyear));
            // $data = DB::table('data_2020')->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // inspeksiya_report($monthyear, $data);
            // business_report($monthyear, $data);
            // currency_report($monthyear, $data);
            // cash_report($monthyear, $data);
            // ijro_report($monthyear, $data);
            // all_report($monthyear, $data);
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            if(!empty($weight)){
                $data_table = 'data_'.$year;
                $report_table = 'report_'.$year;
                $check = Schema::hasTable($report_table);
                if($check){
                    $reports = DB::table($report_table)->
                    select(
                        $report_table.'.*',
                        'banks.short_name as name'
                    )->
                    join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                    where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]])->orderBy($report_table.'.rate', 'desc')->
                    get()->toArray();
                    //print_r($reports);
                    if(!empty($reports)){
                        if($month == 1){
                            $last_month = 12;
                            $last_year = $year-1;
                            $last_report_table = 'report_'.$last_year;
                        }else{
                            $last_month = $month - 1;
                            $last_year = $year;
                            $last_report_table = 'report_'.$last_year;
                        }
                        $check = Schema::hasTable($last_report_table);
                        if($check){
                            $last_reports = DB::table($last_report_table)->where([['month', '=', $last_month], ['year', '=', $last_year]])->orderBy('rate', 'desc')->get()->toArray();
                            if(!empty($last_reports) && !empty($reports)){
                                $count = 1;
                                foreach($reports as $report){
                                    $percent = 0;
                                    $rate_diff = 0;
                                    $last_count = 1;
                                    foreach($last_reports as $last_report){
                                        if($report->mfo_id == $last_report->mfo_id){
                                            $percent = number_format(($report->rate-$last_report->rate??0), 2);
                                            $rate_diff = $last_count - $count;
                                        }
                                        $last_count++;
                                    }
                                    $report->rate_percent = $percent;
                                    $report->rate_diff = $rate_diff;
                                    $count++;
                                }
                            }
                        }
                        $type = 'success';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.all', compact('reports', 'title', 'type', 'message', 'monthyear', 'weight'));
                    }else{
                        $type = 'error';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.all', compact('title', 'type', 'message', 'monthyear', 'weight'));
                    }
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.all', compact('title', 'type', 'message', 'monthyear', 'weight'));
                } 
            }else{
                $type = 'error';
                $message = trans('app.Weights for you selected date is not found please add weight and try again');
                return view('report.all', compact('title', 'type', 'message', 'monthyear'));
            }
             
        }else{
            return view('report.all', compact('title', 'monthyear', 'weight'));
        }
    }
    public function mainbanks_report(Request $request){
        $user = Auth::user();
        $position = get_position($user);
        $title = trans('app.all rating monthly in mainbanks');
        $monthyear = $request->get('monthyear');
        // $currentmonth = date('m');
        // for ($b = 1; $b < $currentmonth; $b++){
        //     $gen_month = '2020-'.$b;
        //     $data = DB::table('data_2020')->where([['month', '=', $b], ['year', '=', 2020]])->get()->toArray();
        //     inspeksiya_report($gen_month, $data);
        //     business_report($gen_month, $data);
        //     currency_report($gen_month, $data);
        //     cash_report($gen_month, $data);
        //     ijro_report($gen_month, $data);
        //     all_report($gen_month, $data);
        // }
            
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){
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


            $month = intval(date('m', strtotime($monthyear)));
            $year = date('Y', strtotime($monthyear));
            // $data = DB::table('data_2020')->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // inspeksiya_report($monthyear, $data);
            // business_report($monthyear, $data);
            // currency_report($monthyear, $data);
            // cash_report($monthyear, $data);
            // ijro_report($monthyear, $data);
            // all_report($monthyear, $data);
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            if(!empty($weight)){
                $data_table = 'data_'.$year;
                $report_table = 'report_'.$year;
                $check = Schema::hasTable($report_table);
                if($check){
                    $reports = DB::table($report_table)->
                    select(
                        $report_table.'.*',
                        'banks.short_name as name',
                        'banks.mainbank_id'
                    )->
                    join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                    where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]])->orderBy($report_table.'.rate', 'desc')->
                    get()->toArray();
                    //print_r($reports);
                    if(!empty($reports)){
                        if($month == 1){
                            $last_month = 12;
                            $last_year = $year-1;
                            $last_report_table = 'report_'.$last_year;
                        }else{
                            $last_month = $month - 1;
                            $last_year = $year;
                            $last_report_table = 'report_'.$last_year;
                        }
                        $check = Schema::hasTable($last_report_table);
                        if($check){
                            $last_reports = DB::table($last_report_table)->where([['month', '=', $last_month], ['year', '=', $last_year]])->orderBy('rate', 'desc')->get()->toArray();
                            if(!empty($last_reports) && !empty($reports)){
                                $count = 1;
                                foreach($reports as $report){
                                    $percent = 0;
                                    $rate_diff = 0;
                                    $last_count = 1;
                                    foreach($last_reports as $last_report){
                                        if($report->mfo_id == $last_report->mfo_id){
                                            $percent = number_format(($report->rate-$last_report->rate??0), 2);
                                            $rate_diff = $last_count - $count;
                                        }
                                        $last_count++;
                                    }
                                    $report->rate_percent = $percent;
                                    $report->rate_diff = $rate_diff;
                                    $count++;
                                }
                            }
                        }
                        foreach ($mainbankss as $key => $mainbank) {
                            $mainbank->inspeksiya = new stdClass;
                            $mainbank->business = new stdClass;
                            $mainbank->cash = new stdClass;
                            $mainbank->currency = new stdClass;
                            $mainbank->ijro = new stdClass;
                            $mainbank->inspeksiya->final_result = 0;
                            $mainbank->inspeksiya->percent = 0;
                            $mainbank->cash->final_result = 0;
                            $mainbank->cash->percent = 0;
                            $mainbank->currency->final_result = 0;
                            $mainbank->currency->percent = 0;
                            $mainbank->ijro->final_result = 0;
                            $mainbank->ijro->percent = 0;
                            $mainbank->business->final_result = 0;
                            $mainbank->business->percent = 0;
                            $mainbank->rate = 0;
                            foreach ($reports as  $report) {
                                if($report->mainbank_id == $mainbank->mainbank_id){
                                    $mainbank->inspeksiya->final_result += json_decode($report->inspeksiya)->final_result/$mainbank->number;
                                    $mainbank->inspeksiya->percent += json_decode($report->inspeksiya)->percent/$mainbank->number;
                                    $mainbank->business->final_result += json_decode($report->business)->final_result/$mainbank->number;
                                    $mainbank->business->percent += json_decode($report->business)->percent/$mainbank->number;
                                    $mainbank->currency->final_result += json_decode($report->currency)->final_result/$mainbank->number;
                                    $mainbank->currency->percent += json_decode($report->currency)->percent/$mainbank->number;
                                    $mainbank->cash->final_result += json_decode($report->cash)->final_result/$mainbank->number;
                                    $mainbank->cash->percent += json_decode($report->cash)->percent/$mainbank->number;
                                    $mainbank->ijro->final_result += json_decode($report->ijro)->final_result/$mainbank->number;
                                    $mainbank->ijro->percent += json_decode($report->ijro)->percent/$mainbank->number;
                                    $mainbank->rate += $report->rate/$mainbank->number;
                                }
                            }
                        }
                        $type = 'success';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.mainall', compact('title', 'type', 'message', 'monthyear', 'weight', 'mainbankss'));
                    }else{
                        $type = 'error';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.mainall', compact('title', 'type', 'message', 'monthyear', 'weight'));
                    }
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.mainall', compact('title', 'type', 'message', 'monthyear', 'weight'));
                } 
            }else{
                $type = 'error';
                $message = trans('app.Weights for you selected date is not found please add weight and try again');
                return view('report.mainall', compact('title', 'type', 'message', 'monthyear'));
            }
             
        }else{
            return view('report.mainall', compact('title', 'monthyear', 'weight'));
        }
    }

    public function mainbank_cash_report(Request $request){
        $user = Auth::user();
        $position = get_position($user);
        $title = trans('app.cash rating monthly in mainbanks');
        $monthyear = $request->get('monthyear');
        // $currentmonth = date('m');
        // for ($b = 1; $b < $currentmonth; $b++){
        //     $gen_month = '2020-'.$b;
        //     $data = DB::table('data_2020')->where([['month', '=', $b], ['year', '=', 2020]])->get()->toArray();
        //     inspeksiya_report($gen_month, $data);
        //     business_report($gen_month, $data);
        //     currency_report($gen_month, $data);
        //     cash_report($gen_month, $data);
        //     ijro_report($gen_month, $data);
        //     all_report($gen_month, $data);
        // }
            
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){
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


            $month = intval(date('m', strtotime($monthyear)));
            $year = date('Y', strtotime($monthyear));
            // $data = DB::table('data_2020')->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // inspeksiya_report($monthyear, $data);
            // business_report($monthyear, $data);
            // currency_report($monthyear, $data);
            // cash_report($monthyear, $data);
            // ijro_report($monthyear, $data);
            // all_report($monthyear, $data);
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            if(!empty($weight)){
                $data_table = 'data_'.$year;
                $report_table = 'report_'.$year;
                $check = Schema::hasTable($report_table);
                if($check){
                    $reports = DB::table($report_table)->
                    select(
                        $report_table.'.*',
                        'banks.short_name as name',
                        'banks.mainbank_id'
                    )->
                    join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                    where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]])->orderBy($report_table.'.rate', 'desc')->
                    get()->toArray();
                    //print_r($reports);
                    if(!empty($reports)){
                        if($month == 1){
                            $last_month = 12;
                            $last_year = $year-1;
                            $last_report_table = 'report_'.$last_year;
                        }else{
                            $last_month = $month - 1;
                            $last_year = $year;
                            $last_report_table = 'report_'.$last_year;
                        }
                        $check = Schema::hasTable($last_report_table);
                        if($check){
                            $last_reports = DB::table($last_report_table)->where([['month', '=', $last_month], ['year', '=', $last_year]])->orderBy('rate', 'desc')->get()->toArray();
                            if(!empty($last_reports) && !empty($reports)){
                                $count = 1;
                                foreach($reports as $report){
                                    $percent = 0;
                                    $rate_diff = 0;
                                    $last_count = 1;
                                    foreach($last_reports as $last_report){
                                        if($report->mfo_id == $last_report->mfo_id){
                                            $percent = number_format(($report->rate-$last_report->rate??0), 2);
                                            $rate_diff = $last_count - $count;
                                        }
                                        $last_count++;
                                    }
                                    $report->rate_percent = $percent;
                                    $report->rate_diff = $rate_diff;
                                    $count++;
                                }
                            }
                        }
                        foreach ($mainbankss as $key => $mainbank) {
                            $mainbank->cash_tushum = new stdClass;
                            $mainbank->cash_qaytish = new stdClass;
                            $mainbank->cash_execution = new stdClass;
                            $mainbank->cash_m_report = new stdClass;
                            $mainbank->cash_tushum->final_result = 0;
                            $mainbank->cash_tushum->percent = 0;
                            $mainbank->cash_execution->final_result = 0;
                            $mainbank->cash_execution->percent = 0;
                            $mainbank->cash_m_report->final_result = 0;
                            $mainbank->cash_m_report->percent = 0;
                            $mainbank->cash_qaytish->final_result = 0;
                            $mainbank->cash_qaytish->percent = 0;
                            $mainbank->rate = 0;
                            foreach ($reports as  $report) {
                                if($report->mainbank_id == $mainbank->mainbank_id){
                                    $mainbank->cash_tushum->final_result += json_decode($report->cash_tushum)->final_result/$mainbank->number;
                                    $mainbank->cash_tushum->percent += json_decode($report->cash_tushum)->percent/$mainbank->number;
                                    $mainbank->cash_qaytish->final_result += json_decode($report->cash_qaytish)->final_result/$mainbank->number;
                                    $mainbank->cash_qaytish->percent += json_decode($report->cash_qaytish)->percent/$mainbank->number;
                                    $mainbank->cash_m_report->final_result += json_decode($report->cash_m_report)->final_result/$mainbank->number;
                                    $mainbank->cash_m_report->percent += json_decode($report->cash_m_report)->percent/$mainbank->number;
                                    $mainbank->cash_execution->final_result += json_decode($report->cash_execution)->final_result/$mainbank->number;
                                    $mainbank->cash_execution->percent += json_decode($report->cash_execution)->percent/$mainbank->number;
                                    $mainbank->rate += json_decode($report->cash)->percent/$mainbank->number;
                                }
                            }
                        }
                        $type = 'success';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.cash.cashmainbanks', compact('title', 'type', 'message', 'monthyear', 'weight', 'mainbankss'));
                    }else{
                        $type = 'error';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.cash.cashmainbanks', compact('title', 'type', 'message', 'monthyear', 'weight'));
                    }
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.cash.cashmainbanks', compact('title', 'type', 'message', 'monthyear', 'weight'));
                } 
            }else{
                $type = 'error';
                $message = trans('app.Weights for you selected date is not found please add weight and try again');
                return view('report.cash.cashmainbanks', compact('title', 'type', 'message', 'monthyear'));
            }
             
        }else{
            return view('report.cash.cashmainbanks', compact('title', 'monthyear', 'weight'));
        }
    }

    public function mainbank_inspeksiya_report(Request $request){
        $user = Auth::user();
        $position = get_position($user);
        $title = trans('app.inspeksiya rating monthly in mainbanks');
        $monthyear = $request->get('monthyear');
        // $currentmonth = date('m');
        // for ($b = 1; $b < $currentmonth; $b++){
        //     $gen_month = '2020-'.$b;
        //     $data = DB::table('data_2020')->where([['month', '=', $b], ['year', '=', 2020]])->get()->toArray();
        //     inspeksiya_report($gen_month, $data);
        //     business_report($gen_month, $data);
        //     currency_report($gen_month, $data);
        //     cash_report($gen_month, $data);
        //     ijro_report($gen_month, $data);
        //     all_report($gen_month, $data);
        // }
            
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){
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


            $month = intval(date('m', strtotime($monthyear)));
            $year = date('Y', strtotime($monthyear));
            // $data = DB::table('data_2020')->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // inspeksiya_report($monthyear, $data);
            // business_report($monthyear, $data);
            // currency_report($monthyear, $data);
            // cash_report($monthyear, $data);
            // ijro_report($monthyear, $data);
            // all_report($monthyear, $data);
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            if(!empty($weight)){
                $data_table = 'data_'.$year;
                $report_table = 'report_'.$year;
                $check = Schema::hasTable($report_table);
                if($check){
                    $reports = DB::table($report_table)->
                    select(
                        $report_table.'.*',
                        'banks.short_name as name',
                        'banks.mainbank_id'
                    )->
                    join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                    where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]])->orderBy($report_table.'.rate', 'desc')->
                    get()->toArray();
                    //print_r($reports);
                    if(!empty($reports)){
                        if($month == 1){
                            $last_month = 12;
                            $last_year = $year-1;
                            $last_report_table = 'report_'.$last_year;
                        }else{
                            $last_month = $month - 1;
                            $last_year = $year;
                            $last_report_table = 'report_'.$last_year;
                        }
                        $check = Schema::hasTable($last_report_table);
                        if($check){
                            $last_reports = DB::table($last_report_table)->where([['month', '=', $last_month], ['year', '=', $last_year]])->orderBy('rate', 'desc')->get()->toArray();
                            if(!empty($last_reports) && !empty($reports)){
                                $count = 1;
                                foreach($reports as $report){
                                    $percent = 0;
                                    $rate_diff = 0;
                                    $last_count = 1;
                                    foreach($last_reports as $last_report){
                                        if($report->mfo_id == $last_report->mfo_id){
                                            $percent = number_format(($report->rate-$last_report->rate??0), 2);
                                            $rate_diff = $last_count - $count;
                                        }
                                        $last_count++;
                                    }
                                    $report->rate_percent = $percent;
                                    $report->rate_diff = $rate_diff;
                                    $count++;
                                }
                            }
                        }
                        foreach ($mainbankss as $key => $mainbank) {
                            $mainbank->i_out_of = new stdClass;
                            $mainbank->i_work_lost = new stdClass;
                            $mainbank->i_likvid_active = new stdClass;
                            $mainbank->i_likvid_credit = new stdClass;
                            $mainbank->i_active_likvid = new stdClass;
                            $mainbank->i_b_liability = new stdClass;
                            $mainbank->i_b_liability_demand = new stdClass;
                            $mainbank->i_net_profit = new stdClass;
                            $mainbank->i_income_expense = new stdClass;
                            $mainbank->i_others = new stdClass;
                            $mainbank->i_out_of->final_result = 0;
                            $mainbank->i_out_of->percent = 0;
                            $mainbank->i_likvid_active->final_result = 0;
                            $mainbank->i_likvid_active->percent = 0;
                            $mainbank->i_work_lost->final_result = 0;
                            $mainbank->i_work_lost->percent = 0;
                            $mainbank->i_likvid_credit->final_result = 0;
                            $mainbank->i_likvid_credit->percent = 0;
                            $mainbank->i_active_likvid->final_result = 0;
                            $mainbank->i_active_likvid->percent = 0;
                            $mainbank->i_b_liability->final_result = 0;
                            $mainbank->i_b_liability->percent = 0;
                            $mainbank->i_b_liability_demand->final_result = 0;
                            $mainbank->i_b_liability_demand->percent = 0;
                            $mainbank->i_net_profit->final_result = 0;
                            $mainbank->i_net_profit->percent = 0;
                            $mainbank->i_income_expense->final_result = 0;
                            $mainbank->i_income_expense->percent = 0;
                            $mainbank->i_others->final_result = 0;
                            $mainbank->rate = 0;
                            foreach ($reports as  $report) {
                                if($report->mainbank_id == $mainbank->mainbank_id){
                                    $mainbank->i_out_of->final_result += json_decode($report->i_out_of)->final_result/$mainbank->number;
                                    $mainbank->i_out_of->percent += json_decode($report->i_out_of)->percent/$mainbank->number;
                                    $mainbank->i_work_lost->final_result += json_decode($report->i_work_lost)->final_result/$mainbank->number;
                                    $mainbank->i_work_lost->percent += json_decode($report->i_work_lost)->percent/$mainbank->number;
                                    $mainbank->i_likvid_active->final_result += json_decode($report->i_likvid_active)->final_result/$mainbank->number;
                                    $mainbank->i_likvid_active->percent += json_decode($report->i_likvid_active)->percent/$mainbank->number;
                                    $mainbank->i_likvid_credit->final_result += json_decode($report->i_likvid_credit)->final_result/$mainbank->number;
                                    $mainbank->i_likvid_credit->percent += json_decode($report->i_likvid_credit)->percent/$mainbank->number;
                                    $mainbank->i_active_likvid->final_result += json_decode($report->i_active_likvid)->final_result/$mainbank->number;
                                    $mainbank->i_active_likvid->percent += json_decode($report->i_active_likvid)->percent/$mainbank->number;
                                    $mainbank->i_b_liability->final_result += json_decode($report->i_b_liability)->final_result/$mainbank->number;
                                    $mainbank->i_b_liability->percent += json_decode($report->i_b_liability)->percent/$mainbank->number;
                                    $mainbank->i_b_liability_demand->final_result += json_decode($report->i_b_liability_demand)->final_result/$mainbank->number;
                                    $mainbank->i_b_liability_demand->percent += json_decode($report->i_b_liability_demand)->percent/$mainbank->number;
                                    $mainbank->i_net_profit->final_result += json_decode($report->i_net_profit)->final_result/$mainbank->number;
                                    $mainbank->i_net_profit->percent += json_decode($report->i_net_profit)->percent/$mainbank->number;
                                    $mainbank->i_income_expense->final_result += json_decode($report->i_income_expense)->final_result/$mainbank->number;
                                    $mainbank->i_income_expense->percent += json_decode($report->i_income_expense)->percent/$mainbank->number;
                                    $mainbank->i_others->final_result += json_decode($report->i_others)->final_result/$mainbank->number;
                                    $mainbank->rate += json_decode($report->inspeksiya)->percent/$mainbank->number;
                                }
                            }
                        }
                        $type = 'success';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.inspeksiya.inspeksiyamainbank', compact('title', 'type', 'message', 'monthyear', 'weight', 'mainbankss'));
                    }else{
                        $type = 'error';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.inspeksiya.inspeksiyamainbank', compact('title', 'type', 'message', 'monthyear', 'weight'));
                    }
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.inspeksiya.inspeksiyamainbank', compact('title', 'type', 'message', 'monthyear', 'weight'));
                } 
            }else{
                $type = 'error';
                $message = trans('app.Weights for you selected date is not found please add weight and try again');
                return view('report.inspeksiya.inspeksiyamainbank', compact('title', 'type', 'message', 'monthyear'));
            }
             
        }else{
            return view('report.inspeksiya.inspeksiyamainbank', compact('title', 'monthyear', 'weight'));
        }
    }

    public function mainbank_business_report(Request $request){
        $user = Auth::user();
        $position = get_position($user);
        $title = trans('app.business rating monthly in mainbanks');
        $monthyear = $request->get('monthyear');
        // $currentmonth = date('m');
        // for ($b = 1; $b < $currentmonth; $b++){
        //     $gen_month = '2020-'.$b;
        //     $data = DB::table('data_2020')->where([['month', '=', $b], ['year', '=', 2020]])->get()->toArray();
        //     inspeksiya_report($gen_month, $data);
        //     business_report($gen_month, $data);
        //     currency_report($gen_month, $data);
        //     cash_report($gen_month, $data);
        //     ijro_report($gen_month, $data);
        //     all_report($gen_month, $data);
        // }
            
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){
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


            $month = intval(date('m', strtotime($monthyear)));
            $year = date('Y', strtotime($monthyear));
            // $data = DB::table('data_2020')->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // inspeksiya_report($monthyear, $data);
            // business_report($monthyear, $data);
            // currency_report($monthyear, $data);
            // cash_report($monthyear, $data);
            // ijro_report($monthyear, $data);
            // all_report($monthyear, $data);
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            if(!empty($weight)){
                $data_table = 'data_'.$year;
                $report_table = 'report_'.$year;
                $check = Schema::hasTable($report_table);
                if($check){
                    $reports = DB::table($report_table)->
                    select(
                        $report_table.'.*',
                        'banks.short_name as name',
                        'banks.mainbank_id'
                    )->
                    join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                    where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]])->orderBy($report_table.'.rate', 'desc')->
                    get()->toArray();
                    //print_r($reports);
                    if(!empty($reports)){
                        if($month == 1){
                            $last_month = 12;
                            $last_year = $year-1;
                            $last_report_table = 'report_'.$last_year;
                        }else{
                            $last_month = $month - 1;
                            $last_year = $year;
                            $last_report_table = 'report_'.$last_year;
                        }
                        $check = Schema::hasTable($last_report_table);
                        if($check){
                            $last_reports = DB::table($last_report_table)->where([['month', '=', $last_month], ['year', '=', $last_year]])->orderBy('rate', 'desc')->get()->toArray();
                            if(!empty($last_reports) && !empty($reports)){
                                $count = 1;
                                foreach($reports as $report){
                                    $percent = 0;
                                    $rate_diff = 0;
                                    $last_count = 1;
                                    foreach($last_reports as $last_report){
                                        if($report->mfo_id == $last_report->mfo_id){
                                            $percent = number_format(($report->rate-$last_report->rate??0), 2);
                                            $rate_diff = $last_count - $count;
                                        }
                                        $last_count++;
                                    }
                                    $report->rate_percent = $percent;
                                    $report->rate_diff = $rate_diff;
                                    $count++;
                                }
                            }
                        }
                        foreach ($mainbankss as $key => $mainbank) {
                            $mainbank->b_past = new stdClass;
                            $mainbank->b_kontur = new stdClass;
                            $mainbank->b_family = new stdClass;
                            $mainbank->b_guarantee = new stdClass;
                            $mainbank->b_home = new stdClass;
                            $mainbank->b_execution = new stdClass;
                            $mainbank->b_m_report = new stdClass;
                            $mainbank->b_past->final_result = 0;
                            $mainbank->b_past->percent = 0;
                            $mainbank->b_family->final_result = 0;
                            $mainbank->b_family->percent = 0;
                            $mainbank->b_kontur->final_result = 0;
                            $mainbank->b_kontur->percent = 0;
                            $mainbank->b_guarantee->final_result = 0;
                            $mainbank->b_guarantee->percent = 0;
                            $mainbank->b_home->final_result = 0;
                            $mainbank->b_home->percent = 0;
                            $mainbank->b_execution->final_result = 0;
                            $mainbank->b_execution->percent = 0;
                            $mainbank->b_m_report->final_result = 0;
                            $mainbank->b_m_report->percent = 0;
                            $mainbank->rate = 0;
                            foreach ($reports as  $report) {
                                if($report->mainbank_id == $mainbank->mainbank_id){
                                    $mainbank->b_past->final_result += json_decode($report->b_past)->final_result/$mainbank->number;
                                    $mainbank->b_past->percent += json_decode($report->b_past)->percent/$mainbank->number;
                                    $mainbank->b_kontur->final_result += json_decode($report->b_kontur)->final_result/$mainbank->number;
                                    $mainbank->b_kontur->percent += json_decode($report->b_kontur)->percent/$mainbank->number;
                                    $mainbank->b_family->final_result += json_decode($report->b_family)->final_result/$mainbank->number;
                                    $mainbank->b_family->percent += json_decode($report->b_family)->percent/$mainbank->number;
                                    $mainbank->b_guarantee->final_result += json_decode($report->b_guarantee)->final_result/$mainbank->number;
                                    $mainbank->b_guarantee->percent += json_decode($report->b_guarantee)->percent/$mainbank->number;
                                    $mainbank->b_home->final_result += json_decode($report->b_home)->final_result/$mainbank->number;
                                    $mainbank->b_home->percent += json_decode($report->b_home)->percent/$mainbank->number;
                                    $mainbank->b_execution->final_result += json_decode($report->b_execution)->final_result/$mainbank->number;
                                    $mainbank->b_execution->percent += json_decode($report->b_execution)->percent/$mainbank->number;
                                    $mainbank->b_m_report->final_result += json_decode($report->b_m_report)->final_result/$mainbank->number;
                                    $mainbank->b_m_report->percent += json_decode($report->b_m_report)->percent/$mainbank->number;
                                    $mainbank->rate += json_decode($report->business)->percent/$mainbank->number;
                                }
                            }
                        }
                        $type = 'success';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.business.businessmainbank', compact('title', 'type', 'message', 'monthyear', 'weight', 'mainbankss'));
                    }else{
                        $type = 'error';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.business.businessmainbank', compact('title', 'type', 'message', 'monthyear', 'weight'));
                    }
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.business.businessmainbank', compact('title', 'type', 'message', 'monthyear', 'weight'));
                } 
            }else{
                $type = 'error';
                $message = trans('app.Weights for you selected date is not found please add weight and try again');
                return view('report.business.businessmainbank', compact('title', 'type', 'message', 'monthyear'));
            }
             
        }else{
            return view('report.business.businessmainbank', compact('title', 'monthyear', 'weight'));
        }
    }

    public function mainbank_currency_report(Request $request){
        $user = Auth::user();
        $position = get_position($user);
        $title = trans('app.business rating monthly in mainbanks');
        $monthyear = $request->get('monthyear');
        // $currentmonth = date('m');
        // for ($b = 1; $b < $currentmonth; $b++){
        //     $gen_month = '2020-'.$b;
        //     $data = DB::table('data_2020')->where([['month', '=', $b], ['year', '=', 2020]])->get()->toArray();
        //     inspeksiya_report($gen_month, $data);
        //     business_report($gen_month, $data);
        //     currency_report($gen_month, $data);
        //     cash_report($gen_month, $data);
        //     ijro_report($gen_month, $data);
        //     all_report($gen_month, $data);
        // }
            
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){
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


            $month = intval(date('m', strtotime($monthyear)));
            $year = date('Y', strtotime($monthyear));
            // $data = DB::table('data_2020')->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            // inspeksiya_report($monthyear, $data);
            // business_report($monthyear, $data);
            // currency_report($monthyear, $data);
            // cash_report($monthyear, $data);
            // ijro_report($monthyear, $data);
            // all_report($monthyear, $data);
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            if(!empty($weight)){
                $data_table = 'data_'.$year;
                $report_table = 'report_'.$year;
                $check = Schema::hasTable($report_table);
                if($check){
                    $reports = DB::table($report_table)->
                    select(
                        $report_table.'.*',
                        'banks.short_name as name',
                        'banks.mainbank_id'
                    )->
                    join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                    where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]])->orderBy($report_table.'.rate', 'desc')->
                    get()->toArray();
                    //print_r($reports);
                    if(!empty($reports)){
                        if($month == 1){
                            $last_month = 12;
                            $last_year = $year-1;
                            $last_report_table = 'report_'.$last_year;
                        }else{
                            $last_month = $month - 1;
                            $last_year = $year;
                            $last_report_table = 'report_'.$last_year;
                        }
                        $check = Schema::hasTable($last_report_table);
                        if($check){
                            $last_reports = DB::table($last_report_table)->where([['month', '=', $last_month], ['year', '=', $last_year]])->orderBy('rate', 'desc')->get()->toArray();
                            if(!empty($last_reports) && !empty($reports)){
                                $count = 1;
                                foreach($reports as $report){
                                    $percent = 0;
                                    $rate_diff = 0;
                                    $last_count = 1;
                                    foreach($last_reports as $last_report){
                                        if($report->mfo_id == $last_report->mfo_id){
                                            $percent = number_format(($report->rate-$last_report->rate??0), 2);
                                            $rate_diff = $last_count - $count;
                                        }
                                        $last_count++;
                                    }
                                    $report->rate_percent = $percent;
                                    $report->rate_diff = $rate_diff;
                                    $count++;
                                }
                            }
                        }
                        foreach ($mainbankss as $key => $mainbank) {
                            $mainbank->c_check = new stdClass;
                            $mainbank->c_phone = new stdClass;
                            $mainbank->c_m_report = new stdClass;
                            $mainbank->c_execution = new stdClass;
                            $mainbank->c_check->final_result = 0;
                            $mainbank->c_check->percent = 0;
                            $mainbank->c_m_report->final_result = 0;
                            $mainbank->c_m_report->percent = 0;
                            $mainbank->c_phone->final_result = 0;
                            $mainbank->c_phone->percent = 0;
                            $mainbank->c_execution->final_result = 0;
                            $mainbank->c_execution->percent = 0;
                            $mainbank->rate = 0;
                            foreach ($reports as  $report) {
                                if($report->mainbank_id == $mainbank->mainbank_id){
                                    $mainbank->c_check->final_result += json_decode($report->c_check)->final_result/$mainbank->number;
                                    $mainbank->c_check->percent += json_decode($report->c_check)->percent/$mainbank->number;
                                    $mainbank->c_phone->final_result += json_decode($report->c_phone)->final_result/$mainbank->number;
                                    $mainbank->c_phone->percent += json_decode($report->c_phone)->percent/$mainbank->number;
                                    $mainbank->c_m_report->final_result += json_decode($report->c_m_report)->final_result/$mainbank->number;
                                    $mainbank->c_m_report->percent += json_decode($report->c_m_report)->percent/$mainbank->number;
                                    $mainbank->c_execution->final_result += json_decode($report->c_execution)->final_result/$mainbank->number;
                                    $mainbank->c_execution->percent += json_decode($report->c_execution)->percent/$mainbank->number;
                                    $mainbank->rate += json_decode($report->currency)->percent/$mainbank->number;
                                }
                            }
                        }
                        $type = 'success';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.currency.currencymainbank', compact('title', 'type', 'message', 'monthyear', 'weight', 'mainbankss'));
                    }else{
                        $type = 'error';
                        $message = trans('app.data not found at date that you entered');
                        return view('report.currency.currencymainbank', compact('title', 'type', 'message', 'monthyear', 'weight'));
                    }
                }else{
                    $type = 'error';
                    $message = trans('app.data not found at date that you entered');
                    return view('report.currency.currencymainbank', compact('title', 'type', 'message', 'monthyear', 'weight'));
                } 
            }else{
                $type = 'error';
                $message = trans('app.Weights for you selected date is not found please add weight and try again');
                return view('report.currency.currencymainbank', compact('title', 'type', 'message', 'monthyear'));
            }
             
        }else{
            return view('report.currency.currencymainbank', compact('title', 'monthyear', 'weight'));
        }
    }

    public function rating_in(Request $request, $department, $type){
        $title = trans('app.'.$department.' '.$type.' report');
        $mfo_id = $request->get('mfo');
        $monthyear = $request->get('monthyear');
        $weight = DB::table('weight_of_reports')->where([['year', '=', date('Y')], ['month', '<=', date('m')]])->orderBy('month', 'desc')->get()->first();
        if(!empty($monthyear)){
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            $data_table = 'data_'.$year;
            if(checkTable('data', $year)){
                $reports = DB::table($data_table)->
                select(
                    $data_table.'.*',
                    $data_table.'.id',
                    $data_table.'.mfo_id',
                    $data_table.'.bank_id',
                    'banks.name'
                )->
                leftjoin('banks', 'banks.id', '=', $data_table.'.bank_id')->
                where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]]);
                if(!empty($mfo_id)){
                    $reports = $reports->where('banks.mfo_id', '=', $mfo_id);
                }
                $reports = $reports->get()->toArray();
                if(!empty($reports)){
                    $message = "Tayyor";
                    return view('report.'.$department.'.'.$type.'', compact('reports', 'title', 'type', 'message', 'monthyear', 'weight'));
                }else{
                    $message = trans('app.data not found at date that you entered');
                    return view('report.'.$department.'.'.$type.'', compact('reports', 'title', 'type', 'message', 'monthyear', 'weight'));
                }
            }else{
                $message = trans('app.data not found at date that you entered');
                return view('report.'.$department.'.'.$type.'', compact('reports', 'title', 'type', 'message', 'monthyear', 'weight'));
            } 
        }else{
            return view('report.'.$department.'.'.$type.'', compact('title', 'monthyear', 'weight'));
        }
    }

    public function loans(Request $request){
        $title = trans('app.loan report');
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
        $s = $request->get('search');
        $fillial = $request->get('fillial');
        $mainbank = $request->get('mainbank');
        $status = $request->get('status');
        $goal_code = $request->get('goal_code');
        $city = $request->get('city');
        $monthyear = $request->get('monthyear');
        $activity_code = $request->get('activity_code');

        $current_month = intval(date('m'));
        $current_year = intval(date('Y'));
        $portfolio_table = "portfolio_".$current_year;
        $check = Schema::hasTable('portfolio_'.$current_year);
        if($check){
            $check_existing = DB::table($portfolio_table)->orderBy('month', 'desc')->orderBy('id', 'desc')->get()->first();
        }else{
            $portfolio_table = "portfolio_".($current_year - 1);
            $check_existing = DB::table($portfolio_table)->orderBy('month', 'desc')->orderBy('id', 'desc')->get()->first();
        }
        $current_month = $check_existing->month;
        $current_year = $check_existing->year;
        if(!empty($monthyear)){
            $current_month = intval(date('m', strtotime($monthyear)));
            $current_year = intval(date('Y', strtotime($monthyear)));
            $portfolio_table = "portfolio_".$current_year;
        }
        $credits = DB::table($portfolio_table)->
        select(
            $portfolio_table.'.*',
            'banks.short_name as bank_name'
        )->
        join('banks', 'banks.id', '=', $portfolio_table.'.bank_id');
        if(!empty($s)){
            $search_query = array(
                $portfolio_table.'.client_sheet_number', 
                $portfolio_table.'.client_name', 
                $portfolio_table.'.client_inn_passport',
                'banks.name',
                'banks.mfo_id'
            );
            $credits = $credits->where(function($query) use($search_query, $s){
                foreach($search_query as $item_query){
                    $query = $query->orWhere($item_query, 'like', '%'.$s.'%');
                }
            }); 
        }
        $credits=$credits->where([[$portfolio_table.'.month', '=', $current_month], [$portfolio_table.'.year', '=', $current_year]]);
        if(!empty($fillial) && $fillial != 'all'){
            $credits = $credits->where($portfolio_table.'.bank_id', '=', $fillial);
        }
        if(!empty($activity_code) && $activity_code != 'all'){
            $credits = $credits->where($portfolio_table.'.activity_code', '=', intval($activity_code));
        }
        if(!empty($goal_code) && $goal_code != 'all'){
            $credits = $credits->where($portfolio_table.'.goal_code', '=', intval($goal_code));
        }
        if(!empty($status)){
            $title = trans('app.problem loan report');
            $credits = $credits->where($portfolio_table.'.status', '=', $status);
        }
        if(!empty($mainbank) && $mainbank != "all"){
            $credits = $credits->where('banks.mainbank_id', '=', $mainbank);
        }
        if(!empty($city) && $city != "all"){
            $credits = $credits->where('banks.city_id', '=', $city);
        }
        $for_total = $credits->where('banks.mainbank_id', '!=', 38)->get()->toArray();
        $credits = $credits->where('banks.mainbank_id', '!=', 38)->latest()->paginate(100);
        $all_amount_equiv = 0;
        $all_remainder = 0;
        $all_debt_amount = 0;
        $all_out_of = 0;
        $all_backup = 0;
        $all_needed_backup = 0;
        if(!empty($for_total)){
            foreach($for_total as $portfolio_single){
                $portfolio_data = json_decode($portfolio_single->portfolio);
                if($portfolio_single->status == 'problem'){
                    $datetime2 = new DateTime(date('d-m-Y', strtotime($portfolio_data->out_of_date)));
                    $datetime1 = new DateTime(date('d-m-Y'));
                    $interval = $datetime1->diff($datetime2)->format('%a');
                    if($interval > 180){
                        $backup_needed = $portfolio_data->debt_amount*1;
                    }elseif($interval > 120 && $interval < 180){
                        $backup_needed = $portfolio_data->debt_amount*0.5;
                    }elseif($interval > 90 && $interval < 120){
                        $backup_needed = $portfolio_data->debt_amount*0.25;
                    }elseif($interval > 30 && $interval < 90){
                        $backup_needed = $portfolio_data->debt_amount*0.1;
                    }
                }else{
                    $backup_needed = 0;
                }
                $all_out_of += intval($portfolio_data->out_of);
                $all_amount_equiv += intval($portfolio_data->contract_amount_eqiuv);
                $all_remainder += intval($portfolio_data->remainder);
                $all_debt_amount += intval($portfolio_data->debt_amount);
                $all_backup += intval($portfolio_data->backup_created);
                $all_needed_backup += intval($backup_needed);
            }
        }
        if(!empty($s)){
            $credits->appends(['search'=>$s]);
        }
        if(!empty($status)){
            $credits->appends(['status'=>$status]);
        }
        if(!empty($monthyear)){
            $credits->appends(['monthyear' => $monthyear]);
        }
        if(!empty($request->get('fillial')) && $request->get('fillial') !='all'){
            $credits->appends(['fillial' => $request->get('fillial')]);
        }
        if(!empty($request->get('mainbank')) && $request->get('mainbank') !='all'){
            $credits->appends(['mainbank' => $request->get('mainbank')]);
        }
        if(!empty($request->get('activity_code')) && $request->get('activity_code') !='all'){
            $credits->appends(['activity_code' => $request->get('activity_code')]);
        }
        if(!empty($request->get('goal_code')) && $request->get('goal_code') !='all'){
            $credits->appends(['goal_code' => $request->get('goal_code')]);
        }
        if(!empty($request->get('goal_code')) && $request->get('goal_code') != 'all'){
            $goal_code = DB::table('goal_codes')->where('code', '=', $request->get('goal_code'))->get()->first();
        }
        if(!empty($city) && $city != 'all'){
            $city = DB::table('cities')->where('id', '=', $city)->get()->first();
        }
        if(!empty($request->get('activity_code')) && $request->get('activity_code') != 'all'){
            $activity_code = DB::table('activity_codes')->where('code', '=', $request->get('activity_code'))->get()->first();
        }
        if(!empty($request->get('fillial')) && $request->get('fillial') != "all"){
            $fillial = DB::table('banks')->where('id', '=', $request->get('fillial'))->get()->first();
        }
        $fillials = null;
        if(!empty($request->get('mainbank')) && $request->get('mainbank') != "all"){
            $mainbank = DB::table('mainbanks')->where('id', '=', $request->get('mainbank'))->get()->first();
            $fillials = DB::table('banks')->where('mainbank_id', '=', $request->get('mainbank'))->get()->toArray();
        }
        return view('report.credits.credits', 
        compact(
            'current_month',
            'current_year',
            'all_amount_equiv',
            'all_remainder',
            'all_debt_amount',
            'all_backup',
            'all_needed_backup',
            'all_out_of',
            'credits', 
            'title', 
            'activities', 
            'goal_codes', 
            'activity_code', 
            'goal_code', 
            'mainbanks', 
            'regions', 
            'cities', 
            'status',
            'fillials',
            'monthyear', 
            'fillial',
            'mainbank', 
            'city', 
            's'
        ));
    }

    public function report(){
        return 'hi';
    }
}
