<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserServices;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    use GeneralTrait;

    public function index($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back();
        }
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        try {
            $userService = new UserServices();
            $output = $userService->update($request);
            if ($output['status'] == false) {
                return redirect()->back()->withInput()->withErrors($output['error']);
            }
            $user = User::find(Auth::user()->id);
            return redirect()->route('profile', $user->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }


    public function editPassword(Request $request)
    {

        return view('profile.update_password');
    }

    public function updatePassword(Request $request)
    {
        try {
            $userService = new UserServices();
            $output = $userService->updatePassword($request);
            if ($output['status'] == false) {
                return redirect()->back()->withInput()->withErrors($output['error']);
            }
            $user = User::find(Auth::user()->id);
            return redirect()->route('profile', $user->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
