<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Services\FriendServices;
use Illuminate\Http\Request;

class FriendRequestController extends Controller
{
    public function sendRequest($userId)
    {
        try {
            $friendServices = new FriendServices();
            $friendServices->sendRequest($userId);
            return redirect()->route('new.users');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function usersRequests()
    {
        try {
            $friendServices = new FriendServices();
            $users = $friendServices->usersRequests()['data'];
            return view('users.users_requests', compact('users'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function myRequests()
    {
        try {
            $friendServices = new FriendServices();
            $users = $friendServices->myRequests()['data'];
            return view('users.my_requests', compact('users'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function responseOnRequest($userId, $response)
    {
        try {
            $friendServices = new FriendServices();
            $friendServices->responseOnRequest($userId, $response);
            return redirect()->route('new.users.requests');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function removeRequest($userId){
        try {
            $friendServices = new FriendServices();
            $friendServices->removeRequest($userId);
            return redirect()->route('my.requests');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
