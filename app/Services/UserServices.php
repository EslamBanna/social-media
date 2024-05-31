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

class UserServices
{
    use GeneralTrait;
    public function update(Request $request)
    {

        $user = User::find(Auth::user()->id);
        $response = [];
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            "email" => "required|email|max:128|unique:users,email," . $user->id . ",id"
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'error' => $validator->errors()
            ];
            return $response;
        }
        DB::beginTransaction();
        $user->name =  $request->name;
        $user->email =  $request->email;
        $user->bio =  $request->bio;
        if ($request->hasFile('photo') && $request->photo != null) {
            $profile_picture = $this->saveImage($request->photo, 'users');
            $user->photo = $profile_picture;
        }
        $user->save();
        DB::commit();
        $response = [
            'status' => true,
            'error' => 'success'
        ];
        return $response;
    }

    public function updatePassword(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $response = [];
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:8|string',
            'new_password' => 'required|min:8|string',
            'confirm_password' => 'same:new_password'
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'error' => $validator->errors()
            ];
            return $response;
        }
        if (!Hash::check($request->old_password, $user->password)) {
            $response = [
                'status' => false,
                'error' => [
                    'old_password' => ['Old Password Is Not Correct']
                ]

            ];
            return $response;
        }
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);
        $response = [
            'status' => true,
            'error' => 'success'
        ];
        return $response;
    }

    public function newUsers(){
        $user_id = Auth::user()->id;
        $firends_id_temp_1 = Friend::where('f_user_id', $user_id)->pluck('s_user_id');
        $firends_id_temp_2 = Friend::where('s_user_id', $user_id)->pluck('f_user_id');
        $firends_id_temp_3 = FriendRequest::where('sender_user_id', $user_id)->pluck('receiver_user_id');
        $firends_id_temp_4 = FriendRequest::where('receiver_user_id', $user_id)->pluck('sender_user_id');
        $firends_id = array_merge([$user_id],$firends_id_temp_1->all(), $firends_id_temp_2->all(), $firends_id_temp_3->all(), $firends_id_temp_4->all());
        $users = User::whereNotIn('id', $firends_id)->paginate(15);
        $response = [
            'status' => true,
            'data' => $users
        ];
        return $response;
    }
}
