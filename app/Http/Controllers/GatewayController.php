<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gateway;

class GatewayController extends Controller
{
    public function setLimit(Request $request, Gateway $gateway)
    {
        $request->validate(['limit' => 'required|integer|min:0']);

        $gateway->update(['limit' => $request->input('limit'), 'current_amount' => 0]);

        return response()->json(['message' => 'Limit updated successfully'], 200);
    }
}
