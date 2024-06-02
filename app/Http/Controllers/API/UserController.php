<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FriendServices;
use App\Services\UserServices;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use GeneralTrait;

    public function index($id)
    {
        $user = User::find($id);
        $friendServices = new FriendServices();
        $friends = $friendServices->frindes($id)['data'];
        if (!$user) {
            return $this->returnError(404, 'User Not Found');
        }
        $data = [
            'user' => $user,
            'friends' => $friends
        ];
        return $this->returnData('data', $data);
    }

    public function update(Request $request)
    {
        try {
            $userService = new UserServices();
            $output = $userService->update($request);
            if ($output['status'] == false) {
                return $this->returnError($output['code'], $output['error']);
            }
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError(500, $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $userService = new UserServices();
            $output = $userService->updatePassword($request);
            if ($output['status'] == false) {
                return $this->returnError($output['code'], $output['error']);
            }
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError(500, $e->getMessage());
        }
    }

    public function newUsers(Request $request)
    {
        try {
            $userService = new UserServices();
            $users = $userService->newUsers($request)['data'];
            return $this->returnData('data', $users);
        } catch (\Exception $e) {
            return $this->returnError(500, $e->getMessage());
        }
    }
}
