<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gateway;
use App\Services\Gateway1Service;
use App\Services\Gateway2Service;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $gateway1Service;
    protected $gateway2Service;

    public function __construct(Gateway1Service $gateway1Service, Gateway2Service $gateway2Service)
    {
        $this->gateway1Service = $gateway1Service;
        $this->gateway2Service = $gateway2Service;
    }

    public function handleGateway1(Request $request)
    {
        $data = $request->all();
        $gateway = Gateway::where('name', 'gateway1')->firstOrFail();

        if (!$this->gateway1Service->isValidSignature($data, $gateway)) {
            Log::warning('Invalid signature for Gateway 1', $data);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if (!$this->gateway1Service::checkLimit($gateway, $data['amount'])) {
            Log::warning('Daily limit exceeded for Gateway 1', $data);
            return response()->json(['error' => 'Daily limit exceeded'], 403);
        }

        $this->gateway1Service->updatePayment($data, $gateway);

        return response()->json(['message' => 'Payment processed'], 200);
    }

    public function handleGateway2(Request $request)
    {
        $data = $request->all();
        $gateway = Gateway::where('name', 'gateway2')->firstOrFail();
        $receivedSignature = $request->header('Authorization');

        if (!$this->gateway2Service->isValidSignature($data, $receivedSignature, $gateway)) {
            Log::warning('Invalid signature for Gateway 2', $data);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if (!$this->gateway2Service::checkLimit($gateway, $data['amount'])) {
            Log::warning('Daily limit exceeded for Gateway 2', $data);
            return response()->json(['error' => 'Daily limit exceeded'], 403);
        }

        $this->gateway2Service->updatePayment($data, $gateway);

        return response()->json(['message' => 'Payment processed'], 200);
    }
}
