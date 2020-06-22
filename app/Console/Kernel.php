<?php

namespace App\Console;

use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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

    protected function scheduleDailyCommands(Schedule $schedule) {
       // $schedule->command('products:set-rating')->dailyAt('01:30');
        $schedule->command('tax_payers:get_data')->dailyAt(env('GET_DATA_HOUR'));
        $schedule->command('tax_payers:truncate')->dailyAt(env('DELETE_DATA_HOUR'))->when(function () {  //daily
            return Storage::disk('padron_reducido')->exists('padron_reducido_ruc.zip'); //.text
        });
        $schedule->command('tax_payers:load_data')->dailyAt(env('LOAD_DATA_HOUR'))->when(function () { //daily
           return Storage::disk('padron_reducido')->exists('padron_reducido_ruc.zip'); //.text
       });

    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->scheduleDailyCommands($schedule);

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
