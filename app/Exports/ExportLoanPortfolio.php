<?php

namespace App\Exports;
use DB;
use PDO;
use URL;
use auth;
use DateTime;
use stdClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportLoanPortfolio implements FromView, WithEvents
{

    protected $request;

    function __construct($request) {
            $this->request = $request;
    }
	use Exportable;
    public function view(): View
    {
        ini_set('memory_limit', '-1');
        //$mfo_id = $monthyear->get('mfo');
        $request = $this->request;
        $monthyear = $request->monthyear;
        $fillial = $request->fillial;
        $mainbank = $request->mainbank;
        $city = $request->city;
        $activity_code = $request->activity_code;
        $goal_code = $request->goal_code;
        $status = $request->status;
        $s = $request->search;
        $month = intval(date('m', strtotime($monthyear)));
        $year = date('Y', strtotime($monthyear));
        $date_title = trans('app.month'.$month)."-".$year;
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
        $credits = $credits->where('banks.mainbank_id', '!=', 38)->get()->toArray();
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
        return view('export-excel.loans.loans', compact('credits', 'title', 'date_title', 'all_out_of', 'all_amount_equiv', 'all_remainder', 'all_debt_amount', 'all_backup', 'all_needed_backup'));
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:O1')->getFont()->setSize(10);
                $event->sheet->getDelegate()->getStyle('A2:O2')->getFont()->setSize(10);
                $event->sheet->getColumnDimension('A')->setWidth(3);
                $event->sheet->getColumnDimension('B')->setWidth(25);
                $event->sheet->getColumnDimension('C')->setWidth(10);
                $event->sheet->getColumnDimension('D')->setWidth(12);
                $event->sheet->getColumnDimension('E')->setWidth(25);
                $event->sheet->getColumnDimension('F')->setWidth(8);
                $event->sheet->getColumnDimension('G')->setWidth(8);
                $event->sheet->getColumnDimension('H')->setWidth(12);
                $event->sheet->getColumnDimension('I')->setWidth(12);
                $event->sheet->getColumnDimension('J')->setWidth(20);
                $event->sheet->getColumnDimension('K')->setWidth(20);
                $event->sheet->getColumnDimension('L')->setWidth(20);
                $event->sheet->getColumnDimension('M')->setWidth(20);
                $event->sheet->getColumnDimension('N')->setWidth(20);
                $event->sheet->getColumnDimension('O')->setWidth(20);
                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(50);
                $event->sheet->getDelegate()->getStyle('A2:P2')->getAlignment()->setWrapText(true);
            },
        ];
    }
}