<?php

namespace App\Http\Controllers;

use DB;
use PDO;
use URL;
use Auth;
use Mail;
use App\Bank;
use App\User;
use DateTime;
use App\Headbank;
use App\Mainbank;
use App\Goal_code;
use App\Account_sheet;
use App\Activity_code;
use App\Http\Requests;
use App\Sxema_account;
use App\Jobs\CashRating;
use App\Jobs\IjroRating;
use App\Cat_account_sheet;
use App\Jobs\CurrencyRating;
use App\Jobs\GenerateRating;
require('excelImporter/excel_reader2.php');
use Illuminate\Http\Request;
use App\Jobs\BusinessRating;
use Illuminate\Support\Carbon;
use App\Event\GenerateRatingEvent;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\excelImporter\Transliteration;
use App\Http\Controllers\excelImporter\SpreadsheetReader;

class ExcelImporterController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function fillial_add(Request $request){
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[0];
                        $signofbank = $row[1];
                        $mainbankname = $row[2];
                        $fillialname = $row[3];
                        $fillialaddress = $row[4];
                        $started_at = $row[5];
                        $edited_at = $row[6];
                        $regionname = $row[7];
                        $cityname = $row[8];
                        $districtname = $row[9];
                        $stir = $row[10];
                        $web_site = $row[11];
                        $location = $row[12];
                        $allrows++;
                        if(!empty($mfo_id)){
                            $mainbank = DB::table('mainbanks')->
                            where('name', '=', $mainbankname)->get()->first();
                            if(empty($mainbank)){
                                $row[$lastColumn] = trans('app.bank mainbank not found');
                                $errorrows[] = $row;
                            }else{
                                $region = DB::table('regions')->
                                where('name', '=', $regionname)->get()->first();
                                if(empty($region)){
                                    $row[$lastColumn] = trans('app.bank region not found');
                                    $errorrows[] = $row;
                                }else{
                                    $district = DB::table('cities')->
                                    where('name', '=', $districtname)->
                                    orWhere('name', '=', $cityname)->get()->first();
                                    if(empty($district)){
                                        $row[$lastColumn] = trans('app.bank city not found');
                                        $errorrows[] = $row;
                                    }else{
                                        $bank = new Bank;
                                        $bank->mainbank_id = $mainbank->id;
                                        $bank->region_id = $region->id;
                                        $bank->city_id = $district->id;
                                        $bank->mfo_id = $mfo_id;
                                        $bank->bank_code = $signofbank;
                                        $bank->name = $fillialname;
                                        $bank->address = $fillialaddress;
                                        $bank->started_at = $started_at;
                                        $bank->edited_at = $edited_at;
                                        $bank->stir_inn = $stir;
                                        $bank->location = $location;
                                        $bank->web_site = $web_site;
                                        $bank->save();
                                        $successrows++;
                                    }
                                }
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $title = trans('app.bank excel');
                return view('excel.bank', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.bank excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.bank', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.bank excel');
            return view('excel.bank', compact('title'));
        }
    }

    public function account_sheet_add(Request $request){
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $cats = array();
                for($i = 10100; $i < 100000; $i+=100) {
                    array_push($cats, $i);
                }
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                for($i=0; $i < $sheetCount; $i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $account_id = $row[0];
                        $account_name = $row[1];
                        $allrows++;
                        if(!empty($account_id)){
                            if(in_array($account_id, $cats)){
                                $cat_account = DB::table('cat_account_sheets')->where('account_id', '=', $account_id)->get()->first();
                                if(!empty($cat_account)){
                                    if(empty($cat_account->name)){
                                        $cat_account = Cat_account_sheet::find($cat_account->id);
                                        $cat_account->account_id = $account_id;
                                        $cat_account->name = $account_name;
                                        $cat_account->save();
                                    }else{
                                        $row[$lastColumn] = trans('app.account sheet regged');
                                        $errorrows[] = $row;
                                    }
                                }else{
                                    $cat_account = new Cat_account_sheet;
                                    $cat_account->name = $account_name;
                                    $cat_account->account_id = $account_id;
                                    $cat_account->save();

                                }
                            }else{
                                foreach ($cats as $key => $value) {
                                    if($account_id > $value && $account_id < $cats[$key+1]){
                                        $cat_account = DB::table('cat_account_sheets')->where('account_id', '=', $value)->get()->first();
                                        if(!empty($cat_account)){
                                            $account = DB::table('account_sheets')->
                                                where('account_id', '=', $account_id)->get()->first();
                                            if(!empty($account)){
                                                if(empty($account->name)){
                                                    $account = Account_sheet::find($account->id);
                                                    $account->account_id = $account_id;
                                                    $account->name = $account_name;
                                                    $account->cat_id = $cat_account->id;
                                                    $account->save();
                                                }else{
                                                    $row[$lastColumn] = trans('app.account sheet regged');
                                                    $errorrows[] = $row;
                                                }
                                            }else{
                                                $account = new Account_sheet;
                                                $account->account_id = $account_id;
                                                $account->name = $account_name;
                                                $account->cat_id = $cat_account->id;
                                                $account->save();
                                                $successrows++;
                                            }
                                        }else{
                                            $row[$lastColumn] = trans('app.not found account in category');
                                            $errorrows[] = $row;
                                        }
                                    }
                                }
                            }
                        }else{
                            $row[$lastColumn] = trans('app.not found account');
                            $errorrows[] = $row;
                        }
                    }
                }
                $title = trans('app.account sheet excel');
                return view('excel.account', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.account sheet excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.account', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.account sheet excel');
            return view('excel.account', compact('title'));
        }
    }

    public function activity_add(Request $request){
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data <= 2){
                            continue;
                        }
                        $activity_name = $row[1];
                        $activity_code = intval($row[0]);
                        $allrows++;
                        $activity = DB::table('activity_codes')->where('code', '=', $activity_code)->get()->first();
                        if(empty($activity)){
                            $code = new Activity_code;
                            $code->code = $activity_code;
                            $code->name = $activity_name;
                            $code->save();
                            $successrows++;
                        }
                    }
                }
                $title = trans('app.activity code excel');
                return view('excel.activity', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.activity excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.activity', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.activity excel');
            return view('excel.activity', compact('title'));
        }
    }

    public function cash_m_report(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = (empty($row[3]))?0:$row[3];
                        $poor_quality = (empty($row[4]))?0:$row[4];
                        $delayed = (empty($row[5]))?0:$row[5];
                        $not_provided = (empty($row[6]))?0:$row[6];
                        $percent = $exist_case - ($poor_quality+$delayed+$not_provided);
                        $final_result = (($exist_case == 0)?0:(($percent/$exist_case)*100))*($weight->cash_m_report/100);
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'cash_m_report';
                                $cash_m_report = array(
                                    'exist_case' => $exist_case,
                                    'poor_quality' => $poor_quality,
                                    'delayed' => $delayed,
                                    'not_provided' => $not_provided
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $cash_m_report);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new CashRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.cash m report excel');
                return view('excel.cash.hisobot', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.cash m report excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.cash.hisobot', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.cash m report excel');
            return view('excel.cash.hisobot', compact('title'));
        }
    }

    public function cash_execution(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = (empty($row[3]))?0:intval($row[3]);
                        $poor_quality = (empty($row[4]))?0:intval($row[4]);
                        $delayed = (empty($row[5]))?0:intval($row[5]);
                        $not_provided = (empty($row[6]))?0:intval($row[6]);
                        $percent = $exist_case - ($poor_quality+$delayed+$not_provided);
                        $final_result = $percent*$weight->cash_execution/100;
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'cash_execution';
                                $cash_execution = array(
                                    'exist_case' => $exist_case,
                                    'poor_quality' => $poor_quality,
                                    'delayed' => $delayed,
                                    'not_provided' => $not_provided
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $cash_execution);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new CashRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.cash execution excel');
                return view('excel.cash.execution', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.cash execution excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.cash.execution', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.cash execution excel');
            return view('excel.cash.execution', compact('title'));
        }
    }

    public function i_others(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = intval($row[2]);
                        $final_result = (empty($row[3]))?0:$row[3];
                        
                        
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'i_others';
                                $i_others = array(
                                    'final_result' => $final_result
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $i_others);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new GenerateRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.i others');
                return view('excel.inspeksiya.others', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.i others');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.inspeksiya.others', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.i others');
            return view('excel.inspeksiya.others', compact('title'));
        }
    }

    public function c_m_report(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = (empty($row[3]))?0:$row[3];
                        $poor_quality = (empty($row[4]))?0:$row[4];
                        $delayed = (empty($row[5]))?0:$row[5];
                        $not_provided = (empty($row[6]))?0:$row[6];
                        $percent = $exist_case - ($poor_quality+$delayed+$not_provided);
                        $final_result = (($exist_case == 0)?0:(($percent/$exist_case)*100))*($weight->c_m_report/100);
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'c_m_report';
                                $c_m_report = array(
                                    'exist_case' => $exist_case,
                                    'poor_quality' => $poor_quality,
                                    'delayed' => $delayed,
                                    'not_provided' => $not_provided
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $c_m_report);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new CurrencyRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.c m report excel');
                return view('excel.currency.monthly_report', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.c m report excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.currency.monthly_report', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.c m report excel');
            return view('excel.currency.monthly_report', compact('title'));
        }
    }

    public function c_vash(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = (empty($row[3]))?0:$row[3];
                        $vash = (empty($row[5]))?0:$row[5];
                        $punkt = (empty($row[6]))?0:$row[6];
                        $percent = ($exist_case == 0)?0:((($exist_case - ($vash + $punkt))/$exist_case)*100);
                        $final_result = $percent*($weight->c_check/100);
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'c_check';
                                $c_check = array(
                                    'exist_case' => $exist_case,
                                    'vash' => $vash,
                                    'punkt' => $punkt
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $c_check);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                // $month = date('m', strtotime($monthyear));
                // $year = date('Y', strtotime($monthyear));
                // $data = DB::table('data_'.$year)->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
                // currency_report($monthyear, $data);
                // all_report($monthyear, $data);
                $job = (new CurrencyRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.currency check excel');
                return view('excel.currency.check_vash', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.currency check excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.currency.check_vash', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.currency check excel');
            return view('excel.currency.check_vash', compact('title'));
        }
    }

    public function c_execution(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = (empty($row[3]))?0:$row[3];
                        $poor_quality = (empty($row[4]))?0:$row[4];
                        $delayed = (empty($row[5]))?0:$row[5];
                        $not_provided = (empty($row[6]))?0:$row[6];
                        $percent = $exist_case - ($poor_quality+$delayed+$not_provided);
                        $final_result = (($exist_case == 0)?0:($percent/$exist_case))*($weight->c_execution/100);
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'c_execution';
                                $c_execution = array(
                                    'exist_case' => $exist_case,
                                    'poor_quality' => $poor_quality,
                                    'delayed' => $delayed,
                                    'not_provided' => $not_provided
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $c_execution);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new CurrencyRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.cash execution excel');
                return view('excel.currency.execution', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.cash execution excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.currency.execution', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.cash execution excel');
            return view('excel.currency.execution', compact('title'));
        }
    }

    public function c_phone(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = (empty($row[3]))?0:$row[3];
                        $currency = (empty($row[4]))?0:$row[4];
                        $percent = $exist_case - $currency;
                        $final_result = (($exist_case == 0)?0:(($percent/$exist_case)*100))*($weight->c_phone/100);
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'c_phone';
                                $c_phone = array(
                                    'exist_case' => $exist_case,
                                    'currency' => $currency
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $c_phone);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new CurrencyRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.currency phone excel');
                return view('excel.currency.phone', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.currency phone excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.currency.phone', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.currency phone excel');
            return view('excel.currency.phone', compact('title'));
        }
    }

    public function b_home(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = (empty($row[3]))?0:$row[3];
                        $credit_50 = (gettype($row[4]) == 'string')?null:$row[4];
                        $credit_50_60 = (gettype($row[5]) == 'string')?null:$row[5];
                        $credit_60_70 = (gettype($row[6]) == 'string')?null:$row[6];
                        $credit_70_80 = (gettype($row[7]) == 'string')?null:$row[7];
                        $credit_80_90 = (gettype($row[8]) == 'string')?null:$row[8];
                        $credit_90_100 = (gettype($row[9]) == 'string')?null:$row[9];
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'b_home';
                                $b_home = array(
                                    'exist_case' => $exist_case,
                                    'credit_50' => $credit_50,
                                    'credit_50_60' => $credit_50_60,
                                    'credit_60_70' => $credit_60_70,
                                    'credit_70_80' => $credit_70_80,
                                    'credit_80_90' => $credit_80_90,
                                    'credit_90_100' => $credit_90_100
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $b_home);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new BusinessRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.b home excel');
                return view('excel.business.home', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.b home excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.business.home', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.b home excel');
            return view('excel.business.home', compact('title'));
        }
    }

    public function b_kontur(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;


                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = (empty($row[3]))?0:$row[3];

                        $credit_50 = (gettype($row[4]) == 'string')?null:$row[4];
                        $credit_50_60 = (gettype($row[5]) == 'string')?null:$row[5];
                        $credit_60_70 = (gettype($row[6]) == 'string')?null:$row[6];
                        $credit_70_80 = (gettype($row[7]) == 'string')?null:$row[7];
                        $credit_80_90 = (gettype($row[8]) == 'string')?null:$row[8];
                        $credit_90_100 = (gettype($row[9]) == 'string')?null:$row[9];


                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'b_kontur';
                                $b_kontur = array(
                                    'exist_case' => $exist_case,
                                    'credit_50' => $credit_50,
                                    'credit_50_60' => $credit_50_60,
                                    'credit_60_70' => $credit_60_70,
                                    'credit_70_80' => $credit_70_80,
                                    'credit_80_90' => $credit_80_90,
                                    'credit_90_100' => $credit_90_100
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $b_kontur);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new BusinessRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.b kontur excel');
                return view('excel.business.kontur', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.b kontur excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.business.kontur', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.b kontur excel');
            return view('excel.business.kontur', compact('title'));
        }
    }

    public function b_family(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;

                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));

                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = intval((empty($row[3]))?0:$row[3]);

                        $credit_50 = (gettype($row[4]) == 'string')?null:$row[4];
                        $credit_50_60 = (gettype($row[5]) == 'string')?null:$row[5];
                        $credit_60_70 = (gettype($row[6]) == 'string')?null:$row[6];
                        $credit_70_80 = (gettype($row[7]) == 'string')?null:$row[7];
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'b_family';
                                $b_family = array(
                                    'exist_case' => $exist_case,
                                    'credit_50' => $credit_50,
                                    'credit_50_60' => $credit_50_60,
                                    'credit_60_70' => $credit_60_70,
                                    'credit_70_80' => $credit_70_80,
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $b_family);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new BusinessRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.b family excel');
                return view('excel.business.family', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.b family excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.business.family', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.b family excel');
            return view('excel.business.family', compact('title'));
        }
    }

    public function b_guarantee(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = intval((empty($row[3]))?0:$row[3]);
                        $nocredit = (gettype($row[4]) == 'string')?null:$row[4];
                        $threecredit = (gettype($row[5]) == 'string')?null:$row[5];
                        $sixcredit = (gettype($row[6]) == 'string')?null:$row[6];

                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'b_guarantee';
                                $b_guarantee = array(
                                    'exist_case' => $exist_case,
                                    'nocredit' => $nocredit,
                                    'threecredit' => $threecredit,
                                    'sixcredit' => $sixcredit
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $b_guarantee);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new BusinessRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.b guarantee excel');
                return view('excel.business.guarantee', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.b guarantee excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.business.guarantee', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.b guarantee excel');
            return view('excel.business.guarantee', compact('title'));
        }
    }

    public function b_past(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $lastthismonth = (gettype($row[3]) == 'string')?null:$row[3];
                        $thismonth = (gettype($row[4]) == 'string')?null:$row[4];
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'b_past';
                                $b_past = array(
                                    'lastthismonth' => $lastthismonth,
                                    'thismonth' => $thismonth
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $b_past);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new BusinessRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.b past excel');
                return view('excel.business.past', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.b past excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.business.past', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.b past excel');
            return view('excel.business.past', compact('title'));
        }
    }

    public function b_m_report(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = (empty($row[3]))?0:$row[3];
                        $poor_quality = (gettype($row[4]) == 'string')?null:$row[4];
                        $delayed = (gettype($row[5]) == 'string')?null:$row[5];
                        $not_provided = (gettype($row[6]) == 'string')?null:$row[6];
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'b_m_report';
                                $b_m_report = array(
                                    'exist_case' => 100,
                                    'poor_quality' => $poor_quality,
                                    'delayed' => $delayed,
                                    'not_provided' => $not_provided
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $b_m_report);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new BusinessRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.b m report excel');
                return view('excel.business.monthly_report', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.b m report excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.business.monthly_report', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.b m report excel');
            return view('excel.business.monthly_report', compact('title'));
        }
    }

    public function b_execution(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;

                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $exist_case = (empty($row[3]))?0:$row[3];
                        $poor_quality = (gettype($row[4]) == 'string')?null:$row[4];
                        $delayed = (gettype($row[5]) == 'string')?null:$row[5];
                        $not_provided = (gettype($row[6]) == 'string')?null:$row[6];
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'b_execution';
                                $b_execution = array(
                                    'exist_case' => $exist_case,
                                    'poor_quality' => $poor_quality,
                                    'delayed' => $delayed,
                                    'not_provided' => $not_provided
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $b_execution);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new BusinessRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.b execution excel');
                return view('excel.business.execution', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.b execution excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.business.execution', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.b execution excel');
            return view('excel.business.execution', compact('title'));
        }
    }

    public function ijro(Request $request){
        $weight = DB::table('weight_of_reports')->orderBy('id', 'desc')->get()->first();
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data == 0 || $data == 1){
                            continue;
                        }
                        $mfo_id = $row[2];
                        $meeting_execution = (gettype($row[3]) == 'string')?null:$row[3];
                        $letter_execution = (gettype($row[4]) == 'string')?null:$row[4];
                        $head_number = (gettype($row[5]) == 'string')?null:$row[5];
                        $people_qabul = (gettype($row[6]) == 'string')?null:$row[6];
                        $prime_number= (gettype($row[7]) == 'string')?null:$row[7];
                        $out_of_number = (gettype($row[8]) == 'string')?null:$row[8];
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(empty($bank)){
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                            }else{
                                $type = 'ijro';
                                $ijro = array(
                                    'meeting_execution' => $meeting_execution,
                                    'letter_execution' => $letter_execution,
                                    'head_number' => $head_number,
                                    'people_qabul' => $people_qabul,
                                    'prime_number' => $prime_number,
                                    'out_of_number' => $out_of_number
                                );
                                insert_data($bank->id, $mfo_id, $year, $month, $type, $ijro);
                                $successrows++;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                        }
                    }
                }
                $job = (new IjroRating($monthyear))->delay(5);
                $this->dispatch($job);
                $title = trans('app.ijro excel');
                return view('excel.ijro.ijro', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.ijro excel');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.ijro.ijro', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.ijro excel');
            return view('excel.ijro.ijro', compact('title'));
        }
    }

    public function balance(Request $request){
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
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                
                for($i = 0; $i < $sheetCount; $i++){
                    $mfo_name = array();
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if(empty($row[$lastColumn])){
                            continue;
                        }
                        foreach ($row as $item) {
                        	if(!empty($item)){
                        		array_push($mfo_name, $item);
                        	}
                        }
                        if(!empty($row[$lastColumn])){
                            $break_point = $data;
                        	break;
                        }
                    }
                    foreach ($mfo_name as $coll => $item) {
                        if($coll > 1 && $coll%2 == 0){
                            $mfo_id = intval(preg_replace('/[^0-9]/', '', $item));
                            if(!empty($mfo_id)){
                                $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                                if(empty($bank)){
                                    $row[$coll] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                    $errorrows[] = $row;
                                }else{
                                    $inserting_data = array();

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

                                    foreach ($excelreader as $data => $row) {
                                        $lastColumn = count($row)-1;
                                        if($data <= $break_point){
                                            continue;
                                        }
                                        $account_id = $row[0];
                                        if(!empty($account_id)){
                                            $sum = !empty($row[$coll])?$row[$coll]:0;
                                            $sum = ($sum < 0)?$sum*-1:$sum;
                                            $currency = !empty($row[$coll+1])?$row[$coll+1]:0;
                                            $currency = ($currency < 0)?$currency*-1:$currency;
                                            $account_data = array(
                                                'account_sheet_id' => $account_id,
                                                'sum' => $sum,
                                                'currency' => $currency
                                            );
                                            array_push($inserting_data, $account_data);

                                            if(in_array($account_id, $all_active_account_sheets)){
                                                $all_active = $all_active + $sum + $currency;
                                            }
                                            if(in_array($account_id, $all_credit_account_sheets)){
                                                $all_credit = $all_credit + $sum + $currency;
                                            }
                                            if(in_array($account_id, $problem_credit_account_sheets)){
                                                $problem_credit = $problem_credit + $sum + $currency;
                                            }
                                            if(in_array($account_id, $losts_account_sheets)){
                                                $losts = $losts + $sum + $currency;
                                            }
                                            if(in_array($account_id, $all_deposit_account_sheets)){
                                                $all_deposit = $all_deposit + $sum + $currency;
                                            }
                                            if(in_array($account_id, $people_deposit_account_sheets)){
                                                $people_deposit = $people_deposit + $sum + $currency;
                                            }
                                            if(in_array($account_id, $demand_deposit_account_sheets)){
                                                $demand_deposit = $demand_deposit + $sum + $currency;
                                            }
                                            if(in_array($account_id, $active_likvids_account_sheets)){
                                                $active_likvids = $active_likvids + $sum + $currency;
                                            }
                                            if(in_array($account_id, $monthly_income_account_sheets)){
                                                $monthly_income = $monthly_income + $sum + $currency;
                                            }
                                            if(in_array($account_id, $monthly_expense_account_sheets)){
                                                $monthly_expense = $monthly_expense + $sum + $currency;
                                            }
                                        }
                                        
                                    }
                                    $net_profit = $monthly_income - $monthly_expense;
                                    $losts = $net_profit - $losts;

                                    $type = 'i_out_of';
                                    $i_out_of = array(
                                        'allcredit' => $all_credit,
                                        'problemcredit' => $problem_credit
                                    );

                                    $bank_id = $bank->id;


                                    insert_data($bank_id, $mfo_id, $year, $month, $type, $i_out_of);

                                    $type = 'i_work_lost';
                                    $i_work_lost = array(
                                        'exist_case' => 100,
                                        'losts' => $losts,
                                        'net_profit' => $net_profit
                                    );
                                    insert_data($bank_id, $mfo_id, $year, $month, $type, $i_work_lost);

                                    $type = 'i_income_expense';
                                    $averexpense = $monthly_expense/$month;
                                    $averincome = $monthly_income/$month;
                                    $i_income_expense = array(
                                        'exist_case' => 50,
                                        'averexpense' => $averexpense,
                                        'averincome' => $averincome
                                    );
                                    insert_data($bank_id, $mfo_id, $year, $month, $type, $i_income_expense);

                                    $type = 'i_active_likvid';
                                    $i_active_likvid = array(
                                        'exist_case' => 10,
                                        'active_likvids' => $active_likvids,
                                        'allactive' => $all_active
                                    );
                                    insert_data($bank_id, $mfo_id, $year, $month, $type, $i_active_likvid);

                                    $type = 'i_net_profit';
                                    $profits = DB::table('data_'.$year)->where([['month', '!=', $month], ['bank_id', '=', $bank_id]])->get()->toArray();
                                    $all_profit = 0;
                                    if(!empty($profits)){
                                        foreach ($profits as $profit) {
                                            $profit = json_decode($profit->i_net_profit);
                                            if(!empty($profit->net_profit)){
                                                $all_profit = $all_profit + $profit->net_profit;
                                            }
                                        }
                                    }
                                    $aver_profit = ($net_profit + $all_profit)/$month;
                                    $i_net_profit = array(
                                        'exist_case' => 1,
                                        'net_profit' => $net_profit,
                                        'aver_profits' => $aver_profit,
                                        'allactive' => $all_active
                                    );
                                    insert_data($bank_id, $mfo_id, $year, $month, $type, $i_net_profit);

                                    $type = 'i_b_liability_demand';
                                    $i_b_liability_demand = array(
                                        'exist_case' => 30,
                                        'demanddeposit' => $demand_deposit,
                                        'alldeposit' => $all_deposit
                                    );
                                    insert_data($bank->id, $mfo_id, $year, $month, $type, $i_b_liability_demand);

                                    $type = 'i_b_liability';
                                    $i_b_liability = array(
                                        'exist_case' => 75,
                                        'peopledeposit' => $people_deposit,
                                        'alldeposit' => $all_deposit
                                    );
                                    insert_data($bank->id, $mfo_id, $year, $month, $type, $i_b_liability);

                                    $type = 'i_likvid_credit';
                                    $i_likvid_credit = array(
                                        'exist_case' => 80,
                                        'alldeposit' => $all_deposit,
                                        'allcredit' => $all_credit
                                    );
                                    insert_data($bank->id, $mfo_id, $year, $month, $type, $i_likvid_credit);

                                    $type = 'i_likvid_active';
                                    $i_likvid_active = array(
                                        'exist_case' => 75,
                                        'alldeposit' => $all_deposit,
                                        'allactive' => $all_active
                                    );
                                    insert_data($bank->id, $mfo_id, $year, $month, $type, $i_likvid_active);


                                    insert_balance($bank_id, $mfo_id, $year, $month, $inserting_data);
                                }
                            }
                        }
                    }
                }
                $job = (new GenerateRating($monthyear))->delay(5);
                $this->dispatch($job);

                $title = trans('app.Balance title');
                return view('excel.balance.balance', compact('errorrows', 'successrows', 'allrows', 'title'));
            }else{
                $title = trans('app.Balance title');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.balance.balance', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.Balance title');
            return view('excel.balance.balance', compact('title'));
        }
    }


    public function sxema(Request $request){

        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = $request->get('monthyear');
                $month = date('m', strtotime($monthyear));
                $year = date('Y', strtotime($monthyear));
                
                for($i = 0; $i < 1; $i++){
                    $sxema_accounts = array();
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if(empty($row[$lastColumn]) && empty($sxema_accounts)){
                            continue;
                        }
                        if(empty($sxema_accounts)){
                            foreach ($row as $col => $item) {
                                if($col >= 4){
                                    array_push($sxema_accounts, intval($item));
                                }                        
                            }
                        }
                        
                        $mfo_id = intval($row[1]);
                        $inserting_data = array();
                        $cash = array();
                        $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->get()->first();
                        if(!empty($bank) && !empty($sxema_accounts)){
                            $i = 0;
                            $income = 0;
                            $expense = 0;
                            foreach ($row as $col => $item) {
                                if($col >= 4){
                                    $new_data = array(
                                        'account_id' => $sxema_accounts[$i],
                                        'amount' => $item
                                    );
                                    if($sxema_accounts[$i] >= 100 && $sxema_accounts[$i] <= 3200 && !empty($item)){
                                        $income += intval($item);
                                    }elseif($sxema_accounts[$i] >= 4001 && $sxema_accounts[$i] <= 5900 && !empty($item)){
                                        $expense += intval($item);
                                    }
                                    array_push($inserting_data, $new_data);
                                    $i++;
                                }                     
                            }
                            insert_sxema($bank->id, $mfo_id, $year, $month, $inserting_data);
                        }
                    }
                }
                if(Schema::hasTable('sxema_'.$year)){
                    $last_year = $year - 1;
                    $last_month = $month - 1;
                    $lastyear_table = 'sxema_'.$last_year;
                    $currentyear_table = 'sxema_'.$year;
                    if(Schema::hasTable($lastyear_table)){
                        $banks = DB::table('banks')->where('region_work_id', '=', 13)->get()->toArray();
                        if(!empty($banks)){
                            foreach ($banks as $bank) {
                                $lastyear_sets = DB::table($lastyear_table)->
                                    where([['month', '=', $month], ['bank_id', '=', $bank->id]])->
                                get()->first();
                                $lastmonth_sets = DB::table($currentyear_table)->
                                    where([['month', '=', $last_month], ['bank_id', '=', $bank->id]])->
                                get()->first();
                                $thisyear_sets = DB::table($currentyear_table)->
                                    where([['month', '<=', $month], ['bank_id', '=', $bank->id]])->
                                get()->toArray();
                                $thismonth_sets = DB::table($currentyear_table)->
                                    where([['month', '=', $month], ['bank_id', '=', $bank->id]])->
                                get()->first();
    
                                if(!empty($lastyear_sets)){
                                    $lastyear_sets = json_decode($lastyear_sets->accounting);
                                    if(!empty($lastyear_sets)){
                                        $income = 0;
                                        $lastyear_income = 0;
                                        $expense = 0;
                                        foreach ($lastyear_sets as $item) {
                                            if($item->account_id >= 100 && $item->account_id <= 3200 && !empty($item)){
                                                $income += intval($item->amount);
                                            }elseif($item->account_id >= 4001 && $item->account_id <= 5900 && !empty($item)){
                                                $expense += intval($item->amount);
                                            }
                                        }
                                        $lastyear_income = $income;
                                        $lastyear_set = ($income/$expense)*100;
                                    }
                                }
    
                                if(!empty($lastmonth_sets)){
                                    $lastmonth_sets = json_decode($lastmonth_sets->accounting);
                                    if(!empty($lastmonth_sets)){
                                        $income = 0;
                                        $lastmonth_income = 0;
                                        $expense = 0;
                                        foreach ($lastmonth_sets as $item) {
                                            if($item->account_id >= 100 && $item->account_id <= 3200 && !empty($item)){
                                                $income += intval($item->amount);
                                            }elseif($item->account_id >= 4001 && $item->account_id <= 5900 && !empty($item)){
                                                $expense += intval($item->amount);
                                            }
                                        }
                                        $lastmonth_income = $income;
                                        $lastmonth_set = ($income/$expense)*100;
                                    }
                                }
    
                                if(!empty($thismonth_sets)){
                                    $thismonth_sets = json_decode($thismonth_sets->accounting);
                                    if(!empty($thismonth_sets)){
                                        $income = 0;
                                        $thismonth_income = 0;
                                        $thismonth_expense = 0;
                                        $expense = 0;
                                        foreach ($thismonth_sets as $item) {
                                            if($item->account_id >= 100 && $item->account_id <= 3200 && !empty($item)){
                                                $income += intval($item->amount);
                                            }elseif($item->account_id >= 4001 && $item->account_id <= 5900 && !empty($item)){
                                                $expense += intval($item->amount);
                                            }
                                        }
                                        $thismonth_income = $income;
                                        $thismonth_expense = $expense;
                                        $thismonth_set = ($income/$expense)*100;
                                    }
                                }
    
                                if(!empty($thisyear_sets)){
                                    $income = 0;
                                    $thisyear_income = 0;
                                    $expense = 0;
                                    foreach($thisyear_sets as $item){
                                        $item = json_decode($item->accounting);
                                        if(!empty($item)){
                                            foreach ($item as $item) {
                                                if($item->account_id >= 100 && $item->account_id <= 3200 && !empty($item)){
                                                    $income += intval($item->amount);
                                                }elseif($item->account_id >= 4001 && $item->account_id <= 5900 && !empty($item)){
                                                    $expense += intval($item->amount);
                                                }
                                            }
                                        }
                                    }
                                    $thisyear_income = $income/$month;
                                    $thisyear_set = ($income/$expense)*100/$month;
                                }
                                $type = 'cash_qaytish';
                                $cash_qaytish = array(
                                    'averageyear' => !empty($thisyear_set)?$thisyear_set:0,
                                    'lastyearthismonth' => !empty($lastyear_set)?$lastyear_set:0,
                                    'lastmonth' => !empty($lastmonth_set)?$lastmonth_set:0,
                                    'thismonth' => !empty($thismonth_set)?$thismonth_set:0,
                                    'thisincome' => !empty($thismonth_income)?$thismonth_income:0,
                                    'thisexpense' => !empty($thismonth_expense)?$thismonth_expense:0
                                );
                                insert_data($bank->id, $bank->mfo_id, $year, $month, $type, $cash_qaytish);
                                $type = 'cash_tushum';
                                $cash_tushum = array(
                                    'averageyear' => !empty($thisyear_income)?$thisyear_income:0,
                                    'lastyearthismonth' => !empty($lastyear_income)?$lastyear_income:0,
                                    'lastmonth' => !empty($lastmonth_income)?$lastmonth_income:0,
                                    'thismonth' => !empty($thismonth_income)?$thismonth_income:0,
                                    'thisincome' => !empty($thismonth_income)?$thismonth_income:0,
                                    'thisexpense' => !empty($thismonth_expense)?$thismonth_expense:0
                                );
                                insert_data($bank->id, $bank->mfo_id, $year, $month, $type, $cash_tushum);
                            }
                        }
    
                    }
                    $month = date('m', strtotime($monthyear));
                    $year = date('Y', strtotime($monthyear));
                    $data = DB::table('data_'.$year)->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
                    // cash_report($monthyear, $data);
                    // all_report($monthyear, $data);
                    $job = (new CashRating($monthyear))->delay(5);
                    $this->dispatch($job);
                }
                $type = "success";
                $message = trans('app.data uploaded');
                $title = trans('app.Sxema title');
                return view('excel.sxema.sxema', compact('errorrows', 'successrows', 'allrows', 'title', 'type', 'message'));
            }else{
                $title = trans('app.Sxema title');
                $type = "error";
                $message = trans('app.error file');
                return view('excel.sxema.sxema', compact('message', 'type', 'title', 'filetypes'));
            }
        }else{
            $title = trans('app.Sxema title');
            return view('excel.sxema.sxema', compact('title'));
        }
    }

    public function credits(Request $request){
        ini_set ('max_execution_time', 360000);
        ini_set ('memory_limit', '2048M');
        $wrong_input = array("'", "_");
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = date('Y-m-d H:i:s', strtotime($request->get('monthyear')));
                $month = date('m', strtotime($request->get('monthyear')));
                $year = date('Y', strtotime($request->get('monthyear')));
                $inserting_data = array();
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data < 2){
                            continue;
                        }
                        if(empty($row[5])){
                            $row[$lastColumn] = trans('app.this client sheet number not found');
                            $errorrows[] = $row;
                            continue;
                        }
                        $mfo_id = intval($row[2]);
                        if(empty($mfo_id)){
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                            continue;
                        }
                        $client_name = str_replace("'", "", $row[3]);
                        $client_inn_passport = $row[4];
                        $client_sheet_number = str_replace($wrong_input, "", $row[5]);
                        $currency_code = intval($row[6]);
                        $contract_amount = $row[7];
                        if($currency_code == 0){
                            $contract_amount_eqiuv = $row[7];
                        }else{
                            $contract_amount_eqiuv = $row[8];
                        }
                        $given_date = strval($row[9]);
                        $given_date = (!empty($row[9]))?date('d-m-Y', strtotime($given_date)):null;
                        $expire_date = (!empty($row[10]))?date('d-m-Y', strtotime($row[10])):null;
                        $rate = intval($row[11]);
                        $remainder = intval($row[12]);
                        $condition_changing = $row[13];
                        $change_date = (!empty($row[14]))?date('d-m-Y', strtotime($row[14])):null;
                        $out_of = intval($row[15]);
                        $out_of_date = (!empty($row[16]))?date('d-m-Y', strtotime($row[16])):null;
                        $trial_amount = intval($row[17]);
                        $debt_amount = intval($row[18]);
                        $backup_amount = intval($row[19]);
                        $remainder_16309 = intval($row[20]);
                        $remainder_16323 = intval($row[21]);
                        $remainder_16377 = intval($row[22]);
                        $activity_code = intval($row[23]);
                        $goal_code = intval($row[24]);
                        $credit_status = 'safe';
                        if(!empty($out_of) || !empty($trial_amount)){
                            $credit_status = 'problem';
                        }
                        $allrows++;
                        $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                        if(!empty($bank)){
                            $new_credit = array(
                                'debt_amount' => $debt_amount,
                                'remainder' => $remainder,
                                'rate' => $rate, 
                                'given_date' => $given_date,
                                'expire_date' => $expire_date,
                                'contract_amount' => $contract_amount,
                                'contract_amount_eqiuv' => $contract_amount_eqiuv,
                                'condition_changing' => $condition_changing,
                                'change_date' => $change_date,
                                'out_of' => $out_of,
                                'out_of_date' => $out_of_date,
                                'trial_amount' => $trial_amount,
                                'backup_created' => $backup_amount,
                                'remainder_16377' => $remainder_16377,
                                'remainder_16309' => $remainder_16309,
                                'remainder_16323' => $remainder_16323,
                            );
                            $data = array(
                                'client_sheet_number' => $client_sheet_number,
                                'mfo_id'=> $bank->mfo_id,
                                'bank_id' => $bank->id,
                                'month' => $month,
                                'year' => $year,
                                'monthyear' => $monthyear,
                                'client_name' => $client_name,
                                'client_inn_passport' => $client_inn_passport,
                                'currency_code' => $currency_code,
                                'activity_code' => $activity_code,
                                'goal_code' => $goal_code,
                                'status' => $credit_status,
                                'portfolio' =>  $new_credit
                            );
                            insert_credit($data);
                            $successrows++;
                        }else{
                            $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                            $errorrows[] = $row;
                        }
                    }
                }
                $title = trans('app.credit box');
                return view('excel.credit.credit', compact('errorrows', 'successrows', 'allrows', 'title'));
            }
        }else{
            $title = trans('app.credit box');
            return view('excel.credit.credit', compact('title'));
        }
    }

    public function new_credits(Request $request){
        // $credit = DB::table('credits')->get()->first();
        // echo $credit->credits;
        ini_set ('max_execution_time', 360000);
        ini_set ('memory_limit', '2048M');
        $wrong_input = array("'", "_");
        $translit = new Transliteration();
        $filetypes = [
            'xlsx',
            'xls'
        ];
        if(!empty($request->hasFile('import'))){
            if(in_array($request->file('import')->getClientOriginalExtension(), $filetypes)){
                $excel = $request->file('import');
                $filepath = public_path().'/uploads/excel-data/';
                $filename = $request->file('import')->getClientOriginalName();
                $excel->move($filepath, $filename);
                $excelreader = new SpreadsheetReader($filepath.$filename);
                $sheetCount = count($excelreader->sheets());
                $errorrows = [];
                $allrows = 0;
                $successrows = 0;
                $monthyear = date('Y-m-d H:i:s', strtotime($request->get('monthyear')));
                $month = date('m', strtotime($request->get('monthyear')));
                $year = date('Y', strtotime($request->get('monthyear')));
                $inserting_data = array();
                for($i=0;$i<1;$i++){
                    $excelreader->ChangeSheet($i);
                    foreach($excelreader as $data => $row){
                        $lastColumn = count($row)-1;
                        if($data < 3){
                            continue;
                        }
                        
                        $mfo_id = intval($row[2]);
                        $client_name = str_replace("'", "", $row[3]);
                        $client_inn_passport = $row[4];
                        $client_sheet_number = str_replace($wrong_input, "", $row[5]);
                        $currency_code = intval($row[6]);
                        $contract_amount = $row[7];
                        if($currency_code == 0){
                            $contract_amount_eqiuv = $row[7];
                        }else{
                            $contract_amount_eqiuv = $row[8];
                        }
                        $given_date = (!empty($row[9]))?date('d-m-Y', strtotime($row[9])):null;
                        $expire_date = (!empty($row[10]))?date('d-m-Y', strtotime($row[10])):null;
                        $rate = $row[11];
                        $remainder = $row[12];
                        $condition_changing = $row[13];
                        $change_date = (!empty($row[14]))?date('d-m-Y', strtotime($row[14])):null;
                        $out_of = $row[15];
                        $out_of_date = (!empty($row[16]))?date('d-m-Y', strtotime($row[16])):null;
                        $trial_amount = $row[17];
                        $debt_amount = $row[18];
                        $backup_amount = $row[19];
                        $remainder_16309 = $row[20];
                        $remainder_16323 = $row[21];
                        $remainder_16377 = $row[22];
                        $activity_code = intval($row[23]);
                        $goal_code = intval($row[24]);
                        $credit_status = 'safe';
                        if(!empty($out_of) || !empty($trial_amount)){
                            $credit_status = 'problem';
                        }
                        $allrows++;
                        if(!empty($mfo_id)){
                            $bank = DB::table('banks')->where('mfo_id', '=', $mfo_id)->first();
                            if(!empty($bank)){
                                $credit = array(
                                    'client_name' => $client_name,
                                    'client_inn_passport' => $client_inn_passport,
                                    'currency_code' => $currency_code,
                                    'contract_amount' => $contract_amount,
                                    'contract_amount_eqiuv' => $contract_amount_eqiuv,
                                    'given_date' => $given_date,
                                    'expire_date' => $expire_date,
                                    'activity_code' => $activity_code,
                                    'goal_code' => $goal_code,
                                    'status' => $credit_status,
                                    'debt_amount' => $debt_amount,
                                    'remainder' => $remainder,
                                    'rate' => $rate, 
                                    'given_date' => $given_date,
                                    'expire_date' => $expire_date,
                                    'condition_changing' => $condition_changing,
                                    'change_date' => $change_date,
                                    'out_of' => $out_of,
                                    'out_of_date' => $out_of_date,
                                    'trial_amount' => $trial_amount,
                                    'backup_created' => $backup_amount,
                                    'remainder_16377' => $remainder_16377,
                                    'remainder_16309' => $remainder_16309,
                                    'remainder_16323' => $remainder_16323,
                                );
                                // $data = array(
                                //     'credits' =>  array($new_credit)
                                // );
                                // insert_credit($bank->id, $bank->mfo_id, $data, $client_sheet_number);
                                $successrows++;
                            }else{
                                $row[$lastColumn] = trans('app.this mfo:').$mfo_id.trans('app.mfo bank not found');
                                $errorrows[] = $row;
                                continue;
                            }
                        }else{
                            $row[$lastColumn] = $data."-".trans('app.row has error')." ".trans('app.not found bank');
                            $errorrows[] = $row;
                            continue;
                        }
                        $credit = json_encode($credit);
                        array_push($inserting_data, $credit);
                    }
                    print_r($inserting_data);
                    insert_newcredit($monthyear, $month, $year, $inserting_data);
                }
            }
        }else{
            $title = trans('app.credit box');
            return view('excel.credit.credit', compact('title'));
        }
    }

    public function data(){
        return checkTable('report');
    }
}
