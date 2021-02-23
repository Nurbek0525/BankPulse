<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Bank;
use App\Headbank;
use App\Mainbank;
use App\Account_sheet;
use App\Cat_account_sheet;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Input;
use PDO;
use DateTime;
use DB;
use URL;
use Auth;
use Mail;
use App\Http\Controllers\excelImporter\SpreadsheetReader;
use App\Http\Controllers\excelImporter\Transliteration;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $position = get_position($user);
        $all_active_account_sheets = array(
            10100,10300,10500,10700,10800,10900,15800,15900,16100,16300,16400,16500,16600,16700,16800,16900,17100,17300,17400,17500,19900,11101,11103,11105,11195,11301,11301,11305,11309,11311,11315,
            11319,11395,11501,11505,11509,11511,11515,11519,11521,11701,11705,11801,11803,11805,11807,11809,11811,11813,11815,11897,11901,12101,12105,12109,12301,12305,12309,12401,12405,12409,12501,
            12502,12503,12504,12505,12509,12521,12601,12605,12609,12621,12701,12704,12405,12709,12801,12802,12803,12805,12809,12901,12904,12905,12909,12921,13001,13005,13009,13101,13104,13105,13109,
            13121,13201,13205,13209,13301,13305,13309,14301,14305,14401,14402,14403,14405,14501,14505,14701,14705,14801,14809,14901,14902,14903,14905,14913,14921,15001,15005,15021,15101,15104,15105,
            15201,15205,15301,15304,15305,15321,15401,15405,15501,15504,15505,15521,15601,15603,15605,15607,15609,15611,15613,15615,15617,15619,15701,15703,15705,15707
        );

        $all_credit_account_sheets = array(
            11101,11103,11105,11195,11301,11305,11309,11311,11315,11319,11395,11501,11505,11509,11511,11515,11519,11521,11701,11705,11801,11803,11805,11807,11809,11811,11813,11815,11897,11901,12101,
            12105,12109,12301,12305,12309,12401,12405,12409,12501,12502,12503,12504,12505,12509,12521,12601,12605,12609,12621,12701,12704,12705,12709,12801,12802,12803,12805,12809,12901,12904,12905,
            12909,12921,13001,13005,13009,13101,13104,13105,13109,13121,13201,13205,13209,13301,13305,13309,14301,14305,14401,14402,14403,14405,14501,14505,14701,14705,14801,14809,14901,14902,14903,
            14905,14913,14921,15001,15005,15021,15101,15104,15105,15201,15205,15301,15304,15305,15321,15401,15405,15501,15504,15505,15521,15601,15603,15605,15607,15609,15611,15613,15615,15617,15619,
            15701,15703,15705,15707
        );

        $problem_credit_account_sheets = array(
            12405,12505,11103,11305,11505,11705,12105,12305,12605,12705,12805,12905,13005,13305,13105,15617,15701,15703,15705,15707
        );
        $losts_account_sheets = array(
            16300,16401,16405,16409,16413
        );

        $all_deposit_account_sheets = array(
            20200,20400,20600,22617,22618,22619,22620
        );
        $people_deposit_account_sheets = array(
            20206,20406,20606,22617,22618
        );

        $demand_deposit_account_sheets = array(20200);

        $active_likvids_account_sheets = array(
            10101,10102,10103,10105,10107,10111,10113,10196,10198,10301,16103
        );

        $monthly_income_account_sheets = array(
            40200,40400,40600,40700,40800,41001,41005,41009,41013,41017,41021,41025,41400,41600,41800,41900,42001,42005,42009,42100,42200,42300,42400,42500,42600,43100,43600,43700,43900,44001,44005,
            44100,44200,44300,44400,44500,44600,44700,44800,44900,45001,45003,45005,45007,45009,45011,45013,45015,45094,45100,45200,45400,45700,45800,45900
        );

        $monthly_expense_account_sheets = array(
            50100,50600,51100,51600,52100,52600,53100,54100,54200,54300,54900,55100,55300,55600,55700,55800,55900,56100,56200,56300,56400,56500,56600,56700,56800,56900
        );
        $title = 'Bank Pulse';

        $current_year = date('Y');
        $last_year = $current_year -1;
        $month = date('m');
        $region_id = 13;
        $current_data_table = 'balance_'.$current_year;
        $last_data_table = 'balance_'.$last_year;
        $current_sxema_table = 'sxema_'.$current_year;
        $last_sxema_table = 'sxema_'.$last_year;


        // Xozirgi yil boshidan shu oygacha bolgan Kredit qoyilmalari va muammoli creditlar
        $current_year_data = DB::table($current_data_table)->
        select(
            $current_data_table.'.*'
        )->
        join('banks', 'banks.id', '=', $current_data_table.'.bank_id')->
        where([['banks.region_id', '=', $region_id], [$current_data_table.'.month', '<', $month]])->orderBy('month')->get()->toArray();

        // Oxirgi oybalance
        $date_last_balance = DB::table($current_data_table)->
        select(
            $current_data_table.'.month',
            $current_data_table.'.year'
        )->
        join('banks', 'banks.id', '=', $current_data_table.'.bank_id')->
        where([['banks.region_id', '=', $region_id], [$current_data_table.'.month', '<', $month]])->groupBy('month', 'year')->orderBy('month', 'desc')->get()->first();
        // Xozirgi yil boshidan shu oygacha bolgan Kredit qoyilmalari va muammoli creditlar
        $current_year_sxema = DB::table($current_sxema_table)->
        select(
            $current_sxema_table.'.*'
        )->
        join('banks', 'banks.id', '=', $current_sxema_table.'.bank_id')->
        where([['banks.region_id', '=', $region_id], [$current_sxema_table.'.month', '<', $month]])->orderBy('month')->get()->toArray();
        // oxirgi oy sxema
        $date_last_sxema = DB::table($current_sxema_table)->
        select(
            $current_sxema_table.'.month',
            $current_sxema_table.'.year'
        )->
        join('banks', 'banks.id', '=', $current_sxema_table.'.bank_id')->
        where([['banks.region_id', '=', $region_id], [$current_sxema_table.'.month', '<', $month]])->groupBy('month', 'year')->orderBy('month', 'desc')->get()->first();

        // O'tgan yilning boshidan shu o'tgan yilning shu yilning shu oygacha bolgan Kredit qoyilmalari va muammoli creditlar
        $last_year_data = DB::table($last_data_table)->
        select(
            $last_data_table.'.*'
        )->
        join('banks', 'banks.id', '=', $last_data_table.'.bank_id')->
        where('banks.region_id', '=', $region_id)->orderBy('month')->get()->toArray();
        $last_year_sxema = DB::table($last_sxema_table)->
        select(
            $last_sxema_table.'.*'
        )->
        join('banks', 'banks.id', '=', $last_sxema_table.'.bank_id')->
        where('banks.region_id', '=', $region_id)->orderBy('month')->get()->toArray();

        $data = array(
            'data_current_monthyear' => array(),
            'sxema_current_monthyear' => array(),
            'sxema_current_month' => array(),
            'data_current_month' => array(),
            'data_current_a_credit' => array(),
            'data_current_p_credit' => array(),
            'data_current_actives' => array(),
            'data_current_deposit' => array(),
            'data_current_p_deposit' => array(),
            'data_current_a_likvid' => array(),
            'data_current_income' => array(),
            'data_current_expense' => array(),
            'data_current_kirim' =>array(),
            'data_current_chiqim' =>array(),


            'data_last_monthyear' => array(),
            'data_last_month' => array(),
            'sxema_last_monthyear' => array(),
            'sxema_last_month' => array(),
            'data_last_a_credit' => array(),
            'data_last_p_credit' => array(),
            'data_last_actives' => array(),
            'data_last_deposit' => array(),
            'data_last_p_deposit' => array(),
            'data_last_a_likvid' => array(),
            'data_last_income' => array(),
            'data_last_expense' => array(),
            'data_last_kirim' => array(),
            'data_last_chiqim' =>array()
        );
        for($h = 1; $h <= 12; $h++){
            array_push($data['data_last_monthyear'], trans('app.shortmonth'.$h)." ".$last_year);
            array_push($data['sxema_last_monthyear'], trans('app.shortmonth'.$h)." ".$last_year);

            array_push($data['sxema_current_monthyear'], trans('app.shortmonth'.$h)." ".$current_year);
            array_push($data['data_current_monthyear'], trans('app.shortmonth'.$h)." ".$current_year);

            if($h > intval($date_last_balance->month)){
                array_push($data['data_last_month'], $h);
                array_push($data['data_current_month'], $h);

                array_push($data['data_last_a_credit'], 0);
                array_push($data['data_last_p_credit'], 0);
                array_push($data['data_last_actives'], 0);
                array_push($data['data_last_deposit'], 0);
                array_push($data['data_last_p_deposit'], 0);
                array_push($data['data_last_a_likvid'], 0);
                array_push($data['data_last_income'], 0);
                array_push($data['data_last_expense'], 0);
            }else{
                array_push($data['data_last_month'], $h);
                array_push($data['data_current_month'], $h);
                array_push($data['data_last_a_credit'], 0);
                array_push($data['data_last_p_credit'], 0);
                array_push($data['data_last_actives'], 0);
                array_push($data['data_last_deposit'], 0);
                array_push($data['data_last_p_deposit'], 0);
                array_push($data['data_last_a_likvid'], 0);
                array_push($data['data_last_income'], 0);
                array_push($data['data_last_expense'], 0);

                array_push($data['data_current_a_credit'], 0);
                array_push($data['data_current_p_credit'], 0);
                array_push($data['data_current_actives'], 0);
                array_push($data['data_current_deposit'], 0);
                array_push($data['data_current_p_deposit'], 0);
                array_push($data['data_current_a_likvid'], 0);
                array_push($data['data_current_income'], 0);
                array_push($data['data_current_expense'], 0);
            }
            if($h > intval($date_last_sxema->month)){
                array_push($data['sxema_last_month'], $h);
                array_push($data['sxema_current_month'], $h);

                array_push($data['data_last_kirim'], 0);
                array_push($data['data_last_chiqim'], 0);
            }else{
                array_push($data['sxema_last_month'], $h);
                array_push($data['sxema_current_month'], $h);
                array_push($data['data_last_kirim'], 0);
                array_push($data['data_last_chiqim'], 0);
                array_push($data['data_current_kirim'], 0);
                array_push($data['data_current_chiqim'], 0);
            }
        }
        
        foreach ($current_year_data as $current_data) {
            if(in_array($current_data->month, $data['data_current_month'])){
                foreach ($data['data_current_month'] as $col => $month) {
                    if($month == $current_data->month){
                        if(!empty($current_data->accounting)){

                            $all_active = 0; 
                            $all_credit = 0; 
                            $problem_credit = 0;
                            $losts = 0;
                            $all_deposit = 0; 
                            $people_deposit = 0; 
                            $demand_deposit = 0; 
                            $active_likvids = 0; 
                            $monthly_income = 0; 
                            $monthly_expense = 0;
                            $net_profit = 0;

                            $inserting_data = json_decode($current_data->accounting);
                            foreach ($inserting_data as $item) {
                                if(in_array($item->account_sheet_id, $all_active_account_sheets)){
                                    $all_active = $all_active + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $all_credit_account_sheets)){
                                    $all_credit = $all_credit + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $problem_credit_account_sheets)){
                                    $problem_credit = $problem_credit + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $losts_account_sheets)){
                                    $losts = $losts + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $all_deposit_account_sheets)){
                                    $all_deposit = $all_deposit + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $people_deposit_account_sheets)){
                                    $people_deposit = $people_deposit + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $demand_deposit_account_sheets)){
                                    $demand_deposit = $demand_deposit + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $active_likvids_account_sheets)){
                                    $active_likvids = $active_likvids + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $monthly_income_account_sheets)){
                                    $monthly_income = $monthly_income + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $monthly_expense_account_sheets)){
                                    $monthly_expense = $monthly_expense + $item->sum + $item->currency;
                                }
                            }
                            $data['data_current_a_credit'][$col] += $all_credit;
                            $data['data_current_p_credit'][$col] += $problem_credit;
                            $data['data_current_actives'][$col] += $all_active;
                            $data['data_current_deposit'][$col] += $all_deposit;
                            $data['data_current_p_deposit'][$col] += $people_deposit;
                            $data['data_current_a_likvid'][$col] += $active_likvids;
                            $data['data_current_income'][$col] += $monthly_income;
                            $data['data_current_expense'][$col] += $monthly_expense;

                        }
                    }
                }
            }
        }

        foreach ($last_year_data as $last_data) {
            if(in_array($last_data->month, $data['data_last_month'])){
                foreach ($data['data_last_month'] as $col => $month) {
                    if($month == $last_data->month){
                        if(!empty($last_data->accounting)){

                            $all_active = 0; 
                            $all_credit = 0; 
                            $problem_credit = 0;
                            $losts = 0;
                            $all_deposit = 0; 
                            $people_deposit = 0; 
                            $demand_deposit = 0; 
                            $active_likvids = 0; 
                            $monthly_income = 0; 
                            $monthly_expense = 0;
                            $net_profit = 0;

                            $inserting_data = json_decode($last_data->accounting);
                            foreach ($inserting_data as $item) {
                                if(in_array($item->account_sheet_id, $all_active_account_sheets)){
                                    $all_active = $all_active + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $all_credit_account_sheets)){
                                    $all_credit = $all_credit + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $problem_credit_account_sheets)){
                                    $problem_credit = $problem_credit + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $losts_account_sheets)){
                                    $losts = $losts + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $all_deposit_account_sheets)){
                                    $all_deposit = $all_deposit + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $people_deposit_account_sheets)){
                                    $people_deposit = $people_deposit + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $demand_deposit_account_sheets)){
                                    $demand_deposit = $demand_deposit + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $active_likvids_account_sheets)){
                                    $active_likvids = $active_likvids + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $monthly_income_account_sheets)){
                                    $monthly_income = $monthly_income + $item->sum + $item->currency;
                                }
                                if(in_array($item->account_sheet_id, $monthly_expense_account_sheets)){
                                    $monthly_expense = $monthly_expense + $item->sum + $item->currency;
                                }
                            }

                            $data['data_last_a_credit'][$col] += $all_credit;
                            $data['data_last_p_credit'][$col] += $problem_credit;
                            $data['data_last_actives'][$col] += $all_active;
                            $data['data_last_deposit'][$col] += $all_deposit;
                            $data['data_last_p_deposit'][$col] += $people_deposit;
                            $data['data_last_a_likvid'][$col] += $active_likvids;
                            $data['data_last_income'][$col] += $monthly_income;
                            $data['data_last_expense'][$col] += $monthly_expense;

                        }
                    }
                }
            }
        }

        foreach ($current_year_sxema as $current_sxema) {
            if(in_array($current_sxema->month, $data['sxema_current_month'])){
                foreach ($data['sxema_current_month'] as $col => $month) {
                    if($month == $current_sxema->month){
                        if(!empty($current_sxema->accounting)){

                            $income = 0; 
                            $expense = 0; 

                            $inserting_sxema = json_decode($current_sxema->accounting);
                            foreach ($inserting_sxema as $item) {
                                if($item->account_id >= 100 && $item->account_id <= 3200 && !empty($item)){
                                    $income += intval($item->amount);
                                }elseif($item->account_id >= 4001 && $item->account_id <= 5900 && !empty($item)){
                                    $expense += intval($item->amount);
                                }
                            }
                            $data['data_current_kirim'][$col] += $income;
                            $data['data_current_chiqim'][$col] += $expense;

                        }
                    }
                }
            }
        }

        foreach ($last_year_sxema as $last_sxema) {
            if(in_array($last_sxema->month, $data['sxema_last_month'])){
                foreach ($data['sxema_last_month'] as $col => $month) {
                    if($month == $last_sxema->month){
                        if(!empty($last_sxema->accounting)){

                            $income = 0; 
                            $expense = 0; 

                            $inserting_sxema = json_decode($last_sxema->accounting);
                            foreach ($inserting_sxema as $item) {
                                if($item->account_id >= 100 && $item->account_id <= 3200 && !empty($item)){
                                    $income += intval($item->amount);
                                }elseif($item->account_id >= 4001 && $item->account_id <= 5900 && !empty($item)){
                                    $expense += intval($item->amount);
                                }
                            }
                            $data['data_last_kirim'][$col] += $income;
                            $data['data_last_chiqim'][$col] += $expense;

                        }
                    }
                }
            }
        }

        $top_mainbanks = getTopMainbank();
        $top_data = getTopPortfolio();

        $top_safe_fillial = $top_data->data->top_fillial_portfolio->safe_portfolio->data;
        $top_problem_fillial = $top_data->data->top_fillial_portfolio->problem_portfolio->data;
        $top_safe_fillial_title = trans('app.'.$top_data->data->top_fillial_portfolio->safe_portfolio->title).$top_data->data->top_fillial_portfolio->safe_portfolio->text;
        $top_problem_fillial_title = trans('app.'.$top_data->data->top_fillial_portfolio->problem_portfolio->title).$top_data->data->top_fillial_portfolio->problem_portfolio->text;

        $top_safe_activity = $top_data->data->top_activity_portfolio->safe_portfolio->data;
        $top_problem_activity = $top_data->data->top_activity_portfolio->problem_portfolio->data;
        $top_safe_activity_species_title = trans('app.'.$top_data->data->top_activity_portfolio->safe_portfolio->title).$top_data->data->top_activity_portfolio->safe_portfolio->text;
        $top_problem_activity_species_title = trans('app.'.$top_data->data->top_activity_portfolio->problem_portfolio->title).$top_data->data->top_activity_portfolio->problem_portfolio->text;
        
        $top_safe_goal = $top_data->data->top_goal_portfolio->safe_portfolio->data;
        $top_problem_goal = $top_data->data->top_goal_portfolio->problem_portfolio->data;
        $top_safe_loan_species_title = trans('app.'.$top_data->data->top_goal_portfolio->safe_portfolio->title).$top_data->data->top_goal_portfolio->safe_portfolio->text;
        $top_problem_loan_species_title = trans('app.'.$top_data->data->top_goal_portfolio->problem_portfolio->title).$top_data->data->top_goal_portfolio->problem_portfolio->text;
        $date_last_top_port = $top_data->date_for_last_port." ".trans('app.last balance information');
        
        $whole_active = $data['data_current_actives'][array_key_last($data['data_current_actives'])];
        $whole_credits = $data['data_current_a_credit'][array_key_last($data['data_current_a_credit'])];
        $whole_problem_c = $data['data_current_p_credit'][array_key_last($data['data_current_p_credit'])];
        $whole_people_d = $data['data_current_p_deposit'][array_key_last($data['data_current_p_deposit'])];
        $whole_likvid_a = $data['data_current_a_likvid'][array_key_last($data['data_current_a_likvid'])];
        $whole_deposit = $data['data_current_deposit'][array_key_last($data['data_current_deposit'])];
        $last_active = $data['data_current_actives'][array_key_last($data['data_current_actives'])-1];
        $last_credits = $data['data_current_a_credit'][array_key_last($data['data_current_a_credit'])-1];
        $last_problem_c = $data['data_current_p_credit'][array_key_last($data['data_current_p_credit'])-1];
        $last_people_d = $data['data_current_p_deposit'][array_key_last($data['data_current_p_deposit'])-1];
        $last_likvid_a = $data['data_current_a_likvid'][array_key_last($data['data_current_a_likvid'])-1];
        $last_deposit = $data['data_current_deposit'][array_key_last($data['data_current_deposit'])-1];
        $active_percent = (($whole_active/($last_active==0?1:$last_active)) - 1)*100;
        $credit_percent = (($whole_credits/($last_credits==0?1:$last_credits)) - 1)*100;
        $problem_c_percent = (($whole_problem_c/($last_problem_c==0?1:$last_problem_c)) - 1)*100;
        $people_d_percent = (($whole_people_d/($last_people_d==0?1:$last_people_d)) - 1)*100;
        $deposit_percent = (($whole_deposit/($last_deposit==0?1:$last_deposit)) - 1)*100;
        $likvid_a_percent = (($whole_likvid_a/($last_likvid_a==0?1:$last_likvid_a)) - 1)*100;
        $data = json_encode($data);

        return view('home', compact(
            'title', 
            'data', 
            'top_mainbanks',
            'top_problem_fillial', 
            'top_safe_fillial',
            'top_safe_goal',
            'top_problem_goal',
            'top_safe_activity',
            'top_problem_activity',
            'whole_active', 
            'whole_credits',
            'whole_problem_c',
            'whole_people_d',
            'active_percent',
            'credit_percent',
            'problem_c_percent',
            'people_d_percent',
            'likvid_a_percent',
            'deposit_percent',
            'whole_deposit',
            'whole_likvid_a',
            'top_safe_fillial_title',
            'top_problem_fillial_title',
            'top_safe_activity_species_title',
            'top_problem_activity_species_title',
            'top_safe_loan_species_title',
            'top_problem_loan_species_title',
            'date_last_balance',
            'date_last_sxema',
            'date_last_top_port'
        ));
    }
}
