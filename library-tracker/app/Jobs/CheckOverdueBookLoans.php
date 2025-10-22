<?php

namespace App\Jobs;

use App\Mail\GenericMail;
use App\Models\Loan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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
        $overdueLoans = Loan::overdue()->with('user:id,email,name', 'book:id,title')->get();

        // send emails to overdue users
        foreach ($overdueLoans as $loan) {
            Mail::to($loan->user->email)
                ->send(new GenericMail(
                    'emails.loans.overdue_loan',
                    'Overdue Loan',
                    [
                        'userName' => $loan->user->name,
                        'bookTitle' => $loan->book->title,
                    ]
                ));
        }

         Log::info('Overdue loan job executed', ['count' => $overdueLoans->count()]);
    }
}
