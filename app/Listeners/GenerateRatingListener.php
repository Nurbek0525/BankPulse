<?php

namespace App\Listeners;

use App\Event\GenerateRatingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use PDO;
use DateTime;
use DB;
use URL;
use Auth;
use Mail;

class GenerateRatingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GenerateRatingEvent  $event
     * @return void
     */
    public function handle(GenerateRatingEvent $event)
    {
        if($event->type = 'inspeksiya'){
            $monthyear = $event->monthyear;
            $month = date('m', strtotime($monthyear));
            $year = date('Y', strtotime($monthyear));
            $data = DB::table('data_'.$year)->where([['month', '=', $month], ['year', '=', $year]])->get()->toArray();
            inspeksiya_report($monthyear, $data);
        }
    }
}
