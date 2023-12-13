<?php

namespace App\Console;

use App\BatchMessage;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Traits\Whatsapp;
use App\WhatsappClient;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function(){
          $api    = new Whatsapp();
          $batch  = BatchMessage::whereStatus('pending')->firstOrFail();
          $batch->update(['status' => 'taken']);
          $numbers = explode(',', $batch->numbers);

          foreach( $numbers as $number ){
            $api->send(
              $batch->admin->whatsapp_instance_id,
              '57' . $number,
              $batch->message,
              $batch->media_url,
              null
            );
          }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
