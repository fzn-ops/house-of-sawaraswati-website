<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    /**
     * Handle Midtrans payment notification callback.
     * Endpoint ini dipanggil oleh Midtrans server saat status pembayaran berubah.
     */
    public function notification(Request $request)
    {
        // Set Midtrans config
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new \Midtrans\Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $orderId           = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus       = $notification->fraud_status;
        $paymentType       = $notification->payment_type;

        Log::info("Midtrans Notification - Order: {$orderId}, Status: {$transactionStatus}, Fraud: {$fraudStatus}");

        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            Log::warning("Midtrans: Transaction not found for order_id: {$orderId}");
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Map payment type ke payment_method yang readable
        $paymentMethodMap = [
            'credit_card'      => 'Kartu Kredit',
            'bank_transfer'    => 'Transfer Bank',
            'echannel'         => 'Mandiri Bill',
            'bca_klikpay'      => 'BCA KlikPay',
            'bca_klikbca'      => 'KlikBCA',
            'cimb_clicks'      => 'CIMB Clicks',
            'danamon_online'   => 'Danamon Online',
            'qris'             => 'QRIS',
            'gopay'            => 'GoPay',
            'shopeepay'        => 'ShopeePay',
            'cstore'           => 'Indomaret/Alfamart',
            'akulaku'          => 'Akulaku',
        ];

        // Tentukan status pembayaran berdasarkan status transaksi dari Midtrans
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $transaction->payment_status = 'paid';
            } else {
                $transaction->payment_status = 'challenge';
            }
        } elseif ($transactionStatus == 'settlement') {
            $transaction->payment_status = 'paid';
        } elseif ($transactionStatus == 'pending') {
            $transaction->payment_status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $transaction->payment_status = 'failed';

            // Kembalikan stok jika pembayaran gagal
            $this->restoreStock($transaction);
        } elseif ($transactionStatus == 'refund') {
            $transaction->payment_status = 'refunded';

            // Kembalikan stok jika di-refund
            $this->restoreStock($transaction);
        }

        // Update payment method dari Midtrans
        $transaction->payment_method = $paymentMethodMap[$paymentType] ?? $paymentType;
        $transaction->save();

        return response()->json(['message' => 'Notification handled']);
    }

    /**
     * Kembalikan stok produk ketika pembayaran gagal/refund.
     */
    private function restoreStock(Transaction $transaction)
    {
        $transaction->load('transactionDetails.product');

        foreach ($transaction->transactionDetails as $detail) {
            if ($detail->product) {
                $detail->product->increment('stok', $detail->quantity);
            }
        }

        Log::info("Stock restored for order: {$transaction->order_id}");
    }
}
