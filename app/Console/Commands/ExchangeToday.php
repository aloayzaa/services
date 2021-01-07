<?php


namespace App\Console\Commands;

use App\Http\Controllers\ExchangeRate\ExchangeRateController;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExchangeToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Exchange:today';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carga los datos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //DB::table('exchanges')->truncate();
        $time = Carbon::now()->format('Y-m-d h:i:s A');
        $this->info("Script start {$time}");
        $this->info("Delete data exchanges and reload indexs");
        $this->info("Insert Data");
        $a = new ExchangeRateController();
        if ($a->today()) {
            $time = Carbon::parse(Carbon::now())->diffForHumans($time);
            $this->info("{$time}");
            $this->info("Data load complete");
        } else {
            $this->info("error");
        }


    }
}
