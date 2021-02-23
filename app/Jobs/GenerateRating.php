<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateRating implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $monthyear;
    public function __construct($monthyear)
    {
        $this->monthyear = $monthyear;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $monthyear = $this->monthyear;
        $month = date('m', strtotime($monthyear));
        $year = date('Y', strtotime($monthyear));
        $data = DB::table('data_'.$year)->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
        try{
            inspeksiya_report($monthyear, $data);
            all_report($monthyear, $data);
        }catch(Exception $e){
            $this->failed($e);
        }
        
    }
    public function failed($exception)
    {
        $exception->getMessage();
    }
}
