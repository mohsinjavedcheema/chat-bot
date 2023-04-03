<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SubscriptionController extends Controller
{
    /**
     * Subscribe user to the chatbot
     *
     * @param Request $request
     * @return mixed
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $user = new User();
        $user->fill($request->all());
        $user->save();

        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->save();

        return response()->json([
            'message' => 'Subscription successful',
            'user' => $user,
            'subscription' => $subscription,
        ]);
    }
}
