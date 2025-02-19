<?php


namespace App\Services;

use App\Models\Payment;
use App\Models\Gateway;
use BaseGatewayService;
use Illuminate\Support\Facades\Log;

class Gateway1Service extends BaseGatewayService
{
    public function isValidSignature(array $data, Gateway $gateway): bool
    {
        $signData = collect($data)
            ->except(['sign'])
            ->sortKeys()
            ->implode(':');

        $expectedSign = hash('sha256', $signData . ':' . $gateway->merchant_key);

        return $expectedSign === $data['sign'];
    }

    public function updatePayment(array $data, Gateway $gateway): void
    {
        Payment::updateOrCreate(
            ['gateway_id' => $gateway->id, 'payment_id' => $data['payment_id']],
            [
                'status' => $data['status'],
                'amount' => $data['amount'],
                'amount_paid' => $data['amount_paid'],
            ]
        );
    }
}
