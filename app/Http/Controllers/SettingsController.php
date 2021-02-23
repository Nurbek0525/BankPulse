<?php

namespace App\Http\Controllers;

use DB;
use App;
use PDO;
use URL;
use Auth;
use Mail;
use App\Role;
use DateTime;
use App\Goal_code;
use App\Department;
use App\Access_right;
use App\Account_sheet;
use App\Activity_code;
use App\Sub_department;
use App\Weight_of_report;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function weight_of_report(Request $request, $id = null){
    	$title = trans('app.weights of ratings');
    	if($request->post()){
            if($request->get('id')){
                $weight = Weight_of_report::find($request->get('id'));
            }else{
                $weight = new Weight_of_report;
            }
            
            $weight->cash_tushum = $request->get('cash_tushum');
            $weight->cash_qaytish = $request->get('cash_qaytish');
            $weight->cash_m_report = $request->get('cash_m_report');
            $weight->cash_execution = $request->get('cash_execution');
            $weight->c_m_report = $request->get('c_m_report');
            $weight->c_execution = $request->get('c_execution');
            $weight->c_phone = $request->get('c_phone');
            $weight->c_check = $request->get('c_check');
            $weight->b_home = $request->get('b_home');
            $weight->b_kontur = $request->get('b_kontur');
            $weight->b_family = $request->get('b_family');
            $weight->b_guarantee = $request->get('b_guarantee');
            $weight->b_past = $request->get('b_past');
            $weight->b_execution = $request->get('b_execution');
            $weight->b_m_report = $request->get('b_m_report');
            $weight->i_out_of = $request->get('i_out_of');
            $weight->i_work_lost = $request->get('i_work_lost');
            $weight->i_likvid_active = $request->get('i_likvid_active');
            $weight->i_likvid_credit = $request->get('i_likvid_credit');
            $weight->i_b_liability = $request->get('i_b_liability');
            $weight->i_b_liability_demand = $request->get('i_b_liability_demand');
            $weight->i_active_likvid = $request->get('i_active_likvid');
            $weight->i_income_expense = $request->get('i_income_expense');
            $weight->i_net_profit = $request->get('i_net_profit');
            $weight->month = date('m', strtotime($request->get('monthyear')));
            $weight->year = date('Y', strtotime($request->get('monthyear')));
            $weight->cash = $request->get('cash');
            $weight->inspeksiya = $request->get('inspeksiya');
            $weight->business = $request->get('business');
            $weight->currency = $request->get('currency');
            $weight->ijro = $request->get('ijro');
            $weight->ijro_head = $request->get('ijro_head');
            $weight->save();
            return redirect('settings/weight/list');
    	}else{
    		return view('settings.weight.weight', compact('title'));
    	}
    }

    public function weight_of_reports(Request $request){
    	$title = trans('app.weights of ratings');
        $weights = DB::table('weight_of_reports')->get()->toArray();
        if($request->get('id')){
            $title = trans('app.edit weights of reports');
            $weight = DB::table('weight_of_reports')->where('id', '=', $request->get('id'))->get()->first();
            return view('settings.weight.weight', compact('title', 'weight'));
        }else{
            return view('settings.weight.weights', compact('title', 'weights'));
        }
    	
    }
    
    public function weight_of_report_view(Request $request){
        $weight = DB::table('weight_of_reports')->where('id', '=', $request->get('id'))->get()->first();
        $title = trans('app.weight single title')." ".trans('app.month'.$weight->month).' '.$weight->year;
        return view('settings.weight.view', compact('title', 'weight'));
    }

    public function account_sheet(Request $request){
        $title = trans('app.account-sheet add');
        if($request->post()){
            $account = new Account_sheet;
            $account->account_id = $request->get('code');
            $account->name = $request->get('name');
            $account->save();
            return redirect('settings/account-sheet/list');
        }else{
            return view('settings.account-sheet.add', compact('title'));
        }
    }

    public function account_sheets(){
        $title = trans('app.account-sheet');
        $account_sheets = DB::table('account_sheets')->get()->toArray();    
        return view('settings.account-sheet.list', compact('title', 'account_sheets'));
    }

    public function role_managements(){
        $title = trans('app.role management');
        $roles = DB::table('roles')->where('status', '=', 'active')->get()->toArray();    
        return view('settings.role.list', compact('title', 'roles'));
    }
    public function role_management(Request $request){
        if($request->post() && $request->get('name')){
            $id = $request->get('id');
            $role = Role::find($id);
            $role->name = $request->get('name');
            $role->position = $request->get('position');
            $role->status = 'active';
            $role->save();
            return redirect('/settings/role/list');
        }else{
            $id = $request->get('id');
        	if(!empty($id)){
                $title = trans('app.edit role');
        		$role = DB::table('roles')->where('id', '=', $id)->get()->first();
                $accessrights = DB::table('access_rights')->where('role_id', '=', $id)->get()->toArray();
                return view('settings.role.add', compact('title', 'role', 'accessrights'));
        	}else{
                $title = trans('app.add role');
        		$role = new Role;
	            $role->name = trans('app.Enter role name');
	            $role->position = trans('app.Select role position');
	            $role->save();
	            $role = Db::table('roles')->get()->last();
	            $array = array(
	                'user' => trans('app.inspektor rights'),
	                'report' => trans('app.report rights'),
	                'excell' => trans('app.excell rights'),
	                'settings' => trans('app.settings rights'),
	            );
	            foreach ($array as $key => $data) {
	                $access_right = new Access_right;
	                $access_right->name = $data;
	                $access_right->code = $key;
	                $access_right->role_id = $role->id;
	                $access_right->save();
	            }
                $accessrights = DB::table('access_rights')->where('role_id', '=', $role->id)->get()->toArray();
                return view('settings.role.add', compact('title', 'role', 'accessrights'));
        	}
        }
        
    }
    public function accessright_change(Request $request){
        $id = $request->get('access_id');
        $type = $request->get('access_type');
        $value = $request->get('value');
        if($type == 'view'){
            $changing = Access_right::find($id);
            $changing->view = $value;
            $changing->save();
        }elseif($type == 'create'){
            $changing = Access_right::find($id);
            $changing->create = $value;
            $changing->save();
        }elseif($type == 'edit'){
            $changing = Access_right::find($id);
            $changing->edit = $value;
            $changing->save();
        }elseif($type == 'delete'){
            $changing = Access_right::find($id);
            $changing->delete = $value;
            $changing->save();
        }
        
    }

    public function lang_change(Request $request){
        $lang = $request->get('lang');
        $id = Auth::user()->id;
        $changing = DB::update("update users set language='$lang' where id=$id");
        session(['locale' => $lang]);
        return redirect()->back();
    }

    public function departments(Request $request){
        $title = trans('app.department list');
        $key = $request->get('key');
        if(!empty($key) && $key == 'sub'){
            $departments = DB::table('sub_departments')->get()->toArray();
            $title = trans('app.sub department list');
        }else{
            $title = trans('app.department list');
            $departments = DB::table('departments')->get()->toArray();
        }
        return view('settings.department.list', compact('title', 'departments', 'key'));
    }

    public function department(Request $request){
        $title = trans('app.department add');
        $key = $request->get('key');
        $code = $request->get('code');
        $departments = DB::table('departments')->get()->toArray();
        if($request->post() && !empty($request->get('name'))){
            if(!empty($key) && $key == 'sub'){
                $host = env('DB_HOST');
                $port = env('DB_PORT');
                $db_name = env('DB_DATABASE');
                $db_username = env('DB_USERNAME');
                $db_password = env('DB_PASSWORD');
                $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "ALTER TABLE weight_of_reports ADD COLUMN $code INTEGER NULL DEFAULT 0";
                $conn->exec($sql);
                $department = new Sub_department;
            }else{
                $department = new Department;
            }
            $department->name = $request->get('name');
            if($key == 'sub'){
                $department->department_id = $request->get('department');
            }
            $department->key = $code;
            $department->save();
            return redirect('settings/department/list?key='.$key);
        }else{
            return view('settings.department.add', compact('title', 'departments', 'key'));
        }
        
    }

    public function activity_codes(){
        $title = trans('app.activity codes');
        $activity_codes = DB::table('activity_codes')->get()->toArray();    
        return view('settings.activity-code.list', compact('title', 'activity_codes'));
    }

    public function activity_code(Request $request){
        $title = trans('app.activity code add');
        if($request->post()){
            $account = new Activity_code;
            $account->code = $request->get('code');
            $account->name = $request->get('name');
            $account->save();
            return redirect('settings/activity-code/list');
        }else{
            return view('settings.activity-code.add', compact('title'));
        }
        
    }
    public function loan_goals(){
        $title = trans('app.loan goal list');
        $goal_codes = DB::table('goal_codes')->get()->toArray();    
        return view('settings.loan-goal.list', compact('title', 'goal_codes'));
    }
    
    public function loan_goal(Request $request){
        $title = trans('app.loan goal add');
        if($request->post()){
            $account = new Goal_code;
            $account->code = $request->get('code');
            $account->name = $request->get('name');
            $account->save();
            return redirect('settings/loan-goal/list');
        }else{
            return view('settings.loan-goal.add', compact('title'));
        }
        
    }



}
