<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\FriendServices;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class FriendRequestController extends Controller
{
    use GeneralTrait;
    public function sendRequest($userId)
    {
        try {
            $friendServices = new FriendServices();
            $friendServices->sendRequest($userId);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }

    public function usersRequests()
    {
        try {
            $friendServices = new FriendServices();
            $users = $friendServices->usersRequests()['data'];
            return $this->returnData('data', $users);
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }

    public function myRequests()
    {
        try {
            $friendServices = new FriendServices();
            $users = $friendServices->myRequests()['data'];
            return $this->returnData('data', $users);
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }

    public function responseOnRequest($userId, $response)
    {
        try {
            $friendServices = new FriendServices();
            $friendServices->responseOnRequest($userId, $response);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }

    public function removeRequest($userId){
        try {
            $friendServices = new FriendServices();
            $friendServices->removeRequest($userId);
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }
}
