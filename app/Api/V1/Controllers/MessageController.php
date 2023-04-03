<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MessageController extends Controller
{
    /**
     * Send a message to subscribers
     *
     * @param Request $request
     * @return mixed
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'channel_id' => 'required|integer',
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $channel_id = $request->input('channel_id');
        $text = $request->input('text');

        $subscriptions = Subscription::where('channel_id', $channel_id)->get();

        foreach ($subscriptions as $subscription) {
            $message = new Message();
            $message->subscription_id = $subscription->id;
            $message->text = $text;
            $message->save();
        }

        return response()->json([
            'message' => 'Message sent',
        ]);
    }
}
