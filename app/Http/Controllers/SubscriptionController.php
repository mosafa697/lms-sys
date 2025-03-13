<?php

namespace App\Http\Controllers;

use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        return response()->json(Subscription::with('user', 'course')->get());
    }
}
