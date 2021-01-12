<?php

namespace App\Console;

use Carbon\Carbon;
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


//        $schedule->command('Exchange:today')->dailyAt('09:06');
        $schedule->command('Exchange:today')->cron('*/3 * * * *');


        $schedule->command('down --message="Estamos en Mantenimiento (30 min)" --retry=30')->dailyAt(env('MAINTENANCE_HOUR'));



        $schedule->command('tax_payers:get_data')->dailyAt(env('MAINTENANCE_HOUR'));
        $schedule->command('tax_payers:truncate')->dailyAt(env('MAINTENANCE_HOUR'))->when(function () {
            return Storage::disk('padron_reducido')->exists('padron_reducido_ruc.zip'); //.text
        });
        $schedule->command('tax_payers:load_data')->dailyAt(env('MAINTENANCE_HOUR'))->when(function () {
           return Storage::disk('padron_reducido')->exists('padron_reducido_ruc.zip');
        });

        $schedule->command('local_anex:get_data')->dailyAt(env('MAINTENANCE_HOUR'));
        $schedule->command('local_anex:truncate')->dailyAt(env('MAINTENANCE_HOUR'))->when(function () {
            return Storage::disk('local_anexo')->exists('padron_reducido_local_anexo.txt'); //.text
        });
        $schedule->command('local_anex:load_data')->dailyAt(env('MAINTENANCE_HOUR'))->when(function () {
        return Storage::disk('local_anexo')->exists('padron_reducido_local_anexo.txt');
        });



        $schedule->command('up')->dailyAt(env('MAINTENANCE_HOUR'));

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
