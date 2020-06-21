<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tax_payers:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate table tax_payers';

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
        try {
            DB::table('tax_payers')->truncate();
            $this->info('The backup has been proceed successfully.');
        
        } catch (Exception $exception) {
            $this->error('The truncate process has been failed.');
            $this->error($exception->getMessage());
        }
    }
}
