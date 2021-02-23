<?php 


namespace App\Http\Controllers;
use DB;
use PDO;
use URL;
use auth;
use DateTime;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

use App\Exports\ExportAllRating;
use App\Exports\ExportCashRating;
use App\Exports\ExportLoanPortfolio;
use App\Exports\ExportInspeksiyaRating;


class ExcelExportController extends Controller
{
    public function monthly_rating_all(Request $request)
    {
        $title = trans('app.all rating monthly');
    	ini_set ('max_execution_time', 260000);
        return (new ExportAllRating($request->get('monthyear')))->download($title.".xlsx", Excel::XLSX);
    }
    public function inspeksiya_rating(Request $request)
    {
        $title = trans('app.inspeksiya rating');
    	ini_set ('max_execution_time', 260000);
        return (new ExportInspeksiyaRating($request->get('monthyear')))->download($title.".xlsx", Excel::XLSX);
    }
    public function loans(Request $request){
        $title = trans('app.loan report');
        if($request->get('status') && $request->get('status')){
            $title = trans('app.problem loan report');
        }
    	ini_set ('max_execution_time', 260000);
        return (new ExportLoanPortfolio($request))->download($title.".xlsx", Excel::XLSX);
    }
    public function cash_rating(Request $request)
    {
        $title = trans('app.cash rating');
    	ini_set ('max_execution_time', 260000);
        return (new ExportCashRating($request->get('monthyear')))->download($title.".xlsx", Excel::XLSX);
    }
    
}