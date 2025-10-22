<?php

namespace App\Jobs;

use App\Mail\GenericMail;
use App\Models\Loan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckOverdueBookLoans implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $overdueLoans = Loan::overdue()->with('user:email,name', 'book:name')->get();

        // send emails to overdue users
        foreach ($overdueLoans as $loan) {
            Mail::to($loan->user->mail)
                ->send(new GenericMail(
                    'emails.loans.overdue_loan',
                    'Overdue Loan',
                    [
                        'userName' => $loan->user->name,
                        'bookName' => $loan->book->name,
                    ]
                ));
        }
    }
}
