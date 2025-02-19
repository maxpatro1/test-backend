<?php

use App\Models\Gateway;

abstract class BaseGatewayService
{
    public static function checkLimit(Gateway $gateway, int $amount): bool
    {
        if ($gateway->current_amount + $amount > $gateway->limit) {
            return false;
        }

        $gateway->increment('current_amount', $amount);
        return true;
    }

    abstract function updatePayment(array $data, Gateway $gateway);
}
