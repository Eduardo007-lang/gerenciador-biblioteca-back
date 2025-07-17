<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckOverdueLoans implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function handle(): void
    {
        $today = Carbon::today();
        $overdueLoans = Loan::where('status', '!=', 'returned')
            ->whereDate('devolution_date', '<', $today)
            ->where('status', '!=', 'overdue')
            ->get();
        foreach ($overdueLoans as $loan) {
            $loan->status = 'overdue';
            $loan->save();
            Log::info("Empréstimo ID {$loan->id} do livro ID {$loan->book_id} para o usuário ID {$loan->user_id} está atrasado.");
        }
    }
} 