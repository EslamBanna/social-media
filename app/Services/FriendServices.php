<?php

namespace App\Services;

use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;

class FriendServices
{
    use GeneralTrait;

    public function sendRequest($userId)
    {
        $user = Auth::user();
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
            $notification_contetnt = 'User ' . Auth::user()->name . ' Send You A Friend Request';
            addNotification($userId, $user->id, null, $notification_contetnt, 2);
        }

        $response = [
            'status' => true,
            'data' => 'success',
            'code' => 200,
            'error' => '',
            'message' => ''
        ];
        return $response;
    }
    public function usersRequests()
    {
        $users_ids = FriendRequest::where('receiver_user_id', Auth::user()->id)->pluck('sender_user_id');
        $users = User::whereIn('id', $users_ids)->paginate(15);
        $response = [
            'status' => true,
            'data' => $users,
            'code' => 200,
            'error' => '',
            'message' => ''
        ];
        return $response;
    }

    public function myRequests()
    {
        $users_ids = FriendRequest::where('sender_user_id', Auth::user()->id)->pluck('receiver_user_id');
        $users = User::whereIn('id', $users_ids)->paginate(15);
        $response = [
            'status' => true,
            'data' => $users,
            'code' => 200,
            'error' => '',
            'message' => ''
        ];
        return $response;
    }

    public function frindesIds($user_id)
    {
        $friends_temp_1 = Friend::where('f_user_id', $user_id)->pluck('s_user_id');
        $friends_temp_2 = Friend::where('s_user_id', $user_id)->pluck('f_user_id');
        $friends_ids = array_merge($friends_temp_1->all(), $friends_temp_2->all());
        return $friends_ids;
    }
    public function frindes($user_id)
    {
        $friends_ids = $this->frindesIds($user_id);
        $friends = User::whereIn('id', $friends_ids)->paginate(15);
        $response = [
            'status' => true,
            'data' => $friends,
            'code' => 200,
            'error' => '',
            'message' => ''
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
                'error' => 'request not found',
                'data' => 'success',
                'code' => 404,
                'message' => ''
            ];
            return $response;
        }
        if ($requestResponse == 1) {
            Friend::create([
                'f_user_id' => $userId,
                's_user_id' => Auth::user()->id
            ]);
            $notification_contetnt = 'User ' . Auth::user()->name . ' Accept Your Friend Request';
            addNotification($userId, Auth::user()->id, null, $notification_contetnt, 3);
        }
        $request->delete();
        $response = [
            'status' => true,
            'data' => '',
            'code' => 200,
            'error' => '',
            'message' => 'success'
        ];
        return $response;
    }

    public function removeRequest($userId)
    {
        $request = FriendRequest::where('receiver_user_id', $userId)->where('sender_user_id', Auth::user()->id)->first();
        $response = [''];
        if (!$request) {
            $response = [
                'status' => false,
                'error' => 'request not found',
                'data' => '',
                'code' => 404,
                'message' => ''
            ];
            return $response;
        }
        $request->delete();
        $response = [
            'status' => true,
            'data' => '',
            'code' => 200,
            'error' => '',
            'message' => 'success'
        ];
        return $response;
    }
}
