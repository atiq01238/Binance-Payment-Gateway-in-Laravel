<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BinanceService;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $binanceService;

    public function __construct(BinanceService $binanceService)
    {
        $this->binanceService = $binanceService;
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'symbol' => 'required|string',
            'side' => 'required|string|in:buy,sell',
            'type' => 'required|string|in:market,limit',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required_if:type,limit|numeric|min:0',
        ]);

        try {
            $response = $this->binanceService->createOrder(
                $validatedData['symbol'],
                strtoupper($validatedData['side']), // Ensure side is uppercase
                strtoupper($validatedData['type']), // Ensure type is uppercase
                $validatedData['quantity'],
                $validatedData['price'] ?? null
            );

            // dd($response);

            if (isset($response['error'])) {
                Log::error('Failed to create order', ['response' => $response]);
                return redirect()->back()->with('error', 'Failed to create order: ' . $response['error']);
            }

            Order::create([
                'symbol' => $validatedData['symbol'],
                'side' => $validatedData['side'],
                'type' => $validatedData['type'],
                'quantity' => $validatedData['quantity'],
                'price' => $validatedData['price'] ?? null,
                'order_id' => $response['order_id'], // Store the order ID in your database
            ]);

            return redirect()->back()->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            Log::error('Exception during order creation', ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

}
