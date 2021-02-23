<?php 

use Illuminate\Http\Request;
use App\Rating_final_result;
use Illuminate\Support\Facades\Schema;
    
    if(!function_exists('get_position')){
        function get_position($user){
            if($user->role_id != 'admin'){
                $position = DB::table('roles')->where('id', '=', $user->role_id)->get()->first();
                return $position->position;
            }else{
                return $user->role_id;
            }
        }
    }
    if(!function_exists('getCities')){
        function getCities($region_id){
            $cities = DB::table('cities');
            if(!empty($region_id)  && $region_id != 'all'){
                $cities = $cities->where('region_id', '=', $region_id);
            }
            $cities = $cities->orderBy('name')->get()->toArray();
            $output = '<option value="all">'.trans("app.all").'</option><option value="" selected disabled hidden>'.trans("app.select city").'</option>';
            if(!empty($cities)){
                foreach($cities as $city){
                    $output .= '<option value="'.$city->id.'">'.$city->name.'</option>';
                }
            }
            return $output;
        }
    }

    if(!function_exists('getfillials')){
        function getfillials($region_id, $mainbank_id, $city_id, $user){
            $position = get_position($user);
            $fillials = DB::table('banks');
            if((!empty($region_id)  && $region_id != 'all')){
                $fillials = $fillials->where('region_work_id', '=', $region_id);
            }
            if($position != 'admin' || $position != 'country'){
                $fillials = $fillials->where('region_work_id', '=', $user->region_id);
            }
            if(!empty($mainbank_id) && $mainbank_id != 'all'){
                $fillials = $fillials->where('mainbank_id', '=', $mainbank_id);
            }
            if(!empty($city_id)  && $city_id != 'all'){
                $fillials = $fillials->where('city_id', '=', $city_id);
            }
            $fillials = $fillials->where('mainbank_id', '!=', 38)->orderBy('name')->get()->toArray();
            $output = '<option value="all">'.trans("app.all").'</option><option value="" selected disabled hidden> '.trans('app.select fillial bank').'</option>';
            if(!empty($fillials)){
                foreach($fillials as $fillial){
                    $output .= '<option value="'.$fillial->id.'">'.$fillial->name.' ['.generateMfo($fillial->mfo_id).']</option>';
                }
            }
            return $output;
        }
    }

    if(!function_exists('getMainbanks')){
        function getMainbanks($region_id,  $city_id, $user){
            $position = get_position($user);
            $banks = DB::table('banks')->
            select('mainbank_id');
            if((!empty($region_id)  && $region_id != 'all')){
                $banks = $banks->where('banks.region_work_id', '=', $region_id);
            }
            if($position != 'admin' || $position != 'country'){
                $banks = $banks->where('region_work_id', '=', $user->region_id);
            }
            if(!empty($city_id)  && $city_id != 'all'){
                $banks = $banks->where('banks.city_id', '=', $city_id);
            }
            $banks = $banks->where('mainbank_id', '!=', 38)->groupBy('mainbank_id')->get()->toArray();
            $mainbanks = DB::table('mainbanks')->where(function($query) use($banks){
                foreach ($banks as $bank) {
                    $query->orWhere('id', '=', $bank->mainbank_id);
                }
            })->orderBy('name')->get()->toArray();
            $output = '<option value="all">'.trans("app.all").'</option><option value="" selected disabled hidden> '.trans('app.select main bank').'</option>';
            if(!empty($mainbanks)){
                foreach($mainbanks as $bank){
                    $output .= '<option value="'.$bank->id.'">'.$bank->name.'</option>';
                }
            }
            return $output;
        }
    }

    if(!function_exists('getAccountsheets')){
        function getAccountsheets($cat_account){
            $account_sheets = DB::table('account_sheets');
            if(!empty($cat_account) && $cat_account != 'all'){
                $account_sheets = $account_sheets->where('cat_id', '=', $cat_account);
            }
            $account_sheets = $account_sheets->orderBy('account_id')->get()->toArray();
            $output = '<option value="all">'.trans("app.all").'</option><option value="" selected disabled hidden> '.trans('app.select account sheet').'</option>';
            if(!empty($account_sheets)){
                foreach($account_sheets as $account){
                    $output .= '<option value="'.$account->id.'">['.$account->account_id.'] '.$account->name.'</option>';
                }
            }
            return $output;
        }
    }

    if(!function_exists('getSubdepartments')){
        function getSubdepartments($department){
            $sub_departments = DB::table('sub_departments');
            if(!empty($department) && $department != 'all' && $department != 'monthly'){
                $sub_departments = $sub_departments->where('department_id', '=', $department);
            }
            $sub_departments = $sub_departments->orderBy('name')->get()->toArray();
            $output = '<option value="all">'.trans("app.all").'</option><option value="" selected disabled hidden> '.trans('app.select rating sub type').'</option>';
            if(!empty($sub_departments)){
                foreach($sub_departments as $department){
                    $output .= '<option value="'.$department->id.'">'.$department->name.'</option>';
                }
            }
            return $output;
        }
    }

    if(!function_exists('getHeadbanks')){
        function getHeadbanks($mainbank_id){
            $headbanks = DB::table('headbanks')->where('mainbank_id', '=', $mainbank_id)->get()->toArray();
            $output = '<option value="" selected disabled hidden> Boshqarmani tanlang</option>';
            if(!empty($headbanks)){
                foreach($headbanks as $bank){
                    $output .= '<option value="'.$bank->id.'">'.$bank->name.'</option>';
                }
            }
            return $output;
        }
    }

    if(!function_exists('getAverageCash')){
        function getAverageCash($tushum, $qaytish, $execution, $m_report, $monthyear, $bank){
            $average = ($tushum + $qaytish + $execution + $m_report);
            return number_format((float)$average, 2, '.', '');
        }
    }

    if(!function_exists('getAverageinspeksiya')){
        function getAverageinspeksiya($outfinal_result, $lostfinal_result, $dafinal_result, $dcfinal_result, $blfinal_result, $bdfinal_result, $npfinal_result, $alfinal_result, $eifinal_result, $others, $monthyear, $bank){
            $average = ($outfinal_result+$lostfinal_result+$dafinal_result+$dcfinal_result+$blfinal_result+$bdfinal_result+$npfinal_result+$alfinal_result+$eifinal_result+$others);
            return number_format((float)$average, 2, '.', '');
        }
    }

    if(!function_exists('generateMfo')){
        function generateMfo($generateMfo){
            $mfo = strval(sprintf("%'.05d", $generateMfo));
            return $mfo;
        }
    }

    if(!function_exists('getAveragebusiness')){
        function getAveragebusiness($bpfinal_result, $bhfinal_result, $bkfinal_result, $bgfinal_result, $bffinal_result, $bmfinal_result, $bifinal_result, $monthyear, $bank){
            $average = ($bpfinal_result+$bhfinal_result+$bgfinal_result+$bkfinal_result+$bmfinal_result+$bifinal_result+$bffinal_result);
            return number_format((float)$average, 2, '.', '');
        }
    }

    if(!function_exists('getAveragecurrency')){
        function getAveragecurrency($cvfinal_result, $mrfinal_result, $cefinal_result, $cpfinal_result, $monthyear, $bank){
            $average = ($cvfinal_result+$cefinal_result+$mrfinal_result+$cpfinal_result);
            return number_format((float)$average, 2, '.', '');
        }
    }

    if(!function_exists('getAverageijro')){
        function getAverageijro($meeting_execution, $letter_execution, $head_number, $people_qabul, $prime_number, $out_of_number, $monthyear, $bank){
            $average = ($meeting_execution + $letter_execution - ($head_number + $people_qabul + $prime_number + $out_of_number));
            return number_format((float)$average, 2, '.', '');
        }
    }

    if(!function_exists('getAveragefinal')){
        function getAveragefinal($business, $currency, $cash, $inspeksiya, $ijro_intizom){
            $average = ($business+$currency+$cash+$inspeksiya+$ijro_intizom);
            
            return number_format((float)$average, 2, '.', '');
        }
    }

    if(!function_exists('deleteTable')){
        function deleteTable(){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $year = date('Y');
            $table_name = 'data_'.$year;
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DROP TABLE data_2020";
            $data=$conn->exec($sql);
            
            return $data;
        }
    }
    if(!function_exists('insert_data')){
        function insert_data($bank_id, $mfo_id, $year, $month, $type, $data){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $data = json_encode($data);
            // checking data in database exist or not
            if(checkTable('data', $year)){
                $datetime = date('Y-m-d H:i:s');
                $old = DB::table('data_'.$year)->where([['bank_id', '=', $bank_id], ['mfo_id', '=', $mfo_id], ['year', '=', $year], ['month', '=', $month]])->get()->first();
                if(!empty($old)){
                   $id = $old->id;
                   $sql = "UPDATE data_$year SET $type='$data', updated_at='$datetime' WHERE id=$id"; 
                }else{
                    $sql = "INSERT INTO data_$year (mfo_id, bank_id, year, month, $type, created_at, updated_at) VALUES('$mfo_id', '$bank_id', '$year', '$month', '$data', '$datetime', '$datetime')";
                }
                $output = $conn->exec($sql);
            }else{
                $output = 'Xatolik';
            }
            
            return $output;
        }
    }

    if(!function_exists('insert_report')){
        function insert_report($bank_id, $mfo_id, $weight, $year, $month, $type, $data){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $data = json_encode($data);
            // checking table exist or not 
            if(checkTable('report', $year)){
                $datetime = date('Y-m-d H:i:s');
                $old = DB::table('report_'.$year)->where([['bank_id', '=', $bank_id], ['mfo_id', '=', $mfo_id], ['year', '=', $year], ['month', '=', $month]])->get()->first();
                if(!empty($old)){
                   $id = $old->id;
                   $sql = "UPDATE report_$year SET $type='$data', weight_id='$weight', updated_at='$datetime' WHERE id=$id"; 
                }else{
                    $sql = "INSERT INTO report_$year (mfo_id, bank_id, weight_id, year, month, $type, created_at, updated_at) VALUES('$mfo_id', '$bank_id', '$weight', '$year', '$month', '$data', '$datetime', '$datetime')";
                }
                $output = $conn->exec($sql);
            }else{
                $output = 'Xatolik';
            }
            // checking data in database exist or not
            
            return $output;
        }
    }

    if(!function_exists('insert_balance')){
        function insert_balance($bank_id, $mfo_id, $year, $month, $data){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $data = json_encode($data);
            // checking data in database exist or not
            if(checkTable('balance', $year)){
                $datetime = date('Y-m-d H:i:s');
                $old = DB::table('balance_'.$year)->where([['bank_id', '=', $bank_id], ['mfo_id', '=', $mfo_id], ['year', '=', $year], ['month', '=', $month]])->get()->first();
                if(!empty($old)){
                   $id = $old->id;
                   $sql = "UPDATE balance_$year SET accounting='$data', updated_at='$datetime' WHERE id=$id"; 
                }else{
                    $sql = "INSERT INTO balance_$year (mfo_id, bank_id, year, month, accounting, created_at, updated_at) VALUES('$mfo_id', '$bank_id', '$year', '$month', '$data', '$datetime', '$datetime')";
                }
                $output = $conn->exec($sql);
            }else{
                $output = 'Xatolik';
            }
            
            return $output;
        }
    }

    if(!function_exists('insert_sxema')){
        function insert_sxema($bank_id, $mfo_id, $year, $month, $data){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $data = json_encode($data);
            // checking data in database exist or not
            if(checkTable('sxema', $year)){
                $datetime = date('Y-m-d H:i:s');
                $old = DB::table('sxema_'.$year)->where([['bank_id', '=', $bank_id], ['mfo_id', '=', $mfo_id], ['month', '=', $month]])->get()->first();
                if(!empty($old)){
                   $id = $old->id;
                   $sql = "UPDATE sxema_$year SET accounting='$data', updated_at='$datetime' WHERE id=$id"; 
                }else{
                    $sql = "INSERT INTO sxema_$year (mfo_id, bank_id, year, month, accounting, created_at, updated_at) VALUES('$mfo_id', '$bank_id', '$year', '$month', '$data', '$datetime', '$datetime')";
                }
                $output = $conn->exec($sql);
            }else{
                $output = 'Xatolik';
            }
            
            return $output;
        }
    }

    if(!function_exists('insert_credit')){
        function insert_credit($data){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $month = $data['month'];
            $year = $data['year'];
            $monthyear = $data['monthyear'];
            $bank_id = $data['bank_id'];
            $mfo_id = $data['mfo_id'];
            $client_sheet_number = $data['client_sheet_number'];
            $client_name = $data['client_name'];
            $client_inn_passport = $data['client_inn_passport'];
            $currency_code = $data['currency_code'];
            $activity_code = $data['activity_code'];
            $goal_code = $data['goal_code'];
            $credit_status = $data['status'];
            $portfolio = json_encode($data['portfolio']);
            // checking data in database exist or not
            if(checkTable('portfolio', $year)){
                $datetime = date('Y-m-d H:i:s');
                $old = DB::table('portfolio_'.$year)->where([['client_sheet_number', '=', $client_sheet_number], ['month', '=', $month], ['year', '=', $year]])->get()->first();
                if(!empty($old)){
                    $id = $old->id;
                    $sql = "UPDATE portfolio_$year SET portfolio='$portfolio', updated_at='$datetime', status='$credit_status' WHERE id=$id"; 
                }else{
                    $sql = "INSERT INTO portfolio_$year (month, year, monthyear, mfo_id, bank_id, client_sheet_number, client_name, client_inn_passport, currency_code, activity_code, goal_code, portfolio, status, created_at, updated_at) VALUES('$month', '$year', '$monthyear', '$mfo_id', '$bank_id', '$client_sheet_number', '$client_name', '$client_inn_passport', '$currency_code', '$activity_code', '$goal_code', '$portfolio', '$credit_status', '$datetime', '$datetime')";
                }
                $output = $conn->exec($sql);
            }else{
                $output = 'Xatolik';
            }
            
            return $output;
        }
    }

    if(!function_exists('insert_newcredit')){
        function insert_newcredit($monthyear, $month, $year, $data){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $credits = json_encode($data);
            // checking data in database exist or not
            if(checkTable('new_credits', $year = null)){
                $datetime = date('Y-m-d H:i:s');
                $sql = "INSERT INTO new_credits (month, year, monthyear, credits, created_at, updated_at) VALUES('$month', '$year', '$monthyear' '$credits', '$datetime', '$datetime')";
                $output = $conn->exec($sql);
            }else{
                $output = 'Xatolik';
            }
            
            return $output;
        }
    }
    
    if(!function_exists('checkTable')){
        function checkTable($type, $year){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            

            if($type == 'data'){
                $table_name = $type.'_'.$year;
                $sql="CREATE TABLE IF NOT EXISTS ".$table_name." (
                    id serial PRIMARY KEY,
                    mfo_id INTEGER NOT NULL DEFAULT 0,
                    bank_id INTEGER NOT NULL DEFAULT 0,
                    month INTEGER NOT NULL DEFAULT 0,
                    year INTEGER NOT NULL DEFAULT 0,
                    b_past JSON NULL DEFAULT NULL,
                    b_guarantee JSON NULL DEFAULT NULL,
                    b_family JSON NULL DEFAULT NULL,
                    b_home JSON NULL DEFAULT NULL,
                    b_kontur JSON NULL DEFAULT NULL,
                    b_execution JSON NULL DEFAULT NULL,
                    b_m_report JSON NULL DEFAULT NULL,
                    cash_tushum JSON NULL DEFAULT NULL,
                    cash_qaytish JSON NULL DEFAULT NULL,
                    cash_execution JSON NULL DEFAULT NULL,
                    cash_m_report JSON NULL DEFAULT NULL,
                    c_check JSON NULL DEFAULT NULL,
                    c_m_report JSON NULL DEFAULT NULL,
                    c_execution JSON NULL DEFAULT NULL,
                    c_phone JSON NULL DEFAULT NULL,
                    i_out_of JSON NULL DEFAULT NULL,
                    i_work_lost JSON NULL DEFAULT NULL,
                    i_likvid_credit JSON NULL DEFAULT NULL,
                    i_likvid_active JSON NULL DEFAULT NULL,
                    i_b_liability_demand JSON NULL DEFAULT NULL,
                    i_b_liability JSON NULL DEFAULT NULL,
                    i_net_profit JSON NULL DEFAULT NULL,
                    i_active_likvid JSON NULL DEFAULT NULL,
                    i_income_expense JSON NULL DEFAULT NULL,
                    i_others JSON NULL DEFAULT NULL,
                    ijro JSON NULL DEFAULT NULL,
                    created_at timestamp(0) NULL DEFAULT NULL,
                    updated_at timestamp(0) NULL DEFAULT NULL,
                    FOREIGN KEY (bank_id) REFERENCES banks(id)
                )";
            }elseif($type == 'report'){

                $table_name = $type.'_'.$year;
                $sql="CREATE TABLE IF NOT EXISTS ".$table_name." (
                    id serial PRIMARY KEY,
                    mfo_id INTEGER NOT NULL DEFAULT 0,
                    bank_id INTEGER NOT NULL DEFAULT 0,
                    weight_id INTEGER NOT NULL DEFAULT 0,
                    month INTEGER NOT NULL DEFAULT 0,
                    year INTEGER NOT NULL DEFAULT 0,
                    rate numeric(7,3) NULL DEFAULT NULL,
                    business JSON NULL DEFAULT NULL,
                    inspeksiya JSON NULL DEFAULT NULL,
                    cash JSON NULL DEFAULT NULL,
                    currency JSON NULL DEFAULT NULL,
                    ijro JSON NULL DEFAULT NULL,
                    b_past JSON NULL DEFAULT NULL,
                    b_guarantee JSON NULL DEFAULT NULL,
                    b_family JSON NULL DEFAULT NULL,
                    b_home JSON NULL DEFAULT NULL,
                    b_kontur JSON NULL DEFAULT NULL,
                    b_execution JSON NULL DEFAULT NULL,
                    b_m_report JSON NULL DEFAULT NULL,
                    cash_tushum JSON NULL DEFAULT NULL,
                    cash_qaytish JSON NULL DEFAULT NULL,
                    cash_execution JSON NULL DEFAULT NULL,
                    cash_m_report JSON NULL DEFAULT NULL,
                    c_check JSON NULL DEFAULT NULL,
                    c_m_report JSON NULL DEFAULT NULL,
                    c_execution JSON NULL DEFAULT NULL,
                    c_phone JSON NULL DEFAULT NULL,
                    i_out_of JSON NULL DEFAULT NULL,
                    i_work_lost JSON NULL DEFAULT NULL,
                    i_likvid_credit JSON NULL DEFAULT NULL,
                    i_likvid_active JSON NULL DEFAULT NULL,
                    i_b_liability_demand JSON NULL DEFAULT NULL,
                    i_b_liability JSON NULL DEFAULT NULL,
                    i_net_profit JSON NULL DEFAULT NULL,
                    i_active_likvid JSON NULL DEFAULT NULL,
                    i_income_expense JSON NULL DEFAULT NULL,
                    ijro_apparati JSON NULL DEFAULT NULL,
                    i_others JSON NULL DEFAULT NULL,
                    created_at timestamp(0) NULL DEFAULT NULL,
                    updated_at timestamp(0) NULL DEFAULT NULL,
                    FOREIGN KEY (bank_id) REFERENCES banks(id),
                    FOREIGN KEY (weight_id) REFERENCES weight_of_reports(id)
                )";
            }elseif($type == 'balance'){
                $table_name = $type.'_'.$year;
                $sql="CREATE TABLE IF NOT EXISTS ".$table_name." (
                    id serial PRIMARY KEY,
                    mfo_id INTEGER NOT NULL DEFAULT 0,
                    bank_id INTEGER NOT NULL DEFAULT 0,
                    month INTEGER NOT NULL DEFAULT 0,
                    year INTEGER NOT NULL DEFAULT 0,
                    accounting JSON NULL DEFAULT NULL,
                    created_at timestamp(0) NULL DEFAULT NULL,
                    updated_at timestamp(0) NULL DEFAULT NULL,
                    FOREIGN KEY (bank_id) REFERENCES banks(id)
                )";
            }elseif($type == 'sxema'){
                $table_name = $type.'_'.$year;
                $sql="CREATE TABLE IF NOT EXISTS ".$table_name." (
                    id serial PRIMARY KEY,
                    mfo_id INTEGER NOT NULL DEFAULT 0,
                    bank_id INTEGER NOT NULL DEFAULT 0,
                    month INTEGER NOT NULL DEFAULT 0,
                    year INTEGER NOT NULL DEFAULT 0,
                    accounting JSON NULL DEFAULT NULL,
                    created_at timestamp(0) NULL DEFAULT NULL,
                    updated_at timestamp(0) NULL DEFAULT NULL,
                    FOREIGN KEY (bank_id) REFERENCES banks(id)
                )";
            }elseif($type == 'portfolio'){
                $table_name = $type.'_'.$year;
                $sql="CREATE TABLE IF NOT EXISTS ".$table_name." (
                    id serial PRIMARY KEY,
                    mfo_id INTEGER NOT NULL DEFAULT 0,
                    month INTEGER NOT NULL DEFAULT 0,
                    year INTEGER NOT NULL DEFAULT 0,
                    monthyear timestamp(0) NULL DEFAULT NULL,
                    bank_id INTEGER NOT NULL DEFAULT 0,
                    client_sheet_number numeric(30,0) NOT NULL DEFAULT NULL,
                    client_name CHARACTER VARYING(250) NOT NULL DEFAULT NULL,
                    client_inn_passport CHARACTER VARYING(35) NOT NULL DEFAULT NULL,
                    currency_code integer NOT NULL DEFAULT NULL,
                    activity_code integer not null default null,
                    goal_code integer not null default null,
                    gender integer default null,
                    client_type character varying(10) default 'physical',
                    portfolio jsonb not null default null,
                    status character varying(10) not null default 'safe',
                    created_at timestamp(0) NULL DEFAULT NULL,
                    updated_at timestamp(0) NULL DEFAULT NULL,
                    FOREIGN KEY (bank_id) REFERENCES banks(id)
                )";
            }
            $grant = "GRANT bankpulse_bank TO bankpulse;";
            $conn->exec($sql);
            //$conn->exec($grant);
            
            return true;
        }
    }

    if(!function_exists('cash_report')){
        function cash_report($monthyear, $data = null){
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            
            $table_data = 'data_'.$year;
            $table_report = 'report_'.$year;
            if(!empty($data)){
                
                foreach ($data as $cal) {
                    $null_weight = 0;
                    $cash_tushum = json_decode($cal->cash_tushum);
                    $cash_qaytish = json_decode($cal->cash_qaytish);
                    $cash_execution = json_decode($cal->cash_execution);
                    $cash_m_report = json_decode($cal->cash_m_report);

                    $array_filter = (object) array_filter((array) $cash_tushum, 'strlen');
                    if(count((array) $array_filter)){
                        $subaverage = $cash_tushum->thismonth - $cash_tushum->averageyear;
                        $sublastthis = $cash_tushum->thismonth - $cash_tushum->lastyearthismonth;
                        $sublastmonth = $cash_tushum->thismonth - $cash_tushum->lastmonth;
                        if($subaverage > 0){
                            $subaverage = 33.33;
                        }else{
                            $subaverage = 0;
                        }
                        if($sublastmonth > 0){
                            $sublastmonth = 33.33;
                        }else{
                            $sublastmonth = 0;
                        }
                        if($sublastthis > 0){
                            $sublastthis = 33.33;
                        }else{
                            $sublastthis = 0;
                        }
                        $tushum_percent = round(($sublastmonth + $sublastthis + $subaverage), 2);
                        if($tushum_percent > 90){
                            $tushum_percent = 100;
                        }
                        $tushum_result = $tushum_percent*($weight->cash_tushum/100);
                        $tushum_color = '#000';
                    }else{
                        $null_weight += $weight->cash_tushum;
                        $tushum_percent = null;
                        $tushum_result = null;
                        $tushum_color = '#e65252';
                    }
                    $array_filter = (object) array_filter((array) $cash_qaytish, 'strlen');
                    if(count((array) $array_filter)){
                        $subaverage = $cash_qaytish->thismonth - $cash_qaytish->averageyear;
                        $sublastthis = $cash_qaytish->thismonth - $cash_qaytish->lastyearthismonth;
                        $sublastmonth = $cash_qaytish->thismonth - $cash_qaytish->lastmonth;
                        if($subaverage > 0){
                            $subaverage = 33.33;
                        }else{
                            $subaverage = 0;
                        }
                        if($sublastmonth > 0){
                            $sublastmonth = 33.33;
                        }else{
                            $sublastmonth = 0;
                        }
                        if($sublastthis > 0){
                            $sublastthis = 33.33;
                        }else{
                            $sublastthis = 0;
                        }
                        $qaytish_percent = round(($sublastmonth + $sublastthis + $subaverage), 2);
                        if($qaytish_percent > 90){
                            $qaytish_percent = 100;
                        }
                        $qaytish_result = $qaytish_percent*($weight->cash_qaytish/100);
                        $qaytish_color = '#000';
                    }else{
                        $null_weight += $weight->cash_qaytish;
                        $qaytish_percent = null;
                        $qaytish_result = null;
                        $qaytish_color = '#e65252';
                    }
                    if(isset($cash_execution ->not_provided) || isset($cash_execution->delayed) || isset($cash_execution->poor_quality)){
                        $eexist_case = $cash_execution->exist_case;
                        $not_provided = $cash_execution->not_provided;
                        $delayed = $cash_execution->delayed;
                        $poor_quality = $cash_execution->poor_quality;
                        $e_percent = $eexist_case - ($not_provided + $delayed + $poor_quality);
                        $e_result = (empty($eexist_case) )?0:(($e_percent/$eexist_case)*$weight->cash_execution);
                        $e_color = '#000';
                    }else{
                        $null_weight += $weight->cash_execution;
                        $e_result = null;
                        $e_percent = null;
                        $e_color = '#e65252';
                    }

                    if(isset($cash_m_report ->not_provided) || isset($cash_m_report->delayed) || isset($cash_m_report->poor_quality)){
                        $mexist_case = $cash_m_report->exist_case;
                        $not_provided = $cash_m_report->not_provided;
                        $delayed = $cash_m_report->delayed;
                        $poor_quality = $cash_m_report->poor_quality;
                        $m_percent = $mexist_case - ($not_provided + $delayed + $poor_quality);
                        $m_result = (empty($mexist_case) )?0:(($m_percent/$mexist_case)*$weight->cash_m_report);
                        $m_color = '#000';
                    }else{
                        $null_weight += $weight->cash_m_report;
                        $m_result = null;
                        $m_percent = null;
                        $m_color = '#e65252';
                    }

                    if($null_weight > 0){
                        $full_weight = $weight->cash_qaytish + $weight->cash_tushum + $weight->cash_execution + $weight->cash_m_report;
                        $extra_weight = $full_weight - $null_weight;
                        $all_rating = $tushum_result + $qaytish_result + $e_result + $m_result;
                        $percent_weight = ($extra_weight == 0)?0:($all_rating/$extra_weight);
                        // redaclare final result after some departments has not checked
                        if(isset($tushum_percent)){
                            $tushum_result = $tushum_percent*($weight->cash_tushum/100);  
                        }else{
                            $tushum_result = $weight->cash_tushum*$percent_weight;
                        }
                        if(isset($qaytish_percent)){
                            $qaytish_result = $qaytish_percent*($weight->cash_qaytish/100);
                        }else{
                            $qaytish_result = $weight->cash_qaytish*$percent_weight;
                        }
                        if(isset($e_percent)){
                            $e_result = ($eexist_case == 0 )?0:(($e_percent/$eexist_case)*($weight->cash_execution));
                        }else{
                            $e_result = $weight->cash_execution*$percent_weight;
                        }
                        if(isset($m_percent)){
                            $m_result = ($mexist_case == 0 )?0:(($m_percent/$mexist_case)*($weight->cash_m_report));
                        }else{
                            $m_result = $weight->cash_m_report*$percent_weight;
                        }
                    }else{
                        if($tushum_percent > 90){
                            $tushum_percent = 100;
                        }
                        $tushum_result = $tushum_percent*($weight->cash_tushum/100);

                        if($qaytish_percent > 90){
                            $qaytish_percent = 100;
                        }
                        $qaytish_result = $qaytish_percent*($weight->cash_qaytish/100);
                        $e_result = (empty($eexist_case) )?0:(($e_percent/$eexist_case)*$weight->cash_execution);
                        $m_result = (empty($mexist_case) )?0:(($m_percent/$mexist_case)*$weight->cash_m_report);
                    }
                    $t_report = array(
                        'percent' => $tushum_percent,
                        'final_result' => $tushum_result,
                        'color' => $tushum_color
                    );
                    $q_report = array(
                        'percent' => $qaytish_percent,
                        'final_result' => $qaytish_result,
                        'color' => $qaytish_color
                    );
                    $e_report = array(
                        'percent' => $e_percent,
                        'final_result' => $e_result,
                        'color' => $e_color
                    );
                    $m_report = array(
                        'percent' => $m_percent,
                        'final_result' => $m_result,
                        'color' => $m_color
                    );
                    $mfo_id = $cal->mfo_id;
                    $bank_id = $cal->bank_id;
                    $weight_id = $weight->id;
                    $t_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'cash_tushum', $t_report);
                    $q_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'cash_qaytish', $q_report);
                    $e_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'cash_execution', $e_report);
                    $m_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'cash_m_report', $m_report);
                }
                return true;
            }else{
                return false;
            }
        }
    }

    if(!function_exists('inspeksiya_report')){
        function inspeksiya_report($monthyear, $data = null){
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            
            $table_data = 'data_'.$year;
            $table_report = 'report_'.$year;
            if(isset($data)){
                
                foreach ($data as $cal) {
                    $null_weight = 0;
                    $i_out_of = json_decode($cal->i_out_of);
                    $i_work_lost = json_decode($cal->i_work_lost);
                    $i_likvid_active = json_decode($cal->i_likvid_active);
                    $i_likvid_credit = json_decode($cal->i_likvid_credit);
                    $i_active_likvid = json_decode($cal->i_active_likvid);
                    $i_net_profit = json_decode($cal->i_net_profit);
                    $i_b_liability_demand = json_decode($cal->i_b_liability_demand);
                    $i_b_liability = json_decode($cal->i_b_liability);
                    $i_income_expense = json_decode($cal->i_income_expense);
                    $array_filter = (object) array_filter((array) $i_out_of, 'strlen');
                    if(count((array) $array_filter)){

                        $allcredit = $i_out_of->allcredit;
                        $problemcredit = $i_out_of->problemcredit;
                        $out_of_percent = ((empty($allcredit))?0:($problemcredit/$allcredit))*100;
                        if($out_of_percent < 0.1000 && $out_of_percent >= 0){
                            $out_of_result = 100*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 0.10001 && $out_of_percent <= 0.50099){
                            $out_of_result = 95*($weight->i_out_of/100);
                        }elseif($out_of_percent > 0.50001 && $out_of_percent < 1.00099){
                            $out_of_result = 90*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 1.00001 && $out_of_percent < 2.00099){
                            $out_of_result = 80*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 2.00001 && $out_of_percent < 3.00099){
                            $out_of_result = 70*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 3.00001 && $out_of_percent < 4.00099){
                            $out_of_result = 60*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 4.00001 && $out_of_percent < 5.00099){
                            $out_of_result = 50*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 5){
                            $out_of_result = 0.00001*($weight->i_out_of/100);
                        } 
                        if($out_of_result > $weight->i_out_of){
                            $out_of_result = $weight->i_out_of;
                        }
                        $out_of_color = '#000';
                    }else{
                        $null_weight += $weight->i_out_of;
                        $out_of_percent = null;
                        $out_of_result = null;
                        $out_of_color = '#e65252';
                    }
                    if(isset($i_likvid_active->alldeposit) || isset($i_likvid_active->allactive)){
                        $exist_case = $i_likvid_active->exist_case;
                        $alldeposit = $i_likvid_active->alldeposit;
                        $allactive = $i_likvid_active->allactive;
                        $l_active_percent = (empty($allactive))?0:((($alldeposit/$allactive)*10000)/$exist_case);
                        $l_active_result = $l_active_percent*($weight->i_likvid_active/100);
                        if($l_active_result > $weight->i_likvid_active){
                            $l_active_result = $weight->i_likvid_active;
                        }
                        $l_active_color = '#000';
                    }else{
                        $null_weight += $weight->i_likvid_active;
                        $l_active_percent = null;
                        $l_active_result = null;
                        $l_active_color = '#e65252';
                    }

                    if(isset($i_likvid_credit->alldeposit) || isset($i_likvid_credit->allcredit)){
                        $exist_case = $i_likvid_credit->exist_case;
                        $alldeposit = $i_likvid_credit->alldeposit;
                        $allcredit = $i_likvid_credit->allcredit;
                        $l_credit_percent = 100 - ((empty($allcredit))?0:(($alldeposit/$allcredit)*100 - $exist_case));
                        $l_credit_result = $l_credit_percent*($weight->i_likvid_credit/100);
                        if($l_credit_result > $weight->i_likvid_credit){
                            $l_credit_result = $weight->i_likvid_credit;
                        }
                        $l_credit_color = '#000';
                    }else{
                        $null_weight += $weight->i_likvid_credit;
                        $l_credit_percent = null;
                        $l_credit_result = null;
                        $l_credit_color = '#e65252';
                    }

                    if(isset($i_b_liability->peopledeposit) || isset($i_b_liability->alldeposit)){
                        $exist_case = $i_b_liability->exist_case;
                        $peopledeposit = $i_b_liability->peopledeposit;
                        $alldeposit = $i_b_liability->alldeposit;
                        $b_liability_percent = (empty($exist_case))?0:((empty($alldeposit))?0:((($peopledeposit/$alldeposit)*10000)/$exist_case));
                        $b_liability_result = $b_liability_percent*($weight->i_b_liability/100);
                        if($b_liability_result > $weight->i_b_liability){
                            $b_liability_result = $weight->i_b_liability;
                        }
                        $b_liability_color= '#000';
                    }else{
                        $null_weight += $weight->i_b_liability;
                        $b_liability_percent = null;
                        $b_liability_result = null;
                        $b_liability_color = '#e65252';
                    }

                    if(isset($i_b_liability_demand->demanddeposit) || isset($i_b_liability_demand->alldeposit)){
                        $exist_case = $i_b_liability_demand->exist_case;
                        $demanddeposit = $i_b_liability_demand->demanddeposit;
                        $alldeposit = $i_b_liability_demand->alldeposit;
                        $b_demand_percent = (empty($alldeposit))?0:(100 - ((intval($demanddeposit)/intval($alldeposit))*100 - intval($exist_case)));
                        $b_demand_result = $b_demand_percent*($weight->i_b_liability_demand/100);
                        if($b_demand_result > $weight->i_b_liability_demand){
                            $b_demand_result = $weight->i_b_liability_demand;
                        }
                        $b_demand_color = '#000';
                    }else{
                        $null_weight += $weight->i_b_liability_demand;
                        $b_demand_percent = null;
                        $b_demand_result = null;
                        $b_demand_color = '#e65252';
                    }

                    if(isset($i_net_profit->aver_profits) || isset($i_net_profit->allactive)){
                        $exist_case = $i_net_profit->exist_case;
                        $aver_profit = $i_net_profit->aver_profits;
                        $allactive = $i_net_profit->allactive;
                        $net_percent = (empty($allactive))?0:($aver_profit/$allactive)*100;
                        $net_percent = ($net_percent < 0 )?0:$net_percent;
                        $net_result = $net_percent*($weight->i_net_profit);
                        if($net_result > $weight->i_net_profit){
                            $net_result = $weight->i_net_profit;
                        }
                        $net_color = '#000';
                    }else{
                        $null_weight += $weight->i_net_profit;
                        $net_percent = null;
                        $net_result = null;
                        $net_color = '#e65252';
                    }

                    if(isset($i_active_likvid->active_likvids) || isset($i_active_likvid->allactive)){
                        $exist_case = $i_active_likvid->exist_case;
                        $active_likvids = $i_active_likvid->active_likvids;
                        $allactive = $i_active_likvid->allactive;
                        $a_l_percent = (empty($allactive))?0:((intval($active_likvids)/intval($allactive))*$exist_case);
                        $a_l_result = $a_l_percent*($weight->i_active_likvid);
                        if($a_l_result > $weight->i_active_likvid){
                            $a_l_result = $weight->i_active_likvid;
                        }
                        $a_l_color = '#000';
                    }else{
                        $null_weight += $weight->i_active_likvid;
                        $a_l_percent = null;
                        $a_l_result = null;
                        $a_l_color = '#e65252';
                    }

                    if(isset($i_income_expense->averexpense) || isset($i_income_expense->averincome)){
                        $exist_case = $i_income_expense->exist_case;
                        $averexpense = $i_income_expense->averexpense;
                        $averincome = $i_income_expense->averincome;
                        $i_i_e_percent = (empty($averincome))?0:(100 - ((($averexpense/$averincome)*100) - $exist_case));
                        $i_i_e_percent = ($i_i_e_percent < 0)?0:$i_i_e_percent;
                        $i_i_e_result = $i_i_e_percent*($weight->i_income_expense/100);
                        if($i_i_e_result > $weight->i_income_expense){
                            $i_i_e_result = $weight->i_income_expense;
                        }
                        $i_i_e_color = '#000';
                    }else{
                        $null_weight += $weight->i_income_expense;
                        $i_i_e_percent = null;
                        $i_i_e_result = null;
                        $i_i_e_color = '#e65252';
                    }

                    if(isset($i_work_lost->losts) || isset($i_work_lost->net_profit)){
                        $exist_case = $i_work_lost->exist_case;
                        $net_profit = $i_work_lost->net_profit;
                        $losts = $i_work_lost->losts;
                        $w_losts_percent = (($losts < 0)?50:0) + (($net_profit < 0)?100:0);
                        $w_losts_percent = (empty($exist_case))?0:($exist_case - (($w_losts_percent > 100)?100:$w_losts_percent))/$exist_case;
                        $w_losts_result = $w_losts_percent*$weight->i_work_lost;
                        if($w_losts_result > $weight->i_work_lost){
                            $w_losts_result = $weight->i_work_lost;
                        }
                        $w_losts_color = '#000';
                    }else{
                        $null_weight += $weight->i_work_lost;
                        $w_losts_percent = null;
                        $w_losts_result = null;
                        $w_losts_color = '#e65252';
                    }

                    if($null_weight > 0){
                        $full_weight = $weight->i_out_of + $weight->i_work_lost + $weight->i_likvid_credit + $weight->i_likvid_active + $weight->i_active_likvid + $weight->i_net_profit + $weight->i_income_expense + $weight->i_b_liability + $weight->i_b_liability_demand;
                        $extra_weight = $full_weight - $null_weight;
                        $all_rating = $out_of_result + $l_active_result + $l_credit_result + $b_liability_result + $b_demand_result + $net_result + $a_l_result + $i_i_e_result + $w_losts_result;
                        $percent_weight = (!empty($extra_weight))?$all_rating/$extra_weight:0;
                        // redaclare final result after some departments has not checked
                        if(isset($out_of_percent)){
                            if($out_of_percent < 0.05001 && $out_of_percent >= 0){
                                $out_of_result = 100*($weight->i_out_of/100);
                            }elseif($out_of_percent >= 0.10001 && $out_of_percent <= 0.50099){
                                $out_of_result = 95*($weight->i_out_of/100);
                            }elseif($out_of_percent > 0.50001 && $out_of_percent < 1.00099){
                                $out_of_result = 90*($weight->i_out_of/100);
                            }elseif($out_of_percent >= 1.00001 && $out_of_percent < 2.00099){
                                $out_of_result = 80*($weight->i_out_of/100);
                            }elseif($out_of_percent >= 2.00001 && $out_of_percent < 3.00099){
                                $out_of_result = 70*($weight->i_out_of/100);
                            }elseif($out_of_percent >= 3.00001 && $out_of_percent < 4.00099){
                                $out_of_result = 60*($weight->i_out_of/100);
                            }elseif($out_of_percent >= 4.00001 && $out_of_percent < 5.00099){
                                $out_of_result = 50*($weight->i_out_of/100);
                            }elseif($out_of_percent >= 5){
                                $out_of_result = 0.00001*($weight->i_out_of/100);
                            }
                            if($out_of_result > $weight->i_out_of){
                                $out_of_result = $weight->i_out_of;
                            }  
                        }else{
                            $out_of_result = $weight->i_out_of*$percent_weight;
                        }
                        if(isset($l_active_percent)){
                            $l_active_result = $l_active_percent*($weight->i_likvid_active/100);
                            if($l_active_result > $weight->i_likvid_active){
                                $l_active_result = $weight->i_likvid_active;
                            }
                        }else{
                            $l_active_result = $weight->i_likvid_active*$percent_weight;
                        }
                        if(isset($l_credit_percent)){
                            $l_credit_result = $l_credit_percent*($weight->i_likvid_credit/100);
                            if($l_credit_result > $weight->i_likvid_credit){
                                $l_credit_result = $weight->i_likvid_credit;
                            }
                        }else{
                            $l_credit_result = $weight->i_likvid_credit*$percent_weight;
                        }
                        if(isset($b_liability_percent)){
                            $b_liability_result = $b_liability_percent*($weight->i_b_liability/100);
                            if($b_liability_result > $weight->i_b_liability){
                                $b_liability_result = $weight->i_b_liability;
                            }
                        }else{
                            $b_liability_result = $weight->i_b_liability*$percent_weight;
                        }
                        if(isset($b_demand_percent)){
                            $b_demand_result = $b_demand_percent*($weight->i_b_liability_demand/100);
                            if($b_demand_result > $weight->i_b_liability_demand){
                                $b_demand_result = $weight->i_b_liability_demand;
                            }
                        }else{
                            $b_demand_result = $weight->i_b_liability_demand*$percent_weight;
                        }
                        if(isset($net_percent)){
                            $net_result = $net_percent*($weight->i_net_);
                            if($net_result > $weight->i_net_profit){
                                $net_result = $weight->i_net_profit;
                            }
                        }else{
                            $net_result = $weight->i_net_profit*$percent_weight;
                        }
                        if(isset($a_l_percent)){
                            $a_l_result = $a_l_percent*($weight_a_l);
                            if($a_l_result > $weight_a_l){
                                $a_l_result = $weight_a_l;
                            }
                        }
                        if(isset($i_i_e_percent)){
                            $i_i_e_result = $i_i_e_percent*($weight->i_income_expense/100);
                            if($i_i_e_result > $weight->i_income_expense){
                                $i_i_e_result = $weight->i_income_expense;
                            }
                        }else{
                            $i_i_e_result = $weight->i_income_expense*$percent_weight;
                        }
                        if(isset($w_losts_percent)){
                            $w_losts_result = $w_losts_percent*$weight->i_work_lost;
                            if($w_losts_result > $weight->i_work_lost){
                                $w_losts_result = $weight->i_work_lost;
                            }
                        }else{
                            $w_losts_result = $weight->i_work_lost*$percent_weight;
                        }
                    }else{
                        if($out_of_percent < 0.1000 && $out_of_percent >= 0){
                            $out_of_result = 100*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 0.10001 && $out_of_percent <= 0.50099){
                            $out_of_result = 95*($weight->i_out_of/100);
                        }elseif($out_of_percent > 0.50001 && $out_of_percent < 1.00099){
                            $out_of_result = 90*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 1.00001 && $out_of_percent < 2.00099){
                            $out_of_result = 80*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 2.00001 && $out_of_percent < 3.00099){
                            $out_of_result = 70*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 3.00001 && $out_of_percent < 4.00099){
                            $out_of_result = 60*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 4.00001 && $out_of_percent < 5.00099){
                            $out_of_result = 50*($weight->i_out_of/100);
                        }elseif($out_of_percent >= 5){
                            $out_of_result = 0.00001*($weight->i_out_of/100);
                        } 
                        $l_active_result = $l_active_percent*($weight->i_likvid_active/100);
                        $l_credit_result = $l_credit_percent*($weight->i_likvid_credit/100);
                        $b_liability_result = $b_liability_percent*($weight->i_b_liability/100);
                        $b_demand_result = $b_demand_percent*($weight->i_b_liability_demand/100);
                        $net_result = $net_percent*($weight->i_net_profit);
                        $a_l_result = $a_l_percent*($weight->i_active_likvid);
                        $i_i_e_result = $i_i_e_percent*($weight->i_income_expense/100);
                        $w_losts_result = $w_losts_percent*$weight->i_work_lost;

                        if($out_of_result > $weight->i_out_of){
                            $out_of_result = $weight->i_out_of;
                        }
                        if($l_active_result > $weight->i_likvid_active){
                            $l_active_result = $weight->i_likvid_active;
                        }
                        if($l_credit_result > $weight->i_likvid_credit){
                            $l_credit_result = $weight->i_likvid_credit;
                        }
                        if($b_liability_result > $weight->i_b_liability){
                            $b_liability_result = $weight->i_b_liability;
                        }
                        if($b_demand_result > $weight->i_b_liability_demand){
                            $b_demand_result = $weight->i_b_liability_demand;
                        }
                        if($net_result > $weight->i_net_profit){
                            $net_result = $weight->i_net_profit;
                        }
                        if($a_l_result > $weight->i_active_likvid){
                            $a_l_result = $weight->i_active_likvid;
                        }
                        if($i_i_e_result > $weight->i_income_expense){
                            $i_i_e_result = $weight->i_income_expense;
                        }
                        if($w_losts_result > $weight->i_work_lost){
                            $w_losts_result = $weight->i_work_lost;
                        }


                    }
                    $out_of_report = array(
                        'percent' => round($out_of_percent, 2),
                        'final_result' => round($out_of_result, 2),
                        'color' => $out_of_color
                    );
                    $l_a_report = array(
                        'percent' => round($l_active_percent, 2),
                        'final_result' => round($l_active_result, 2),
                        'color' => $l_active_color
                    );
                    $l_c_report = array(
                        'percent' => round($l_credit_percent, 2),
                        'final_result' => round($l_credit_result, 2),
                        'color' => $l_credit_color
                    );
                    $b_l_report = array(
                        'percent' => round($b_liability_percent, 2),
                        'final_result' => round($b_liability_result, 2),
                        'color' => $b_liability_color
                    );
                    $b_d_report = array(
                        'percent' => round($b_demand_percent, 2),
                        'final_result' => round($b_demand_result, 2),
                        'color' => $b_demand_color
                    );
                    $net_report = array(
                        'percent' => round($net_percent, 2),
                        'final_result' => round($net_result, 2),
                        'color' => $net_color
                    );
                    $a_l_report = array(
                        'percent' => round($a_l_percent, 2),
                        'final_result' => round($a_l_result, 2),
                        'color' => $a_l_color
                    );
                    $i_e_report = array(
                        'percent' => round($i_i_e_percent, 2),
                        'final_result' => round($i_i_e_result, 2),
                        'color' => $i_i_e_color
                    );
                    $w_losts_report = array(
                        'percent' => round($w_losts_percent, 2),
                        'final_result' => round($w_losts_result, 2),
                        'color' => $w_losts_color
                    );
                    if(!empty($cal->i_others)){
                        $i_others = json_decode($cal->i_others);
                        $i_o_result = ($out_of_result + $l_active_result + $l_credit_result + $b_liability_result + $b_demand_result + $net_result + $a_l_result + $i_i_e_result + $w_losts_result)*0.05;
                        $i_o_result = $i_o_result + $i_others->final_result; 
                        $i_others = array(
                            'final_result' => $i_others->$i_o_result
                        );
                    }else{
                        $i_o_result = ($out_of_result + $l_active_result + $l_credit_result + $b_liability_result + $b_demand_result + $net_result + $a_l_result + $i_i_e_result + $w_losts_result)*0.05;
                        $i_others = array(
                            'final_result' => $i_o_result
                        );
                    }
                    
                   
                    $mfo_id = $cal->mfo_id;
                    $bank_id = $cal->bank_id;
                    $weight_id = $weight->id;
                    $out_of_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'i_out_of', $out_of_report);
                    $l_a_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'i_likvid_active', $l_a_report);
                    $l_c_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'i_likvid_credit', $l_c_report);
                    $b_l_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'i_b_liability', $b_l_report);
                    $b_d_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'i_b_liability_demand', $b_d_report);
                    $net_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'i_net_profit', $net_report);
                    $a_l_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'i_active_likvid', $a_l_report);
                    $i_e_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'i_income_expense', $i_e_report);
                    $w_losts_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'i_work_lost', $w_losts_report);
                    $i_others = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'i_others', $i_others);
                }
                return true;
            }else{
                return false;
            }
        }
    }

    if(!function_exists('business_report')){
        function business_report($monthyear, $data = null){
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            
            $table_data = 'data_'.$year;
            $table_report = 'report_'.$year;
            if(isset($data)){
                
                foreach ($data as $cal) {
                    $null_weight = 0;
                    $b_home = json_decode($cal->b_home);
                    $b_kontur = json_decode($cal->b_kontur);
                    $b_family = json_decode($cal->b_family);
                    $b_guarantee = json_decode($cal->b_guarantee);
                    $b_past = json_decode($cal->b_past);
                    $b_m_report = json_decode($cal->b_m_report);
                    $b_execution = json_decode($cal->b_execution);
                    $array_filter = (object) array_filter((array) $b_home, 'strlen');
                    if(count((array) $array_filter) > 1){
                        $exist_case = $b_home->exist_case;
                        $home_percent = ($exist_case - ($b_home->credit_50 + $b_home->credit_50_60 + $b_home->credit_60_70 + $b_home->credit_70_80 + $b_home->credit_80_90 + $b_home->credit_90_100));
                        $home_result = $home_percent * ($weight->b_home/100);
                        if($home_result > $weight->b_home){
                            $home_result = $weight->b_home;
                        }  
                        $home_color = '#000';

                    }else{
                        $null_weight += $weight->b_home;
                        $home_percent = null;
                        $home_result = null;
                        $home_color = '#e65252';
                    }
                    $array_filter = (object) array_filter((array) $b_kontur, 'strlen');
                    if(count((array) $array_filter) > 1){
                        $exist_case = $b_kontur->exist_case;
                        $kontur_percent = ($exist_case - ($b_kontur->credit_50 + $b_kontur->credit_50_60 + $b_kontur->credit_60_70 + $b_kontur->credit_70_80 + $b_kontur->credit_80_90 + $b_kontur->credit_90_100));
                        $kontur_result = $kontur_percent*($weight->b_kontur/100);
                        if($kontur_result > $weight->b_kontur){
                            $kontur_result = $weight->b_kontur;
                        }
                        $kontur_color = '#000';
                    }else{
                        $null_weight += $weight->b_kontur;
                        $kontur_percent = null;
                        $kontur_result = null;
                        $kontur_color = '#e65252';
                    }
                    $array_filter = (object) array_filter((array) $b_family, 'strlen');
                    if(count((array) $array_filter) > 1){
                        $exist_case = $b_family->exist_case;
                        $family_percent = ($exist_case - ($b_family->credit_50 + $b_family->credit_50_60 + $b_family->credit_60_70 + $b_family->credit_70_80));
                        $family_result = $family_percent*($weight->b_family/100);
                        if($family_result > $weight->b_family){
                            $family_result = $weight->b_family;
                        }
                        $family_color = '#000';
                    }else{
                        $null_weight += $weight->b_family;
                        $family_percent = null;
                        $family_result = null;
                        $family_color = '#e65252';
                    }
                    $array_filter = (object) array_filter((array) $b_guarantee, 'strlen');
                    if(count((array) $array_filter) > 1){
                        $exist_case = $b_guarantee->exist_case;
                        $guarantee_percent = ($exist_case - ($b_guarantee->nocredit + $b_guarantee->threecredit + $b_guarantee->sixcredit));
                        $guarantee_result = $guarantee_percent*($weight->b_guarantee/100);
                        if($guarantee_result > $weight->b_guarantee){
                            $guarantee_result = $weight->b_guarantee;
                        }
                        $guarantee_color = '#000';
                    }else{
                        $null_weight += $weight->b_guarantee;
                        $guarantee_percent = null;
                        $guarantee_result = null;
                        $guarantee_color = '#e65252';
                    }
                    $array_filter = (object) array_filter((array) $b_past, 'strlen');
                    if(count((array) $array_filter) > 1){
                        $thismonth = $b_past->thismonth;
                        $lastthismonth = $b_past->lastthismonth;
                        $past_percent = (empty($lastthismonth))?0:(($thismonth/$lastthismonth)*100);
                        if($past_percent >= 120 || $past_percent == 0){
                            $past_result = 1*$weight->b_past;
                        }elseif($past_percent >= 100 && $past_percent < 120){
                            $past_result = 0.9*$weight->b_past;
                        }elseif($past_percent >= 80 && $past_percent < 100 ){
                            $past_result = 0.8*$weight->b_past;
                        }elseif($past_percent >= 60 && $past_percent < 80){
                            $past_result = 0.7*$weight->b_past;
                        }elseif($past_percent >= 0 && $past_percent < 60){
                            $past_result = 0.5*$weight->b_past;
                        }
                        if($past_result > $weight->b_past){
                            $past_result = $weight->b_past;
                        }
                        $past_color = '#000';
                    }else{
                        $null_weight += $weight->b_past;
                        $past_percent = null;
                        $past_result = null;
                        $past_color = '#e65252';
                    }
                    $array_filter = (object) array_filter((array) $b_m_report, 'strlen');
                    if(count((array) $array_filter) > 1){
                        $exist_case = $b_m_report->exist_case;
                        $poor_quality = $b_m_report->poor_quality;
                        $delayed = $b_m_report->delayed;
                        $not_provided = $b_m_report->not_provided;
                        $m_percent = $exist_case - ($poor_quality+$delayed+$not_provided);
                        $m_result = $m_percent*($weight->b_m_report/100);
                        if($m_result > $weight->b_m_report){
                            $m_result = $weight->b_m_report;
                        }
                        $m_color = '#000';
                    }else{
                        $null_weight += $weight->b_m_report;
                        $m_percent = null;
                        $m_result = null;
                        $m_color = '#e65252';
                    }
                    $array_filter = (object) array_filter((array) $b_execution, 'strlen');
                    if(count((array) $array_filter) > 1){
                        $exist_case = $b_execution->exist_case;
                        $poor_quality = $b_execution->poor_quality;
                        $delayed = $b_execution->delayed;
                        $not_provided = $b_execution->not_provided;
                        $execution_percent = $exist_case - ($poor_quality+$delayed+$not_provided);
                        $execution_result = $execution_percent*($weight->b_execution/100);
                        if($execution_result > $weight->b_execution){
                            $execution_result = $weight->b_execution;
                        }
                        $execution_color = '#000';
                    }else{
                        $null_weight += $weight->b_execution;
                        $execution_percent = null;
                        $execution_result = null;
                        $execution_color = '#e65252';
                    }
                    //checking are there some category exist
                    if($null_weight > 0){
                        $full_weight = $weight->b_home + $weight->b_kontur + $weight->b_family + $weight->b_guarantee + $weight->b_past + $weight->b_m_report + $weight->b_execution;
                        $extra_weight = $full_weight - $null_weight;
                        $all_rating = $execution_result + $m_result + $past_result + $guarantee_result + $family_result + $kontur_result + $home_result;
                        $percent_weight = ($extra_weight == 0)?0:$all_rating/$extra_weight;
                        // redaclare final result after some departments has not checked
                        if(isset($home_percent)){
                            $home_result = $home_percent * ($weight->b_home/100);
                            if($home_result > $weight->b_home){
                                $home_result = $weight->b_home;
                            }  
                        }else{
                            $home_result = $weight->b_home*$percent_weight;
                        }
                        if(isset($kontur_percent)){
                            $kontur_result = $kontur_percent*($weight->b_kontur/100);
                            if($kontur_result > $weight->b_kontur){
                                $kontur_result = $weight->b_kontur;
                            }
                        }else{
                            $kontur_result = $weight->b_kontur*$percent_weight;
                        }
                        if(isset($guarantee_percent)){
                            $guarantee_result = $guarantee_percent*($weight->b_guarantee/100);
                            if($guarantee_result > $weight->b_guarantee){
                                $guarantee_result = $weight->b_guarantee;
                            }
                        }else{
                            $guarantee_result = $weight->b_guarantee*$percent_weight;
                        }
                        if(isset($family_percent)){
                            $family_result = $family_percent*($weight->b_family/100);
                            if($family_result > $weight->b_family){
                                $family_result = $weight->b_family;
                            }
                        }else{
                            $family_result = $weight->b_family*$percent_weight;
                        }
                        if(isset($past_percent)){
                            if($past_percent >= 120 || $past_percent == 0){
                                $past_result = 1*$weight->b_past;
                            }elseif($past_percent >= 100 && $past_percent < 120){
                                $past_result = 0.9*$weight->b_past;
                            }elseif($past_percent >= 80 && $past_percent < 100 ){
                                $past_result = 0.8*$weight->b_past;
                            }elseif($past_percent >= 60 && $past_percent < 80){
                                $past_result = 0.7*$weight->b_past;
                            }elseif($past_percent >= 0 && $past_percent < 60){
                                $past_result = 0.5*$weight->b_past;
                            }
                            if($past_result > $weight->b_past){
                                $past_result = $weight->b_past;
                            }
                        }else{
                            $past_result = $weight->b_past*$percent_weight;
                        }
                        if(isset($m_percent)){
                            $m_result = $m_percent*($weight->b_m_report/100);
                            if($m_result > $weight->b_m_report){
                                $m_result = $weight->b_m_report;
                            }
                        }else{
                            $m_result = $weight->b_m_report*$percent_weight;
                        }
                        if(isset($execution_percent)){
                            $execution_result = $execution_percent*($weight->b_execution/100);
                            if($execution_result > $weight->b_execution){
                                $execution_result = $weight->b_execution;
                            }
                        }else{
                            $execution_result = $weight->b_execution*$percent_weight;
                        }
                    }else{
                        $home_result = $home_percent*($weight->b_home/100);
                        $kontur_result = $kontur_percent*($weight->b_kontur/100);
                        $family_result = $family_percent*($weight->b_family/100);
                        $guarantee_result = $guarantee_percent*($weight->b_guarantee/100);
                        if($past_percent >= 120  || $past_percent == 0){
                            $past_result = 1*$weight->b_past;
                        }elseif($past_percent >= 100 && $past_percent < 120){
                            $past_result = 0.9*$weight->b_past;
                        }elseif($past_percent >= 80 && $past_percent < 100 ){
                            $past_result = 0.8*$weight->b_past;
                        }elseif($past_percent >= 60 && $past_percent < 80){
                            $past_result = 0.7*$weight->b_past;
                        }elseif($past_percent >= 0 && $past_percent < 60){
                            $past_result = 0.5*$weight->b_past;
                        }
                        $m_result = $m_percent*($weight->b_m_report);
                        $execution_result = $execution_percent*($weight->b_execution/100);

                        if($home_result > $weight->b_home){
                            $home_result = $weight->b_home;
                        }
                        if($kontur_result > $weight->b_kontur){
                            $kontur_result = $weight->b_kontur;
                        }
                        if($family_result > $weight->b_family){
                            $family_result = $weight->b_family;
                        }
                        if($guarantee_result > $weight->b_guarantee){
                            $guarantee_result = $weight->b_guarantee;
                        }
                        if($past_result > $weight->b_past){
                            $past_result = $weight->b_past;
                        }
                        if($m_result > $weight->b_m_report){
                            $m_result = $weight->b_m_report;
                        }
                        if($execution_result > $weight->b_execution){
                            $execution_result = $weight->b_execution;
                        }
                    }
                    $home_report = array(
                        'percent' => round($home_percent, 2),
                        'final_result' => round($home_result, 2),
                        'color' => $home_color
                    );
                    $kontur_report = array(
                        'percent' => round($kontur_percent, 2),
                        'final_result' => round($kontur_result, 2),
                        'color' => $kontur_color
                    );
                    $family_report = array(
                        'percent' => round($family_percent, 2),
                        'final_result' => round($family_result, 2),
                        'color' => $family_color
                    );
                    $guarantee_report = array(
                        'percent' => round($guarantee_percent, 2),
                        'final_result' => round($guarantee_result, 2),
                        'color' => $guarantee_color
                    );
                    $past_report = array(
                        'percent' => round($past_percent, 2),
                        'final_result' => round($past_result, 2),
                        'color' => $past_color
                    );
                    $m_report = array(
                        'percent' => round($m_percent, 2),
                        'final_result' => round($m_result, 2),
                        'color' => $m_color
                    );
                    $execution_report = array(
                        'percent' => round($execution_percent, 2),
                        'final_result' => round($execution_result, 2),
                        'color' => $execution_color
                    );
                    $mfo_id = $cal->mfo_id;
                    $bank_id = $cal->bank_id;
                    $weight_id = $weight->id;
                    $home_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'b_home', $home_report);
                    $kontur_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'b_kontur', $kontur_report);
                    $guarantee_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'b_guarantee', $guarantee_report);
                    $family_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'b_family', $family_report);
                    $past_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'b_past', $past_report);
                    $m_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'b_m_report', $m_report);
                    $execution_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'b_execution', $execution_report);


                    
                }
                return true;
            }else{
                return false;
            }
        }
    }

    if(!function_exists('currency_report')){
        function currency_report($monthyear, $data = null){
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            
            $table_data = 'data_'.$year;
            $table_report = 'report_'.$year;
            if(isset($data)){
                
                foreach ($data as $cal) {
                    $null_weight = 0;
                    $c_check = json_decode($cal->c_check);
                    $c_m_report = json_decode($cal->c_m_report);
                    $c_execution = json_decode($cal->c_execution);
                    $c_phone = json_decode($cal->c_phone);
                    if(isset($c_check)){
                        $exist_case = $c_check->exist_case;
                        $vash = $c_check->vash;
                        $punkt = $c_check->punkt;
                        $check_percent = ($exist_case == 0)?0:(($exist_case - ($vash + $punkt))/$exist_case)*100;
                        $check_result = $check_percent*($weight->c_check/100);
                        $check_color = '#000';
                    }else{
                        $null_weight += $weight->c_check;
                        $check_percent = null;
                        $check_result = null;
                        $check_color = '#e65252';
                    }
                    if(isset($c_phone)){
                        $exist_case = $c_phone->exist_case;
                        $currency = $c_phone->currency;
                        $phone_percent = ($exist_case == 0)?0:(($exist_case-$currency)/$exist_case)*100;
                        $phone_result = $phone_percent*($weight->c_phone/100);
                        $phone_color = '#000';
                    }else{
                        $null_weight += $weight->c_phone;
                        $phone_percent = null;
                        $phone_result = null;
                        $phone_color = '#e65252';
                    }
                    if(isset($c_m_report)){
                        $exist_case = $c_m_report->exist_case;
                        $poor_quality = $c_m_report->poor_quality;
                        $delayed = $c_m_report->delayed;
                        $not_provided = $c_m_report->not_provided;
                        $report_percent = ($exist_case == 0)?0:(($exist_case - ($poor_quality+$delayed+$not_provided))/$exist_case)*100;
                        $report_result = $report_percent*($weight->c_m_report/100);
                        $report_color = '#000';
                    }else{
                        $null_weight += $weight->c_m_report;
                        $report_percent = null;
                        $report_result = null;
                        $report_color = '#e65252';
                    }

                    if(isset($c_execution)){
                        $exist_case = $c_execution->exist_case;
                        $poor_quality = $c_execution->poor_quality;
                        $delayed = $c_execution->delayed;
                        $not_provided = $c_execution->not_provided;
                        $execution_percent = ($exist_case == 0)?0:(($exist_case - ($poor_quality+$delayed+$not_provided))/$exist_case)*100;
                        $execution_result = $execution_percent*($weight->c_execution/100);
                        $execution_color = '#000';
                    }else{
                        $null_weight += $weight->c_execution;
                        $execution_percent = null;
                        $execution_result = null;
                        $execution_color = '#e65252';
                    }
                    //checking are there some category exist
                    if($null_weight > 0){
                        $full_weight = $weight->c_check + $weight->c_phone + $weight->c_m_report + $weight->c_execution;
                        $extra_weight = $full_weight - $null_weight;
                        $all_rating = $execution_result + $report_result + $phone_result + $check_result;
                        $percent_weight = ($extra_weight == 0)?0:$all_rating/$extra_weight;
                        // redaclare final result after some departments has not checked
                        if(isset($check_percent)){
                            $check_result = $check_percent * ($weight->c_check/100);
                            if($check_result > $weight->c_check){
                                $check_result = $weight->c_check;
                            }
                            
                        }else{
                            $check_result = $weight->c_check*$percent_weight;
                            
                        }
                        if(isset($phone_percent)){
                            $phone_result = $phone_percent*($weight->c_phone/100);
                            if($phone_result > $weight->c_phone){
                                $phone_result = $weight->c_phone;
                            }
                            
                        }else{
                            $phone_result = $weight->c_phone*$percent_weight;
                            
                        }
                        if(isset($report_percent)){
                            $report_result = $report_percent*($weight->c_m_report/100);
                            if($report_result > $weight->c_m_report){
                                $report_result = $weight->c_m_report;
                            }
                            
                        }else{
                            $report_result = $weight->c_m_report*$percent_weight;
                            
                        }
                        if(isset($execution_percent)){
                            $execution_result = $execution_percent*($weight->c_execution/100);
                            if($execution_result > $weight->c_execution){
                                $execution_result = $weight->c_execution;
                            }
                            
                        }else{
                            $execution_result = $weight->c_execution*$percent_weight;
                            
                        }
                    }else{
                        $check_result = $check_percent*($weight->c_check/100);
                        $phone_result = $phone_percent*($weight->c_phone/100);
                        $report_result = $report_percent*($weight->c_m_report/100);
                        $execution_result = $execution_percent*($weight->c_execution/100);

                        if($check_result > $weight->c_check){
                            $check_result = $weight->c_check;
                        }
                        if($phone_result > $weight->c_phone){
                            $phone_result = $weight->c_phone;
                        }
                        if($report_result > $weight->c_m_report){
                            $report_result = $weight->c_m_report;
                        }
                        if($execution_result > $weight->c_execution){
                            $execution_result = $weight->c_execution;
                        }
                    }
                    $check_report = array(
                        'percent' => round($check_percent, 2),
                        'final_result' => round($check_result, 2),
                        'color' => $check_color
                    );
                    $phone_report = array(
                        'percent' => round($phone_percent, 2),
                        'final_result' => round($phone_result, 2),
                        'color' => $phone_color
                    );
                    $m_report = array(
                        'percent' => round($report_percent, 2),
                        'final_result' => round($report_result, 2),
                        'color' => $report_color
                    );
                    $execution_report = array(
                        'percent' => round($execution_percent, 2),
                        'final_result' => round($execution_result, 2),
                        'color' => $execution_color
                    );
                    $mfo_id = $cal->mfo_id;
                    $bank_id = $cal->bank_id;
                    $weight_id = $weight->id;
                    $check_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'c_check', $check_report);
                    $phone_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'c_phone', $phone_report);
                    $m_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'c_m_report', $m_report);
                    $execution_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'c_execution', $execution_report);
                }
                return true;
            }else{
                return false;
            }
        }
    }

    if(!function_exists('ijro_report')){
        function ijro_report($monthyear, $data = null){
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            $table_data = 'data_'.$year;
            $table_report = 'report_'.$year;
            if(isset($data)){
                foreach ($data as $cal) {
                    $null_weight = 0;
                    $ijro = json_decode($cal->ijro);
                    if(isset($ijro)){
                        $report = (object) array_filter((array) $ijro, 'strlen');
                        if(!empty($report)){
                            $meeting = $ijro->meeting_execution;
                            $letter = $ijro->letter_execution;
                            $head_number = $ijro->head_number;
                            $people_qabul = $ijro->people_qabul;
                            $prime_number = $ijro->prime_number;
                            $out_of_number = $ijro->out_of_number;
                            //checking are there some category exist
                            $negative = ($head_number + $people_qabul + $prime_number + $out_of_number)*-1;
                            $positive = $meeting + $letter;
                            $ijro_report = array(
                                'negative' => $negative,
                                'positive' => $positive
                            );
                        }else{
                            $ijro_report = null;
                        }
                        
                    	
	                    $mfo_id = $cal->mfo_id;
	                    $bank_id = $cal->bank_id;
	                    $weight_id = $weight->id;
	                    $ijro_apparati = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'ijro_apparati', $ijro_report);
                    } 
                }
                return true;
            }else{
                return false;
            }
        }
    }

    if(!function_exists('all_report')){
        function all_report($monthyear, $data){
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $weight = DB::table('weight_of_reports')->where([['year', '=', $year], ['month', '<=', $month]])->orderBy('month', 'desc')->get()->first();
            $weight_id = $weight->id;
            $table_data = 'data_'.$year;
            $table_report = 'report_'.$year;
            $report = DB::table($table_report)->
            where([['month', '=', $month], ['year', '=', $year]])->
            get()->last();
            if(!isset($report->inspeksiya)){
                inspeksiya_report($monthyear, $data);
            }
            if(!isset($report->business)){
                business_report($monthyear, $data);
            }
            if(!isset($report->cash)){
                cash_report($monthyear, $data);
            }
            if(!isset($report->currency)){
                currency_report($monthyear, $data);
            }
            if(!isset($report->ijro)){
                ijro_report($monthyear, $data);
            }
            $reports = DB::table($table_report)->
                where([['month', '=', $month], ['year', '=', $year]])->
            get()->toArray();
            if(!empty($reports)){
                $i_colors = array(
                    'i_out_of',
                    'i_work_lost',
                    'i_likvid_active',
                    'i_likvid_credit',
                    'i_b_liability',
                    'i_b_liability_demand',
                    'i_net_profit',
                    'i_active_likvid',
                    'i_income_expense'
                );
                $b_colors = array(
                    'b_past',
                    'b_guarantee',
                    'b_family',
                    'b_kontur',
                    'b_home' ,
                    'b_execution',
                    'b_m_report'
                );
                $cash_colors = array(
                    'cash_tushum',
                    'cash_qaytish',
                    'cash_execution',
                    'cash_m_report'
                );
                $c_colors = array(
                    'c_check',
                    'c_phone',
                    'c_execution',
                    'c_m_report'
                );
            	foreach ($reports as $report) {
                    $report_original = $report;
            		$mfo_id = $report->mfo_id;
                    $bank_id = $report->bank_id;
            		$i_report = array(
            			'i_out_of' => $report->i_out_of?json_decode($report->i_out_of)->final_result:0,
            			'i_work_lost' => $report->i_work_lost?json_decode($report->i_work_lost)->final_result:0,
            			'i_likvid_active' => $report->i_likvid_active?json_decode($report->i_likvid_active)->final_result:0,
            			'i_likvid_credit' => $report->i_likvid_credit?json_decode($report->i_likvid_credit)->final_result:0,
            			'i_b_liability' => $report->i_b_liability?json_decode($report->i_b_liability)->final_result:0,
            			'i_b_liability_demand' => $report->i_b_liability_demand?json_decode($report->i_b_liability_demand)->final_result:0,
            			'i_net_profit' => $report->i_net_profit?json_decode($report->i_net_profit)->final_result:0,
            			'i_active_likvid' => $report->i_active_likvid?json_decode($report->i_active_likvid)->final_result:0,
            			'i_income_expense' => $report->i_income_expense?json_decode($report->i_income_expense)->final_result:0,
            			'i_others' => $report->i_others?json_decode($report->i_others)->final_result:0
            		);
            		$b_report = array(
            			'b_past' => $report->b_past?json_decode($report->b_past)->final_result:0,
            			'b_guarantee' => $report->b_guarantee?json_decode($report->b_guarantee)->final_result:0,
            			'b_family' => $report->b_family?json_decode($report->b_family)->final_result:0,
            			'b_kontur' => $report->b_kontur?json_decode($report->b_kontur)->final_result:0,
            			'b_home' => $report->b_home?json_decode($report->b_home)->final_result:0,
            			'b_execution' => $report->b_execution?json_decode($report->b_execution)->final_result:0,
            			'b_m_report' => $report->b_m_report?json_decode($report->b_m_report)->final_result:0
            		);
            		$cash_report = array(
            			'cash_tushum' => $report->cash_tushum?json_decode($report->cash_tushum)->final_result:0,
            			'cash_qaytish' => $report->cash_qaytish?json_decode($report->cash_qaytish)->final_result:0,
            			'cash_execution' => $report->cash_execution?json_decode($report->cash_execution)->final_result:0,
            			'cash_m_report' => $report->cash_m_report?json_decode($report->cash_m_report)->final_result:0
            		);
            		$c_report = array(
            			'c_check' => $report->c_check?json_decode($report->c_check)->final_result:0,
            			'c_phone' => $report->c_phone?json_decode($report->c_phone)->final_result:0,
            			'c_execution' => $report->c_execution?json_decode($report->c_execution)->final_result:0,
            			'c_m_report' => $report->c_m_report?json_decode($report->c_m_report)->final_result:0
            		);
            		$ijro_report = array(
            			'ijro' => $report->ijro_apparati?json_decode($report->ijro_apparati):0
            		);
            		$null_weight = 0;
            		$report = (object) array_filter((array) $i_report, 'strlen');
                    if(count((array) $report)){
                        $i_percent = round(array_sum((array) $report), 2);
                        $i_result = round(($i_percent*$weight->inspeksiya/100), 2);
                        $i_color = '#000';
                        foreach($i_colors as $i_item){
                            foreach($report_original as $original_name => $original){
                                if($i_item == $original_name){
                                    $json_original = json_decode($original);
                                    if($json_original->color == '#e65252' || empty($json_original)){
                                        $i_color = '#0a3cef';
                                    }
                                }
                            }
                        }
                    }else{
                    	$null_weight += $weight->inspeksiya;
                        $i_percent = null;
                        $i_result = null;
                        $i_color = '#e65252';
                    }
                    $report = (object) array_filter((array) $b_report, 'strlen');
                    if(count((array) $report)){
                        $b_percent = round(array_sum((array) $report), 2);
                        $b_result = round(($b_percent*$weight->business/100), 2);
                        $b_color = '#000';
                        foreach($b_colors as $b_item){
                            foreach($report_original as $original_name => $original){
                                if($b_item == $original_name){
                                    $json_original = json_decode($original);
                                    if($json_original->color == '#e65252'){
                                        $b_color = '#0a3cef';
                                    }
                                }
                            }
                        }
                        
                    }else{
                    	$null_weight += $weight->business;
                        $b_percent = null;
                        $b_result = null;
                        $b_color = '#e65252';
                    }
                    $report = (object) array_filter((array) $cash_report, 'strlen');
                    if(count((array) $report)){
                        $cash_percent = round((array_sum((array) $report)), 2);
                        $cash_result = round(($cash_percent*$weight->cash/100), 2);
                        $cash_color = '#000';
                        foreach($cash_colors as $cash_item){
                            foreach($report_original as $original_name => $original){
                                if($cash_item == $original_name){
                                    $json_original = json_decode($original);
                                    if($json_original->color == '#e65252'){
                                        $cash_color = '#0a3cef';
                                    }
                                }
                            }
                        }
                    }else{
                    	$null_weight += $weight->cash;
                        $cash_percent = null;
                        $cash_result = null;
                        $cash_color = '#e65252';
                    }

                    $report = (object) array_filter((array) $c_report, 'strlen');
                    if(count((array) $report)){
                        $c_percent = round(array_sum((array) $report), 2);
                        $c_result = round(($c_percent*$weight->currency/100), 2);
                        $c_color = '#000';
                        foreach($c_colors as $c_item){
                            foreach($report_original as $original_name => $original){
                                if($c_item == $original_name){
                                    $json_original = json_decode($original);
                                    if($json_original->color == '#e65252'){
                                        $c_color = '#0a3cef';
                                    }
                                }
                            }
                        }
                    }else{
                    	$null_weight += $weight->currency;
                        $c_percent = null;
                        $c_result = null;
                        $c_color = '#e65252';
                    }
                    $report = $ijro_report['ijro'];
                    if(!empty($report)){
                        $ijro_percent = round(array_sum((array) $report), 2);
                        $ijro_result = round(($ijro_percent*($weight->ijro_head/100)), 2);
                        if($ijro_result > $weight->ijro_head){
                            $ijro_result = $weight->ijro_head;
                        }
                        $ijro_color = '#000';
                    }else{
                    	$null_weight += $weight->ijro_head;
                        $ijro_percent = null;
                        $ijro_result = null;
                        $ijro_color = '#e65252';
                    }
                    if($null_weight > 0){
                    	$full_weight = $weight->cash + $weight->inspeksiya + $weight->business + $weight->currency + $weight->ijro_head;
                        $extra_weight = $full_weight - $null_weight;
                        $all_rating = $ijro_result + $i_result + $b_result + $cash_result + $c_result;
                        $percent_weight = ($extra_weight == 0)?0:($all_rating/$extra_weight);
                        if(isset($i_percent)){
                        	$i_result = round(($i_percent*$weight->inspeksiya/100), 2);
                        }else{
                            $i_result = $weight->inspeksiya*$percent_weight;
                        }
                        if(isset($b_percent)){
                        	$b_result = round(($b_percent*$weight->business/100), 2);
                        }else{
                            $b_result = $weight->business*$percent_weight;
                        }
                        if(isset($cash_percent)){
                        	$cash_result = round(($cash_percent*$weight->cash/100), 2);
                        }else{
                            $cash_result = $weight->cash*$percent_weight;
                        }
                        if(isset($c_percent)){
                        	$c_result = round(($c_percent*$weight->currency/100), 2);
                        }else{
                            $c_result = $weight->currency*$percent_weight;
                        }
                        if(isset($ijro_percent)){
                        	$ijro_result = round(($ijro_percent*$weight->ijro_head/100), 2);
                        }else{
                            $ijro_result = $weight->ijro_head*$percent_weight;
                        }
                    }
                	$i_average = array(
                		'percent' => $i_percent,
                        'final_result' => $i_result,
                        'color' => $i_color
                	);
                	$b_average = array(
                		'percent' => $b_percent,
                		'final_result' => $b_result,
                        'color' => $b_color
                	);
                	$cash_average = array(
                		'percent' => $cash_percent,
                		'final_result' => $cash_result,
                        'color' => $cash_color
                	);
                	$c_average = array(
                		'percent' => $c_percent,
                		'final_result' => $c_result,
                        'color' => $c_color
                	);
                	$ijro_average = array(
                		'percent' => $ijro_percent,
                		'final_result' => $ijro_result,
                        'color' => $ijro_color
                    );
                    $final_rate = ($i_result + $b_result + $cash_result + $c_result + $ijro_result);
                    $i_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'inspeksiya', $i_average);
                    $b_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'business', $b_average);
                    $cash_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'cash', $cash_average);
                    $c_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'currency', $c_average);
                    $ijro_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'ijro', $ijro_average);
                    $final_report = insert_report($bank_id, $mfo_id, $weight_id, $year, $month, 'rate', $final_rate);
            	}
            	return true;
            }else{
            	return false;
            }
        }
    }

    if(!function_exists('number_digiting')){
        function number_digiting($number){
            if($number < 1){
                $number = number_format($number, 2);
            }elseif($number > 1 && $number < 10){
                $number = number_format($number, 2);
            }elseif($number > 10 && $number < 100){
                $number = number_format($number, 1);
            }elseif($number > 100 && $number < 1000){
                $number = number_format($number, 0);
            }
            return $number;
        }
    }

    if(!function_exists('number_formatting')){
        function number_formatting($number, $divide = null){
            if($divide == null){
                if($number > 1000 && $number < 1000000){
                    $out_put = $number/1000;
                    $divide = 1000;
                    $text = trans('app.ming');
                }elseif($number > 1000000 && $number < 1000000000){
                    $out_put = $number/1000000;
                    $divide = 1000000;
                    $text = trans('app.mln');
                }elseif($number > 1000000000 && $number < 1000000000000){
                    $out_put = $number/1000000000;
                    $divide = 1000000000;
                    $text = trans('app.mlrd');
                }elseif($number > 1000000000000 && $number < 1000000000000000){
                    $out_put = $number/1000000000000;
                    $divide = 1000000000000;
                    $text = trans('app.trln');
                }elseif($number > 1000000000000000 && $number < 1000000000000000000){
                    $out_put = $number/1000000000000000;
                    $divide = 1000000000000000;
                    $text = trans('app.trlrd');
                }elseif($number < 1000){
                    $out_put = $number;
                    $divide = 1;
                    $text = '';
                }
                $final_result = new stdClass;
                $final_result->number = $out_put;
                $final_result->text = $text;
                $final_result->dividing = $divide;
            }else{
                $out_put = $number/$divide;
                $final_result = new stdClass;
                $final_result->number = $out_put;
                $final_result->dividing = $divide;
            }
            
            return $final_result;
        }
    }

    if(!function_exists('currency_check')){
        function currency_check(){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $table_name = 'currency';
            $sql="CREATE TABLE IF NOT EXISTS ".$table_name." (
                id serial PRIMARY KEY,
                month INTEGER NOT NULL DEFAULT 0,
                year INTEGER NOT NULL DEFAULT 0,
                currency JSON NULL DEFAULT NULL,
                currency_date timestamp(0) NULL DEFAULT NULL,
                created_at timestamp(0) NULL DEFAULT NULL,
                updated_at timestamp(0) NULL DEFAULT NULL
            )";
            $grant = "GRANT bankpulse_bank TO bankpulse;";
            $conn->exec($sql);
            //$conn->exec($grant);
            
            return true;
        }
    }

    if(!function_exists('get_currency')){
        function get_currency($monthyear){
            $main_currencies = array(840, 978, 643, 826, 392, 756, 156);
            $year = date('Y', strtotime($monthyear));
            $month = date('m', strtotime($monthyear));
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $table_name = 'currency';
            if(Schema::hasTable($table_name)){
                $currency = DB::table($table_name)->where([['month', '=', $month], ['year', '=', $year]])->latest()->first();
                if(!empty($currency)){
                    
                    // $opts = array(
                    //     'http'=>array(
                    //       'method'=>"GET",
                    //       'header'=>"Accept-language: en\r\n" ."Cookie: foo=bar\r\n"
                    //     )
                    // );
                    
                    //$context = stream_context_create($opts);
                    $ch = curl_init('http://cbu.uz/oz/arkhiv-kursov-valyut/json/');
                    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $data = curl_exec($ch);
                    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if(curl_errno($ch) == 0 AND $http == 200){
                        //$currency1 = file_get_contents('http://cbu.uz/oz/arkhiv-kursov-valyut/json/', false, $context);
                        $currency1_date = date('Y-m-d H:i:s', strtotime(json_decode($data)[0]->Date));
                        if($currency1_date > $currency->currency_date){
                            $currency1 = json_encode($data);
                            $datetime = date('Y-m-d H:i:s');
                            $sql = "INSERT INTO currency (year, month, currency, currency_date, created_at, updated_at) VALUES('$year', '$month', '$currency1', '$currency1_date', '$datetime', '$datetime')";
                            $conn->exec($sql);
                            $currency = json_decode(json_decode($currency1));
                            $length = count($currency);
                            for($i = 0; $i < $length; $i++){
                                if(!in_array($currency[$i]->Code, $main_currencies)){
                                    unset($currency[$i]);
                                }
                            }
                            return $currency;
                        }else{
                            $currency = json_decode(json_decode($currency->currency));
                            $length = count($currency);
                            for($i = 0; $i < $length; $i++){
                                if(!in_array($currency[$i]->Code, $main_currencies)){
                                    unset($currency[$i]);
                                }
                            }
                            return $currency;
                        }
                    }else{
                        $currency = json_decode(json_decode($currency->currency));
                        $length = count($currency);
                        for($i = 0; $i < $length; $i++){
                            if(!in_array($currency[$i]->Code, $main_currencies)){
                                unset($currency[$i]);
                            }
                        }
                        return $currency;
                    }
                }else{
                    $ch = curl_init('http://cbu.uz/oz/arkhiv-kursov-valyut/json/');
                    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $data = curl_exec($ch);
                    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    $currency_date = date('Y-m-d H:i:s', strtotime(json_decode($data)[0]->Date));
                    $currency = json_encode($data);
                    $datetime = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO currency (year, month, currency, currency_date, created_at, updated_at) VALUES('$year', '$month', '$currency', '$currency_date', '$datetime', '$datetime')";
                    $conn->exec($sql); 
                    $currency = json_decode(json_decode($currency));
                    $length = count($currency);
                    for($i = 0; $i < $length; $i++){
                        if(!in_array($currency[$i]->Code, $main_currencies)){
                            unset($currency[$i]);
                        }
                    }
                    return $currency;   
                }
            }else{
                $ch = curl_init('http://cbu.uz/oz/arkhiv-kursov-valyut/json/');
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($ch);
                $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $currency_date = date('Y-m-d H:i:s', strtotime(json_decode($currency)[0]->Date));
                $currency = json_encode($data);
                $datetime = date('Y-m-d H:i:s');
                $sql = "INSERT INTO currency (year, month, currency, currency_date, created_at, updated_at) VALUES('$year', '$month', '$currency', '$currency_date', '$datetime', '$datetime')";
                $conn->exec($sql);    
                return json_decode($currency);
            }
        }
    }

    if(!function_exists('getColors')){
        function getColors(){
            $color = array("#047bf8","#24b314","#fbe4a0","#fd2785","#5bc0de","#9453fa","#1b3999","#7e6fff","#ffcc29","#ff2c53", "#209f84", "#ff5c00", "#450b5a", "#34c73b", "#3333ff", "#24365c", "#02a1fb", "#434f0b", "#2673fb", "#6813cc", "#28f19d", "#ff1149", "#ffad97", "#4d0348", "#bd4648", "#0174ff", "#ce3be0", "#f51e63", "#92c83f", "#fa4d25", "#6021d3");
            return $color;
        }
    }

    if(!function_exists('insertCache')){
        function insertCache($data, $type, $month, $year){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $datetime = date('Y-m-d H:i:s');
            $data = array(
                'data' => $data,
                'updated_at' => $datetime,
                'date_for_last_port' => "01.".($month + 1).".".$year
            );
            $data = json_encode($data);
            // checking data in database exist or not
            $tableName = 'cachings';
            
            $old = DB::table($tableName)->where([['year', '=', $year], ['month', '=', $month]])->get()->first();
            if(!empty($old)){
                $id = $old->id;
                $sql = "UPDATE $tableName SET $type='$data', updated_at='$datetime' WHERE id=$id"; 
            }else{
                $sql = "INSERT INTO $tableName (year, month, $type, created_at, updated_at) VALUES('$year', '$month', '$data', '$datetime', '$datetime')";
            }
            $output = $conn->exec($sql);
            
            return $output;
        }
    }

    if(!function_exists('getTopMainbank')){
        function getTopMainbank(){
            $user = Auth::user();
            $position = get_position($user);
            
            $current_month = intval(date('m'));
            $current_year = intval(date('Y'));
            $report_table = "report_".$current_year;
            $check = Schema::hasTable('report_'.$current_year);
            if($check){
                $check_existing = DB::table($report_table)->orderBy('id', 'desc')->get()->first();
            }else{
                $report_table = "report_".($current_year - 1);
                $check_existing = DB::table($report_table)->orderBy('id', 'desc')->get()->first();
            }
            $mainbanks = DB::table('banks')->
            select(
                DB::raw('count(banks.id) as number'),
                'banks.mainbank_id',
                'mainbanks.name',
                'mainbanks.photo',
                'mainbanks.logo'
            )->
            join('mainbanks', 'mainbanks.id', '=', 'banks.mainbank_id');
            $mainbanks = $mainbanks->where('banks.region_work_id', '=', $user->region_id)->
            where('mainbanks.id', '!=', 38)->groupBy('banks.mainbank_id', 'mainbanks.name', 'mainbanks.photo', 'mainbanks.logo')->
            get()->toArray();
            foreach ($mainbanks as $mainbank) {
                $mainbank->rating = 0;
            }
            $dep_array = DB::table('departments')->get()->toArray();
            if(!empty($check_existing)){
                $rating_banks = DB::table($report_table)->
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
                    $report_table.'.business',
                    $report_table.'.cash',
                    $report_table.'.currency',
                    $report_table.'.ijro',
                    'banks.mainbank_id'
                )->
                join('banks', 'banks.id', '=', $report_table.'.bank_id')->
                where('month', '=', $check_existing->month)->get()->toArray();
            }
            if(!empty($rating_banks)){
                foreach($rating_banks as $banks){
                    $rate = 0;
                    foreach ($dep_array as $dep) {
                        foreach ($banks as $key => $value) {
                            if($key == $dep->key){
                                if(!empty($value)){
                                    $rate = $rate + floatval(json_decode($value)->final_result);
                                }
                            }
                        }
                    }
                    foreach ($mainbanks as $mainbank) {
                        if($mainbank->mainbank_id == $banks->mainbank_id){
                            $mainbank->rating = $mainbank->rating + ($rate/$mainbank->number);
                        }
                    }
                }
            }
            foreach ($mainbanks as $key => $mainbank) {
                $rating[$key] = $mainbank->rating;
            }
            array_multisort($rating, SORT_DESC, $mainbanks);
            return $mainbanks;
        }
    }

    if(!function_exists('generateTopPortfolio')){
        function generateTopPortfolio(){
            $user = Auth::user();
            $position = get_position($user);
            cacheTable();
            $fillials = DB::table('banks')->where('banks.region_work_id', '=', $user->region_id)->get()->toArray();
            $goals = DB::table('goal_codes')->get()->toArray();
            $activities = DB::table('activity_codes')->get()->toArray();
            foreach ($fillials as $fillial) {
                $fillial->safe_credit = 0;
                $fillial->problem_credit = 0;
            }
            foreach ($activities as $activity) {
                $activity->safe_credit = 0;
                $activity->problem_credit = 0;
            }
            foreach ($goals as $goal) {
                $goal->safe_credit = 0;
                $goal->problem_credit = 0;
            }
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
            $portfolio_data = DB::table($portfolio_table)->
            where([['month', '=', $check_existing->month], ['year', '=', $check_existing->year]])->get()->toArray();
            foreach($portfolio_data as $portfolio){
                foreach ($fillials as $fillial) {
                    if($portfolio->mfo_id == $fillial->mfo_id){
                        //echo $portfolio->mfo_id." ";
                        $json = json_decode($portfolio->portfolio);
                        if($portfolio->status == 'safe'){
                            //echo $portfolio->mfo_id." ";
                            $fillial->safe_credit = $fillial->safe_credit + intval($json->remainder);
                        }
                        if($portfolio->status === 'problem'){
                            //echo $portfolio->mfo_id."-".$check_existing->month." ";
                            $fillial->safe_credit = $fillial->safe_credit + intval($json->remainder);
                            $fillial->problem_credit = $fillial->problem_credit + intval($json->out_of);
                        }
                    }
                }
                foreach ($activities as $activity) {
                    if($portfolio->activity_code == $activity->code){
                        $json = json_decode($portfolio->portfolio);
                        if($portfolio->status == 'safe'){
                            $activity->safe_credit = $activity->safe_credit + intval($json->remainder);
                        }elseif($portfolio->status == 'problem'){
                            $activity->safe_credit = $activity->safe_credit + intval($json->remainder);
                            $activity->problem_credit = $activity->problem_credit + intval($json->out_of);
                        }
                    }
                }
                foreach ($goals as $goal) {
                    if($portfolio->goal_code == $goal->code){
                        $json = json_decode($portfolio->portfolio);
                        if($portfolio->status == 'safe'){
                            $goal->safe_credit = $goal->safe_credit + intval($json->remainder);
                        }elseif($portfolio->status == 'problem'){
                            $goal->safe_credit = $goal->safe_credit + intval($json->remainder);
                            $goal->problem_credit = $goal->problem_credit + intval($json->out_of);
                        }
                    }
                }
            }
            $mfo_color = array(
                'mfo' => array(),
                'color' => array(),
                'last' => 0
            );
            $colors = getColors();
            foreach ($fillials as $key => $fillial) {
                $top_safe_fillial[] = $fillial;
                $top_problem_fillial[] = $fillial;
                $sortsafe[$key] = $fillial->safe_credit;
                $sortproblem[$key] = $fillial->problem_credit;
            }
            array_multisort($sortsafe, SORT_DESC, $top_safe_fillial);
            $length = count($top_safe_fillial);
            while($length > 10 ){
                array_pop($top_safe_fillial);
                $length = count($top_safe_fillial);
            }
            foreach($top_safe_fillial as $key => $top){
                if($key == 0){
                    $number_format_safe = number_formatting($top->safe_credit);
                    $top->safe_credit = $number_format_safe->number;
                    $top->safe_dividing = $number_format_safe->dividing;
                }else{
                    $top->safe_credit = number_formatting($top->safe_credit, $number_format_safe->dividing)->number;
                    $top->safe_dividing = $number_format_safe->dividing;
                }
                array_push($mfo_color['mfo'], $top->mfo_id);
                array_push($mfo_color['color'], $colors[$key]);
                $mfo_color['last'] = $key;
                $top->color = $colors[$key];
            }
            
            array_multisort($sortproblem, SORT_DESC, $top_problem_fillial);
            $length = count($top_problem_fillial);
            while($length > 10 ){
                array_pop($top_problem_fillial);
                $length = count($top_problem_fillial);
            }
            foreach($top_problem_fillial as $key => $top){
                if($key == 0){
                    $number_format_problem = number_formatting($top->problem_credit);
                    $top->problem_credit = $number_format_problem->number;
                    $top->problem_dividing = $number_format_problem->dividing;
                }else{
                    $top->problem_credit = number_formatting($top->problem_credit, $number_format_problem->dividing)->number;
                    $top->problem_dividing = $number_format_problem->dividing;
                }
                if(in_array($top->mfo_id, $mfo_color['mfo'])){
                    foreach($mfo_color as $m=> $mfo){
                        if($mfo == $top->mfo_id){
                            $top->color = $mfo_color['color'][$m];
                        }
                    }
                }else{
                    $top->color = $colors[$mfo_color['last']+1];
                    array_push($mfo_color['mfo'], $top->mfo_id);
                    array_push($mfo_color['color'], $colors[$key]);
                    $color_l = count($mfo_color['color']);
                    $mfo_color['last'] = $color_l;
                }
            }
            $top_safe_fillial_title = 'top fillial portfolio';
            $top_problem_fillial_title = 'top fillial problem portfolio';
            $top_safe_fillial_text = " (".$number_format_safe->text." UZS)";
            $top_problem_fillial_text = " (".$number_format_problem->text." UZS)";


            $active_color = array(
                'code' => array(),
                'color' => array(),
                'last' => 0
            );
            foreach ($activities as $key => $activity) {
                $top_safe_activity[] = $activity;
                $top_problem_activity[] = $activity;
                $sortactivitysafe[$key] = $activity->safe_credit;
                $sortactivityproblem[$key] = $activity->problem_credit;
            }
            array_multisort($sortactivitysafe, SORT_DESC, $top_safe_activity);
            $length = count($top_safe_activity);
            while($length > 10 ){
                array_pop($top_safe_activity);
                $length = count($top_safe_activity);
            }
            foreach($top_safe_activity as $key => $top){
                if($key == 0){
                    $number_format_safe = number_formatting($top->safe_credit);
                    $top->safe_credit = $number_format_safe->number;
                    $top->safe_dividing = $number_format_safe->dividing;
                }else{
                    $top->safe_credit = number_formatting($top->safe_credit, $number_format_safe->dividing)->number;
                    $top->safe_dividing = $number_format_safe->dividing;
                }
                array_push($active_color['code'], $top->code);
                array_push($active_color['color'], $colors[$key]);
                $active_color['last'] = $key;
                $top->color = $colors[$key];
            }


            array_multisort($sortactivityproblem, SORT_DESC, $top_problem_activity);
            $length = count($top_problem_activity);
            while($length > 10 ){
                array_pop($top_problem_activity);
                $length = count($top_problem_activity);
            }
            foreach($top_problem_activity as $key => $top){
                if($key == 0){
                    $number_format_problem = number_formatting($top->problem_credit);
                    $top->problem_credit = $number_format_problem->number;
                    $top->problem_dividing = $number_format_problem->dividing;
                }else{
                    $top->problem_credit = number_formatting($top->problem_credit, $number_format_problem->dividing)->number;
                    $top->problem_dividing = $number_format_problem->dividing;
                }
                if(in_array($top->code, $active_color['code'])){
                    foreach($active_color as $m=> $code){
                        if($code == $top->code){
                            $top->color = $active_color['color'][$m];
                        }
                    }
                }else{
                    $top->color = $colors[$active_color['last']+1];
                    array_push($active_color['code'], $top->code);
                    array_push($active_color['color'], $colors[$key]);
                    $color_l = count($active_color['color']);
                    $active_color['last'] = $color_l;
                }
            }


            $top_safe_activity_species_title = 'top activity species portfolio';
            $top_problem_activity_species_title = 'top activity species problem portfolio';
            $top_safe_activity_species_text = " (".$number_format_safe->text." UZS)";
            $top_problem_activity_species_text = " (".$number_format_problem->text." UZS)";

            foreach ($goals as $key => $goal) {
                $top_safe_goal[] = $goal;
                $top_problem_goal[] = $goal;
                $sortgoalsafe[$key] = $goal->safe_credit;
                $sortgoalproblem[$key] = $goal->problem_credit;
            }

            array_multisort($sortgoalsafe, SORT_DESC, $top_safe_goal);
            $length = count($top_safe_goal);
            while($length > 10 ){
                array_pop($top_safe_goal);
                $length = count($top_safe_goal);
            }
            $goal_color = array(
                'code' => array(),
                'color' => array(),
                'last' => 0
            );
            foreach($top_safe_goal as $key => $top){
                if($key == 0){
                    $number_format_safe = number_formatting($top->safe_credit);
                    $top->safe_credit = $number_format_safe->number;
                    $top->safe_dividing = $number_format_safe->dividing;
                }else{
                    $top->safe_credit = number_formatting($top->safe_credit, $number_format_safe->dividing)->number;
                    $top->safe_dividing = $number_format_safe->dividing;
                }
                array_push($goal_color['code'], $top->code);
                array_push($goal_color['color'], $colors[$key]);
                $goal_color['last'] = $key;
                $top->color = $colors[$key];
            }

            array_multisort($sortgoalproblem, SORT_DESC, $top_problem_goal);
            $length = count($top_problem_goal);
            while($length > 10 ){
                array_pop($top_problem_goal);
                $length = count($top_problem_goal);
            }
            foreach($top_problem_goal as $key => $top){
                if($key == 0){
                    $number_format_problem = number_formatting($top->problem_credit);
                    $top->problem_credit = $number_format_problem->number;
                    $top->problem_dividing = $number_format_problem->dividing;
                }else{
                    $top->problem_credit = number_formatting($top->problem_credit, $number_format_problem->dividing)->number;
                    $top->problem_dividing = $number_format_problem->dividing;
                }
                if(in_array($top->code, $goal_color['code'])){
                    foreach($goal_color as $m=> $code){
                        if($code == $top->code){
                            $top->color = $goal_color['color'][$m];
                        }
                    }
                }else{
                    $top->color = $colors[$goal_color['last']+1];
                    array_push($goal_color['code'], $top->code);
                    array_push($goal_color['color'], $colors[$key]);
                    $color_l = count($goal_color['color']);
                    $goal_color['last'] = $color_l;
                }
            }
            $top_safe_loan_species_title = 'top loans species portfolio';
            $top_problem_loan_species_title = 'top loans species problem portfolio';
            $top_safe_loan_species_text = " (".$number_format_safe->text." UZS)";
            $top_problem_loan_species_text = " (".$number_format_problem->text." UZS)";
            
            $top_portfolio_data = array(
                'top_fillial_portfolio' => array(
                    'safe_portfolio' => array(
                        'data' => $top_safe_fillial,
                        'title' => $top_safe_fillial_title,
                        'text' => $top_safe_fillial_text

                    ),
                    'problem_portfolio' =>array(
                        'data' => $top_problem_fillial,
                        'title' => $top_problem_fillial_title,
                        'text' => $top_problem_fillial_text
                    )
                ),
                'top_activity_portfolio' => array(
                    'safe_portfolio' => array(
                        'data' => $top_safe_activity,
                        'title' => $top_safe_activity_species_title,
                        'text' => $top_safe_activity_species_text
                    ),
                    'problem_portfolio' =>array(
                        'data' => $top_problem_activity,
                        'title' => $top_problem_activity_species_title,
                        'text' => $top_problem_activity_species_text
                    )
                ),
                'top_goal_portfolio' => array(
                    'safe_portfolio' => array(
                        'data' => $top_safe_goal,
                        'title' => $top_safe_loan_species_title,
                        'text' => $top_safe_loan_species_text
                    ),
                    'problem_portfolio' =>array(
                        'data' => $top_problem_goal,
                        'title' => $top_problem_loan_species_title,
                        'text' => $top_problem_loan_species_text
                    )
                )
            );
            insertCache($top_portfolio_data, 'top_portfolio_data', $check_existing->month, $check_existing->year);
            $data = DB::table('cachings')->where([['month', '=', $check_existing->month], ['year', '=', $check_existing->year]])->get()->first();
            $data = json_decode($data->top_portfolio_data);
            return $data;
        }
    }

    if(!function_exists('getTopPortfolio')){
        function getTopPortfolio(){
            $user = Auth::user();
            $position = get_position($user);
            $check = Schema::hasTable('cachings');
            if($check){
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
                $portfolio_data = DB::table($portfolio_table)->
                where([['month', '=', $check_existing->month], ['year', '=', $check_existing->year]])->latest()->first();
                $cache = DB::table('cachings')->where([['month', '=', $check_existing->month], ['year', '=', $check_existing->year]])->latest()->first();
                if(!empty($cache)){
                    $data = json_decode($cache->top_portfolio_data);
                    if(!empty($data)){
                        if($portfolio_data->updated_at <= $data->updated_at){
                            //return $data;
                            return generatetopPortfolio();
                        }else{
                            return generatetopPortfolio();
                        }
                    }else{
                        return generatetopPortfolio();
                    }
                }else{
                    return generatetopPortfolio();
                }
            }else{
                return generatetopPortfolio();
            }
        }
    }

    if(!function_exists('cacheTable')){
        function cacheTable(){
            $host = env('DB_HOST');
            $port = env('DB_PORT');
            $db_name = env('DB_DATABASE');
            $db_username = env('DB_USERNAME');
            $db_password = env('DB_PASSWORD');
            $conn = new PDO("pgsql:host=$host;dbname=$db_name;port=$port", "$db_username", "$db_password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $table_name = 'cachings';
            $sql="CREATE TABLE IF NOT EXISTS ".$table_name." (
                id serial primary key,
                month integer not null default 0,
                year integer not null default 0,
                rating_total jsonb null default null,
                rating_inspeksiya jsonb null default null,
                rating_business jsonb null default null,
                rating_ijro jsonb null default null,
                rating_currency jsonb null default null,
                rating_cash jsonb null default null,
                top_portfolio_data jsonb null default null,
                created_at timestamp(0) null default null,
                updated_at timestamp(0) null default null
            )";
            $grant = "GRANT bankpulse_bank TO bankpulse;";
            $conn->exec($sql);
            return true;
        }
    }

    if(!function_exists('checkVScache')){
        function checkVScache($data, $type, $month, $year){
            $check = Schema::hasTable('cachings');
            if($check){
                $cache = DB::table('cachings')->where([['month', '=', $month], ['year', '=', $year]])->latest()->first();
                if(!empty($cache)){ 
                    if($type == 'rating_inspeksiya'){
                        $cache_date = json_decode($cache->rating_inspeksiya);
                    }elseif($type == 'top_portfolio_data'){
                        $cache_date = json_decode($cache->top_portfolio_data);
                    }elseif($type == 'rating_cash'){
                        $cache_date = json_decode($cache->rating_cash);
                    }elseif($type == 'rating_business'){
                        $cache_date = json_decode($cache->rating_business);
                    }elseif($type == 'rating_currency'){
                        $cache_date = json_decode($cache->rating_currency);
                    }elseif($type == 'rating_ijro'){
                        $cache_date = json_decode($cache->rating_ijro);
                    }elseif($type == 'rating_total'){
                        $cache_date = json_decode($cache->rating_total);
                    }
                    if(!empty($cache_date)){
                        if($data->updated_at > $cache_date->updated_at){
                            return false;
                        }else{
                            return true;
                        }
                    }else{
                        return false;
                    }
                    
                }else{
                    return false;
                }
            }else{
                cacheTable();
                return false;
            }
        }
    }

    if(!function_exists('getBankInfo')){
        function getBankInfo($id, $type){
            $user = Auth::user();
            $position = get_position($user);
            $banks = DB::table('banks');
            if($type == 'main'){
                $banks = $banks->where('mainbank_id', '=', $id);
            }else if($type == 'fill'){
                $banks = $banks->where('id', '=', $id);
            }
            if($position == 'admin' || $position == 'country'){
                $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
            }else{
                $banks = $banks->where('banks.region_work_id', '=', $user->region_id);
            }
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
            // Xozirgi yil boshidan shu oygacha bolgan Kredit qoyilmalari va muammoli creditlar
            $current_year_sxema = DB::table($current_sxema_table)->
            select(
                $current_sxema_table.'.*'
            )->
            join('banks', 'banks.id', '=', $current_sxema_table.'.bank_id')->
            where([['banks.region_id', '=', $region_id], [$current_sxema_table.'.month', '<', $month]])->orderBy('month')->get()->toArray();


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
                }else{
                    array_push($data['data_current_month'], $current_data->month);
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
                        array_push($data['data_current_a_credit'], $all_credit);
                        array_push($data['data_current_p_credit'], $problem_credit);
                        array_push($data['data_current_actives'], $all_active);
                        array_push($data['data_current_deposit'], $all_deposit);
                        array_push($data['data_current_p_deposit'], $people_deposit);
                        array_push($data['data_current_a_likvid'], $active_likvids);
                        array_push($data['data_current_income'], $monthly_income);
                        array_push($data['data_current_expense'], $monthly_expense);
                    }else{
                        array_push($data['data_current_a_credit'], 0);
                        array_push($data['data_current_p_credit'], 0);
                        array_push($data['data_current_actives'], 0);
                        array_push($data['data_current_deposit'], 0);
                        array_push($data['data_current_p_deposit'], 0);
                        array_push($data['data_current_a_likvid'], 0);
                        array_push($data['data_current_income'], 0);
                        array_push($data['data_current_expense'], 0);
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
                }else{
                    array_push($data['data_last_month'], $last_data->month);
                    array_push($data['data_last_monthyear'], trans('app.shortmonth'.$last_data->month)." ".$last_data->year);
                    array_push($data['data_current_monthyear'], trans('app.shortmonth'.$last_data->month)." ".$current_year);
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
                        array_push($data['data_last_a_credit'], $all_credit);
                        array_push($data['data_last_p_credit'], $problem_credit);
                        array_push($data['data_last_actives'], $all_active);
                        array_push($data['data_last_deposit'], $all_deposit);
                        array_push($data['data_last_p_deposit'], $people_deposit);
                        array_push($data['data_last_a_likvid'], $active_likvids);
                        array_push($data['data_last_income'], $monthly_income);
                        array_push($data['data_last_expense'], $monthly_expense);
                    }else{
                        array_push($data['data_last_a_credit'], 0);
                        array_push($data['data_last_p_credit'], 0);
                        array_push($data['data_last_actives'], 0);
                        array_push($data['data_last_deposit'], 0);
                        array_push($data['data_last_p_deposit'], 0);
                        array_push($data['data_last_a_likvid'], 0);
                        array_push($data['data_last_income'], 0);
                        array_push($data['data_last_expense'], 0);
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
                }else{
                    array_push($data['sxema_current_month'], $current_sxema->month);
                    array_push($data['sxema_current_monthyear'], trans('app.shortmonth'.$current_sxema->month)." ".$current_sxema->year);
                    if(!empty($current_sxema->accounting)){

                        $income = 0; 
                        $expense = 0; 


                        $inserting_sxema = json_decode($current_sxema->accounting);
                        foreach ($inserting_sxema as $item) {
                            foreach ($inserting_sxema as $item) {
                                if($item->account_id >= 100 && $item->account_id <= 3200 && !empty($item)){
                                    $income += intval($item->amount);
                                }elseif($item->account_id >= 4001 && $item->account_id <= 5900 && !empty($item)){
                                    $expense += intval($item->amount);
                                }
                            }
                        }
                        array_push($data['data_current_kirim'], $income);
                        array_push($data['data_current_chiqim'], $expense);
                    }else{
                        array_push($data['data_current_kirim'], 0);
                        array_push($data['data_current_chiqim'], 0);
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
                }else{
                    array_push($data['sxema_last_month'], $last_sxema->month);
                    array_push($data['sxema_last_monthyear'], trans('app.shortmonth'.$last_sxema->month)." ".$last_sxema->year);
                    if(!empty($last_sxema->accounting)){

                        $income = 0; 
                        $expense = 0; 


                        $inserting_sxema = json_decode($last_sxema->accounting);
                        foreach ($inserting_sxema as $item) {
                            foreach ($inserting_sxema as $item) {
                                if($item->account_id >= 100 && $item->account_id <= 3200 && !empty($item)){
                                    $income += intval($item->amount);
                                }elseif($item->account_id >= 4001 && $item->account_id <= 5900 && !empty($item)){
                                    $expense += intval($item->amount);
                                }
                            }
                        }
                        array_push($data['data_last_kirim'], $income);
                        array_push($data['data_last_chiqim'], $expense);
                    }else{
                        array_push($data['data_last_kirim'], 0);
                        array_push($data['data_last_chiqim'], 0);
                    }
                }
            }
            return json_encode($data);
        }
    }

    if(!function_exists('getBankName')){
        function getBankName($mfo_or_id, $type){
            if($type == 'fill'){
                $bank = DB::table('banks')->where('mfo_id', '=', $mfo_or_id)->get()->first();
            }else{
                $bank = DB::table('mainbanks')->where('id', '=', $mfo_or_id)->get()->first();
            }
            return $bank->short_name??'';
        }
    }

    function count_digit($number)
    {
        return strlen((string) $number);
    }


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



?>