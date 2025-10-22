<?php

namespace App\Console\Commands;

use App\Jobs\CheckOverdueBookLoans;
use Illuminate\Console\Command;

class CheckOverdueLoans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:check-overdue-loans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue loans and send reminder emails to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        CheckOverdueBookLoans::dispatchSync();
    }
}
