<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Razorpay\Api\Api;

class RazorpayService
{
    public function __construct(private ?Api $api = null)
    {
    }

    public function createOrder(float $amount, string $receipt, array $notes = []): object
    {
        return (object) $this->api()->order->create([
            'amount' => (int) round($amount * 100),
            'currency' => 'INR',
            'receipt' => $receipt,
            'payment_capture' => 1,
            'notes' => $notes,
        ]);
    }

    public function verifyPaymentSignature(array $attributes): void
    {
        $this->api()->utility->verifyPaymentSignature($attributes);
    }

    private function api(): Api
    {
        if ($this->api) {
            return $this->api;
        }

        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');

        if (blank($key) || blank($secret)) {
            throw ValidationException::withMessages([
                'razorpay' => 'Razorpay credentials are not configured.',
            ]);
        }

        return $this->api = new Api($key, $secret);
    }
}
