<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Subscription;
use App\Models\User;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $webhook_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $webhook_secret);

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                $user = User::where('email', $session->customer_email)->first();

                if ($user) {
                    Subscription::create([
                        'user_id' => $user->id,
                        'stripe_session_id' => $session->id,
                        'status' => 'active',
                        'amount' => $session->amount_total / 100,
                    ]);
                }
            } elseif ($event->type === 'invoice.payment_failed') {
                Log::warning('Payment failed for user');
            }

            return response()->json(['message' => 'Webhook received'], 200);
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook handling error'], 400);
        }
    }
}
