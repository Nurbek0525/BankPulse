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

class ExportCashRating implements FromView, WithEvents
{
    protected $monthyear;

    function __construct($monthyear) {
            $this->monthyear = $monthyear;
    }
	use Exportable;
    public function view(): View
    {
        ini_set('memory_limit', '-1');
        $monthyear = $this->monthyear;
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
        return view('export-excel.cash.cash', compact('reports', 'weight', 'title', 'date_title'));
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A2:P2')->getFont()->setSize(10);
                $event->sheet->getColumnDimension('A')->setWidth(3);
                $event->sheet->getColumnDimension('B')->setWidth(25);
                $event->sheet->getColumnDimension('G')->setWidth(15);
                $event->sheet->getColumnDimension('H')->setWidth(15);
                $event->sheet->getColumnDimension('I')->setWidth(15);
                $event->sheet->getColumnDimension('J')->setWidth(15);
                $event->sheet->getColumnDimension('K')->setWidth(15);
                $event->sheet->getColumnDimension('L')->setWidth(15);
                $event->sheet->getColumnDimension('M')->setWidth(10);
                $event->sheet->getColumnDimension('N')->setWidth(10);
                $event->sheet->getColumnDimension('O')->setWidth(15);
                $event->sheet->getColumnDimension('P')->setWidth(15);
                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(50);
                $event->sheet->getDelegate()->getStyle('A2:P2')->getAlignment()->setWrapText(true);
            },
        ];
    }

}