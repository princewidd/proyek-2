<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request): Response
    {
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature = $payload['signature_key'] ?? null;

        $expected = hash('sha512', $orderId.$statusCode.$grossAmount.config('midtrans.server_key'));

        if (! $orderId || ! $signature || ! hash_equals($expected, $signature)) {
            Log::warning('Midtrans webhook signature tidak valid.', ['order_id' => $orderId]);

            return response('Invalid signature', 403);
        }

        $pembayaran = Pembayaran::where('midtrans_order_id', $orderId)->first();

        if (! $pembayaran) {
            return response('OK', 200);
        }

        MidtransService::terapkanStatus(
            $pembayaran,
            $payload['transaction_status'] ?? null,
            $payload['fraud_status'] ?? null
        );

        return response('OK', 200);
    }
}
