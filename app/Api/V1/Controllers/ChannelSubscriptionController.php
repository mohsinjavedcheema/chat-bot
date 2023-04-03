<?php

namespace App\Api\V1\Controllers;

use App\Models\Channel;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Lumen\Routing\Controller;

class ChannelSubscriptionController extends Controller
{
    /**
     * Subscribe a user to a channel.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'channel_id' => [
                'required',
                'integer',
                Rule::exists('channels', 'id'),
            ],
        ]);

        $user_id = $request->input('user_id');
        $channel_id = $request->input('channel_id');

        // Check if user is already subscribed to the channel
        if (Subscription::where('user_id', $user_id)->where('channel_id', $channel_id)->exists()) {
            return response()->json([
                'message' => 'User is already subscribed to the channel.',
            ]);
        }

        // Create subscription
        $subscription = new Subscription();
        $subscription->user_id = $user_id;
        $subscription->channel_id = $channel_id;
        $subscription->save();

        // Retrieve subscribed channel details
        $channel = Channel::find($channel_id);

        return response()->json([
            'message' => 'User subscribed to channel successfully.',
            'channel' => $channel,
            'subscription' => $subscription,
        ]);
    }
}
