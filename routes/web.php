<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/getcity', 'BankController@getCity');
Route::get('/getmainbank', 'BankController@getMainbank');
Route::get('/getfillial', 'BankController@getFillial');
Route::get('/getaccountsheet', 'BankController@getAccountsheet');
Route::get('/getsubdepartment', 'BankController@getSubdepartment');
Route::get('/getheadbank', 'BankController@getHeadbank');
Route::get('/search/fillials', 'SearchController@search_fillials');
Route::group(['prefix'=>'bank', 'middleware'=>'auth'], function(){
    Route::get('/list/view/{id}/{type}', 'BankController@view');
    Route::get('/list', 'BankController@list');
    Route::get('/add', 'BankController@add');
    Route::post('/save', 'BankController@save');
    Route::get('/delete/{id}/{type}', 'BankController@delete');
    Route::get('/edit/{id}/{type}', 'BankController@edit');
    Route::post('/update/{id}/{type}', 'BankController@update');
});
Route::group(['prefix'=>'user', 'middleware'=>'auth'], function(){
    Route::get('/list', 'UserController@list');
    Route::get('/add', 'UserController@add');
    Route::post('/add', 'UserController@add');
    Route::get('/edit/{id}', 'UserController@add');
    Route::post('/update/{id}', 'UserController@update');
});
Route::group(['prefix'=>'region', 'middleware'=>'auth'], function(){
    Route::get('/list', 'RegionController@index');
    Route::get('/add', 'RegionController@create');
    Route::post('/save', 'RegionController@store');
});
Route::group(['prefix'=>'city', 'middleware'=>'auth'], function(){
    Route::get('/list', 'CityController@index');
    Route::get('/add', 'CityController@create');
    Route::post('/save', 'CityController@store');
});
Route::group(['prefix'=>'report', 'middleware'=>'auth'], function(){
    Route::get('/loan/table', 'ReportController@loans');
    Route::post('/loan/table', 'ReportController@loans');
    Route::get('/cash/table', 'ReportController@cash_report');
    Route::post('/cash/table', 'ReportController@cash_report');


    Route::get('/inspeksiya/table', 'ReportController@inspeksiya_report');
    Route::post('/inspeksiya/table', 'ReportController@inspeksiya_report');


    Route::get('/currency/table', 'ReportController@currency_report');
    Route::post('/currency/table', 'ReportController@currency_report');
    Route::get('/{department}/{type}/table', 'ReportController@rating_in');
    Route::post('/{department}/{type}/table', 'ReportController@rating_in');


    Route::get('/business/table', 'ReportController@business_report');
    Route::post('/business/table', 'ReportController@business_report');


    Route::get('/ijro/table', 'ReportController@ijro_report');
    Route::post('/ijro/table', 'ReportController@ijro_report');


    Route::get('/final/table', 'ReportController@final_report');
    Route::post('/final/table', 'ReportController@final_report');
    Route::get('/mainbanks/table', 'ReportController@mainbanks_report');
    Route::post('/mainbanks/table', 'ReportController@mainbanks_report');
    Route::get('/mainbank-cash/table', 'ReportController@mainbank_cash_report');
    Route::post('/mainbank-cash/table', 'ReportController@mainbank_cash_report');
    Route::get('/mainbank-inspeksiya/table', 'ReportController@mainbank_inspeksiya_report');
    Route::post('/mainbank-inspeksiya/table', 'ReportController@mainbank_inspeksiya_report');
    Route::get('/mainbank-business/table', 'ReportController@mainbank_business_report');
    Route::post('/mainbank-business/table', 'ReportController@mainbank_business_report');
    Route::get('/mainbank-currency/table', 'ReportController@mainbank_currency_report');
    Route::post('/mainbank-currency/table', 'ReportController@mainbank_currency_report');
});
Route::group(['prefix'=>'excel', 'middleware'=>'auth'], function(){
    Route::get('/fillial', 'ExcelImporterController@fillial_add');
    Route::post('/fillial', 'ExcelImporterController@fillial_add');
    Route::get('/account-sheet', 'ExcelImporterController@account_sheet_add');
    Route::post('/account-sheet', 'ExcelImporterController@account_sheet_add');
    Route::get('/activity', 'ExcelImporterController@activity_add');
    Route::post('/activity', 'ExcelImporterController@activity_add');

    //Нақд пул муомаласини ташкил этиш бошқармаси бўйича
    
    Route::get('/cash/tushum', 'ExcelImporterController@cash_tushum');
    Route::post('/cash/tushum', 'ExcelImporterController@cash_tushum');
    
    Route::get('/cash/qaytish', 'ExcelImporterController@cash_qaytish');
    Route::post('/cash/qaytish', 'ExcelImporterController@cash_qaytish');
    Route::get('/cash/hisobot', 'ExcelImporterController@cash_m_report');
    Route::post('/cash/hisobot', 'ExcelImporterController@cash_m_report');
    Route::get('/cash/execution', 'ExcelImporterController@cash_execution');
    Route::post('/cash/execution', 'ExcelImporterController@cash_execution');

    // Кредит ташкилотларини инспекция қилиш бошқармаси бўйича

    Route::get('/inspeksiya/out-of-credit', 'ExcelImporterController@i_out_of');
    Route::post('/inspeksiya/out-of-credit', 'ExcelImporterController@i_out_of');
    Route::get('/inspeksiya/likvidil-by-active', 'ExcelImporterController@i_likvid_active');
    Route::post('/inspeksiya/likvidil-by-active', 'ExcelImporterController@i_likvid_active');
    Route::get('/inspeksiya/likvidil-by-credit', 'ExcelImporterController@i_likvid_credit');
    Route::post('/inspeksiya/likvidil-by-credit', 'ExcelImporterController@i_likvid_credit');
    Route::get('/inspeksiya/bank-liability', 'ExcelImporterController@i_b_liability');
    Route::post('/inspeksiya/bank-liability', 'ExcelImporterController@i_b_liability');
    Route::get('/inspeksiya/bank-liability-demand', 'ExcelImporterController@i_b_liability_demand');
    Route::post('/inspeksiya/bank-liability-demand', 'ExcelImporterController@i_b_liability_demand');
    Route::get('/inspeksiya/net-profit', 'ExcelImporterController@i_net_profit');
    Route::post('/inspeksiya/net-profit', 'ExcelImporterController@i_net_profit');
    Route::get('/inspeksiya/active-likvid', 'ExcelImporterController@i_active_likvid');
    Route::post('/inspeksiya/active-likvid', 'ExcelImporterController@i_active_likvid');
    Route::get('/inspeksiya/expense-income', 'ExcelImporterController@i_income_expense');
    Route::post('/inspeksiya/expense-income', 'ExcelImporterController@i_income_expense');
    Route::get('/inspeksiya/work-lost', 'ExcelImporterController@i_work_lost');
    Route::post('/inspeksiya/work-lost', 'ExcelImporterController@i_work_lost');
    Route::get('/inspeksiya/others', 'ExcelImporterController@i_others');
    Route::post('/inspeksiya/others', 'ExcelImporterController@i_others');

    // Кредит ташкилотларида молиявий мониторингни мувофиқлаштириш ва валюта назорати бўлими бўйича

    Route::get('/currency/monthly-report', 'ExcelImporterController@c_m_report');
    Route::post('/currency/monthly-report', 'ExcelImporterController@c_m_report');
    Route::get('/currency/check-vash', 'ExcelImporterController@c_vash');
    Route::post('/currency/check-vash', 'ExcelImporterController@c_vash');
    Route::get('/currency/execution', 'ExcelImporterController@c_execution');
    Route::post('/currency/execution', 'ExcelImporterController@c_execution');
    Route::get('/currency/phone', 'ExcelImporterController@c_phone');
    Route::post('/currency/phone', 'ExcelImporterController@c_phone');

    // банк филиалларини ойлик фаолияти тўғрисида 

    Route::get('/business/home', 'ExcelImporterController@b_home');
    Route::post('/business/home', 'ExcelImporterController@b_home');
    Route::get('/business/kontur', 'ExcelImporterController@b_kontur');
    Route::post('/business/kontur', 'ExcelImporterController@b_kontur');
    Route::get('/business/family', 'ExcelImporterController@b_family');
    Route::post('/business/family', 'ExcelImporterController@b_family');
    Route::get('/business/guarantee', 'ExcelImporterController@b_guarantee');
    Route::post('/business/guarantee', 'ExcelImporterController@b_guarantee');
    Route::get('/business/past', 'ExcelImporterController@b_past');
    Route::post('/business/past', 'ExcelImporterController@b_past');
    Route::get('/business/monthly-report', 'ExcelImporterController@b_m_report');
    Route::post('/business/monthly-report', 'ExcelImporterController@b_m_report');
    Route::get('/business/execution', 'ExcelImporterController@b_execution');
    Route::post('/business/execution', 'ExcelImporterController@b_execution');


    Route::get('/ijro/ijro', 'ExcelImporterController@ijro');
    Route::post('/ijro/ijro', 'ExcelImporterController@ijro');

    Route::get('balance/balance', 'ExcelImporterController@balance');
    Route::post('balance/balance', 'ExcelImporterController@balance');

    Route::get('sxema/sxema', 'ExcelImporterController@sxema');
    Route::post('sxema/sxema', 'ExcelImporterController@sxema');
    Route::get('credit/credit', 'ExcelImporterController@credits');
    Route::post('credit/credit', 'ExcelImporterController@credits');
});

Route::group(['prefix' => 'settings', 'middleware' => 'auth'], function(){
    Route::get('/weight/list', 'SettingsController@weight_of_reports');
    Route::get('/weight/view', 'SettingsController@weight_of_report_view');
    Route::post('/weight/add', 'SettingsController@weight_of_report');
    Route::get('/weight/add', 'SettingsController@weight_of_report');
    Route::get('/account-sheet/list', 'SettingsController@account_sheets');
    Route::post('/account-sheet/add', 'SettingsController@account_sheet');
    Route::get('/account-sheet/add', 'SettingsController@account_sheet');
    Route::get('/lang/change', 'SettingsController@lang_change');
    Route::get('/role/list', 'SettingsController@role_managements');
    Route::post('/role/add/{id}', 'SettingsController@role_management');
    Route::get('/role/add', 'SettingsController@role_management');
    Route::get('/role/edit', 'SettingsController@role_management');
    Route::get('/accessrights/change', 'SettingsController@accessright_change');
    Route::get('/department/list', 'SettingsController@departments');
    Route::get('/department/add', 'SettingsController@department');
    Route::post('/department/add', 'SettingsController@department');
    Route::get('/activity-code/list', 'SettingsController@activity_codes');
    Route::post('/activity-code/add', 'SettingsController@activity_code');
    Route::get('/activity-code/add', 'SettingsController@activity_code');
    Route::get('/loan-goal/list', 'SettingsController@loan_goals');
    Route::post('/loan-goal/add', 'SettingsController@loan_goal');
    Route::get('/loan-goal/add', 'SettingsController@loan_goal');

});
Route::group(['prefix' => 'export', 'middleware' => 'auth'], function(){
    Route::get('/excel/monthly-all-rating', 'ExcelExportController@monthly_rating_all');
    Route::get('/pdf/monthly-all-rating', 'PdfExportController@monthly_rating_all');
    Route::get('/excel/inspeksiya/inspeksiya', 'ExcelExportController@inspeksiya_rating');
    Route::get('/pdf/inspeksiya/inspeksiya', 'PdfExportController@inspeksiya_rating');
    Route::get('/excel/cash/cash', 'ExcelExportController@cash_rating');
    Route::get('/pdf/cash/cash', 'PdfExportController@cash_rating');
    Route::get('/excel/loans/loans', 'ExcelExportController@loans');
    Route::get('/pdf/loans/loans', 'PdfExportController@loans');

});
Route::group(['prefix' => 'charts', 'middleware' => 'auth'], function(){
    Route::get('/pie-chart', 'ChartController@pie_chart');
    Route::post('/pie-chart', 'ChartController@pie_chart');
    Route::get('/line-chart', 'ChartController@line_chart');
    Route::post('/line-chart', 'ChartController@line_chart');
    Route::get('/rating-chart', 'ChartController@rating_chart');
    Route::post('/rating-chart', 'ChartController@rating_chart');
    Route::get('/loan-pie-credit', 'ChartController@loan_pie_credit');
    Route::post('/loan-pie-credit', 'ChartController@loan_pie_credit');
    Route::get('/loan-pie-problem', 'ChartController@loan_pie_problem');
    Route::post('/loan-pie-problem', 'ChartController@loan_pie_problem');
    Route::get('/loan-line-problem', 'ChartController@loan_line_problem');
    Route::post('/loan-line-problem', 'ChartController@loan_line_problem');
    Route::get('/loan-line-portfolio', 'ChartController@loan_line_portfolio');
    Route::post('/loan-line-portfolio', 'ChartController@loan_line_portfolio');
});
Route::get('/data', 'ExcelImporterController@data');

