<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LocalAnexDeleteData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'local_anex:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate table annexed_locals';

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
        $time = Carbon::now()->format('Y-m-d h:i:s A');
        $this->info("Script start {$time}");
        try {
            DB::table('annexed_locals')->truncate();
            $this->info('The truncate process has been proceed successfully.');

        } catch (Exception $exception) {
            $this->error('The truncate process has been failed.');
            $this->error($exception->getMessage());
        }
        $time = Carbon::now()->format('Y-m-d h:i:s A');
        $this->info("Script end {$time}");
    }
}
