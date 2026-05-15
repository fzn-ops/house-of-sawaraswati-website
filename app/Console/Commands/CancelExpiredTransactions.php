<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredTransactions extends Command
{
    protected $signature = 'transactions:cancel-expired';

    protected $description = 'Cancel transaksi pending yang sudah lebih dari 24 jam';

    public function handle()
    {
        $expiredTransactions = Transaction::where('payment_status', 'pending')
            ->where('transaction_date', '<', Carbon::now()->subHours(24))
            ->get();

        $count = 0;

        foreach ($expiredTransactions as $transaction) {
            $transaction->update(['payment_status' => 'failed']);
            $count++;
            Log::info("Auto-cancelled expired transaction: {$transaction->order_id}");
        }

        $this->info("Cancelled {$count} expired transactions.");

        return Command::SUCCESS;
    }
}
