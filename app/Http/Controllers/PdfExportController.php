<?php 


namespace App\Http\Controllers;
use DB;
use PDF;
use PDO;
use URL;
use auth;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class PdfExportController extends Controller
{
    public function monthly_rating_all(Request $request)
    {
    	ini_set ('max_execution_time', 260000);
        $monthyear = $request->get('monthyear');
        $month = intval(date('m', strtotime($monthyear)));
        $year = date('Y', strtotime($monthyear));
        $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
        $data_table = 'data_'.$year;
        $report_table = 'report_'.$year;
        $date_title = trans('app.month'.$month)."-".$year;
        $title = trans('app.all rating monthly');
        $reports = DB::table($report_table)->
        select(
            $report_table.'.id',
            $report_table.'.mfo_id',
            $report_table.'.bank_id',
            $report_table.'.month',
            $report_table.'.year',
            $report_table.'.created_at',
            $report_table.'.updated_at',
            $report_table.'.weight_id',
            $report_table.'.rate',
            $report_table.'.inspeksiya',
            $report_table.'.business',
            $report_table.'.cash',
            $report_table.'.currency',
            $report_table.'.ijro',
            'banks.short_name as name'
        )->
        join('banks', 'banks.id', '=', $report_table.'.bank_id')->
        where([['month', '=', $month], ['year', '=', $year], ['banks.mainbank_id', '!=', 38]])->orderBy($report_table.'.rate', 'desc')->
        get()->toArray();
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
        }
        $pdf = PDF::loadView('export-pdf.pdf-all', compact('reports', 'title', 'date_title', 'weight'))->setPaper('A4', 'landscape');
        // download PDF file with download method
        return $pdf->download($title.".pdf");
    }

    public function inspeksiya_rating(Request $request){
        ini_set ('max_execution_time', 260000);
        $monthyear = $request->get('monthyear');
        $month = intval(date('m', strtotime($monthyear)));
        $year = date('Y', strtotime($monthyear));
        $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
        $data_table = 'data_'.$year;
        $report_table = 'report_'.$year;
        $date_title = trans('app.month'.$month)."-".$year;
        $title = trans('app.inspeksiya rating');
        $reports = DB::table($report_table)->
        select(
            $report_table.'.id',
            $report_table.'.mfo_id',
            $report_table.'.bank_id',
            $report_table.'.month',
            $report_table.'.year',
            $report_table.'.created_at',
            $report_table.'.updated_at',
            $report_table.'.weight_id',
            $report_table.'.inspeksiya',
            $report_table.'.i_out_of', 
            $report_table.'.i_work_lost',
            $report_table.'.i_likvid_credit',
            $report_table.'.i_likvid_active',
            $report_table.'.i_b_liability',
            $report_table.'.i_b_liability_demand',
            $report_table.'.i_net_profit',
            $report_table.'.i_active_likvid',
            $report_table.'.i_income_expense',
            $report_table.'.i_others',
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
        }
        $pdf = PDF::loadView('export-pdf.inspeksiya.inspeksiya', compact('reports', 'title', 'date_title', 'weight'))->setPaper('A4', 'landscape');
        // download PDF file with download method
        return $pdf->download($title.".pdf");
    }
    public function cash_rating(Request $request){
        ini_set ('max_execution_time', 260000);
        $monthyear = $request->get('monthyear');
        $month = intval(date('m', strtotime($monthyear)));
        $year = date('Y', strtotime($monthyear));
        $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
        $data_table = 'data_'.$year;
        $report_table = 'report_'.$year;
        $date_title = trans('app.month'.$month)."-".$year;
        $title = trans('app.cash rating');
        $reports = DB::table($report_table)->
        select(
            $report_table.'.id',
            $report_table.'.mfo_id',
            $report_table.'.bank_id',
            $report_table.'.month',
            $report_table.'.year',
            $report_table.'.created_at',
            $report_table.'.updated_at',
            $report_table.'.weight_id',
            $report_table.'.cash',
            $report_table.'.cash_tushum', 
            $report_table.'.cash_execution',
            $report_table.'.cash_qaytish',
            $report_table.'.cash_m_report',
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
        }
        $pdf = PDF::loadView('export-pdf.cash.cash', compact('reports', 'title', 'date_title', 'weight'))->setPaper('A4', 'landscape');
        // download PDF file with download method
        return $pdf->download($title.".pdf");
    }
    
}