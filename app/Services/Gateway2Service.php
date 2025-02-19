<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Gateway;
use BaseGatewayService;
use Illuminate\Support\Facades\Log;

class Gateway2Service extends BaseGatewayService
{
    public function isValidSignature(array $data, string $receivedSignature, Gateway $gateway): bool
    {
        $signData = collect($data)
            ->sortKeys()
            ->implode('.');

        $expectedSign = md5($signData . '.' . $gateway->merchant_key);

        return $expectedSign === $receivedSignature;
    }

    public function updatePayment(array $data, Gateway $gateway): void
    {
        Payment::updateOrCreate(
            ['gateway_id' => $gateway->id, 'payment_id' => $data['invoice']],
            [
                'status' => $data['status'],
                'amount' => $data['amount'],
                'amount_paid' => $data['amount_paid'],
            ]
        );
    }
}

