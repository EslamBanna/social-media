<?php

namespace App\Services;

use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FriendServices
{
    use GeneralTrait;

    public function sendRequest($userId)
    {
        $user = Auth::user();
        // $friend = FriendRequest::
        $friend = FriendRequest::where(function ($q) use ($userId, $user) {
            $q->where('sender_user_id', $user->id)->where('receiver_user_id', $userId);
        })->orWhere(function ($q) use ($userId, $user) {
            $q->where('sender_user_id', $userId)->where('receiver_user_id', $user->id);
        })->first();
        if (!$friend) {
            FriendRequest::create([
                'sender_user_id' => $user->id,
                'receiver_user_id' => $userId,
            ]);
        }

        $response = [
            'status' => true,
            'data' => 'success'
        ];
        return $response;
    }
    public function usersRequests()
    {
        $users_ids = FriendRequest::where('receiver_user_id', Auth::user()->id)->pluck('sender_user_id');
        $users = User::whereIn('id', $users_ids)->paginate(15);
        $response = [
            'status' => true,
            'data' => $users
        ];
        return $response;
    }

    public function myRequests()
    {
        $users_ids = FriendRequest::where('sender_user_id', Auth::user()->id)->pluck('receiver_user_id');
        $users = User::whereIn('id', $users_ids)->paginate(15);
        $response = [
            'status' => true,
            'data' => $users
        ];
        return $response;
    }
    public function frindes($user_id)
    {
        $friends_temp_1 = Friend::where('f_user_id', $user_id)->pluck('s_user_id');
        $friends_temp_2 = Friend::where('s_user_id', $user_id)->pluck('f_user_id');
        $friends_ids = array_merge($friends_temp_1->all(), $friends_temp_2->all());
        $friends = User::whereIn('id', $friends_ids)->paginate(15);
        $response = [
            'status' => true,
            'data' => $friends
        ];
        return $response;
    }

    public function responseOnRequest($userId, $requestResponse)
    {
        $request = FriendRequest::where('sender_user_id', $userId)->where('receiver_user_id', Auth::user()->id)->first();
        $response = [''];
        if (!$request) {
            $response = [
                'status' => false,
                'error' => 'request not found'
            ];
            return $response;
        }
        if ($requestResponse == 1) {
            Friend::create([
                'f_user_id' => $userId,
                's_user_id' => Auth::user()->id
            ]);
        }
        $request->delete();
        $response = [
            'status' => true,
            'data' => 'success'
        ];
        return $response;
    }

    public function removeRequest($userId){
        $request = FriendRequest::where('receiver_user_id', $userId)->where('sender_user_id', Auth::user()->id)->first();
        $response = [''];
        if (!$request) {
            $response = [
                'status' => false,
                'error' => 'request not found'
            ];
            return $response;
        }
        $request->delete();
        $response = [
            'status' => true,
            'data' => 'success'
        ];
        return $response;
    }
}
