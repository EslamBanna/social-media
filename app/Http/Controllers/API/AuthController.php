<?php

namespace App\Http\Controllers\API;

use App\Events\SendForgotPasswordCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use GeneralTrait;
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->returnError(400, $validator->errors()->first());
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $token =  $user->createToken('MyApp')->plainTextToken;
                $data = [
                    'user' => $user,
                    'token' => $token
                ];
                return $this->returnData('data', $data);
            } else {
                return $this->returnError(401, 'not authenticated user');
            }
        } catch (\Exception $e) {
            return $this->returnError(500,$e->getMessage());
        }
    }

    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'unique:users,email',
                'password' => 'required|min:5',
                'confirm_password' => 'required|same:password',
            ]);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $input = $request->all();
            $password = bcrypt($input['password']);
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email ?? "";
            $user->password = $password;
            $verificationCode = mt_rand(1000, 9999);
            $user->verification_code = $verificationCode;
            if ($request->hasFile('photo')) {
                $user_profile = $this->saveImage($request->photo, 'users');
                $user->photo = $user_profile;
            }
            $user->save();
            $allUserData = User::find($user->id);
            $token =  $user->createToken('MyApp')->plainTextToken;
            $data = [
                'user' => $allUserData,
                'token' => $token
            ];
            DB::commit();
            return $this->returnData('data', $data);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError(500,$e->getMessage());
        }
    }

    public function sendOTP(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users,email'
            ]);
            if ($validator->fails()) {
                return $this->returnError(400, $validator->errors()->first());
            }
            $user = User::where('email', $request->email)->first();
            $verificationCode = mt_rand(1000, 9999);
            $user->verification_code = $verificationCode;
            $userName =  $user->f_name . ' ' . $user->l_name;
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError(500,$e->getMessage());
        }
    }

    public function me(Request $request)
    {
        $user = Auth::user();
        return $this->returnData('user', $user);
    }

    public function verifyEmail(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
                'verification_code' => 'required|min:4'
            ]);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user = User::where('email', $request->email)->first();
            if ($user->verification_code != $request->verification_code) {
                return $this->returnError(404, 'incorrect code');
            }
            $user->email_verified_at = Carbon::now();
            $user->save();
            $allUserData = $user;
            $token =  $user->createToken('MyApp')->plainTextToken;
            $data = [
                'user' => $allUserData,
                'token' => $token
            ];
            DB::commit();
            return $this->returnData('data', $data);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError(500,$e->getMessage());
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'email' => 'required|email:exists:users:email'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user = User::where('email', $request->email)->first();
            $code = mt_rand(1000, 9999);
            PasswordResetToken::where('email', $user->email)->delete();
            PasswordResetToken::create([
                'email' => $user->email,
                'token' => $code
            ]);
            $userName =  $user->f_name . ' ' . $user->l_name;
            event(new SendForgotPasswordCode($request->email, $code, $userName));
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            return $this->returnError(500,$e->getMessage());
        }
    }
    public function checkForgetPasswordToken(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'code' => 'required',
                'email' => 'required|exists:users,email',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $code = PasswordResetToken::where('token', $request->code)
                ->where('email', $request->email)
                ->first();
            if (!$code) {
                return $this->returnError(405,'incorrect_code');
            }
            return $this->returnSuccessMessage('true');
            // PasswordResetToken::where('email', $user['email'])->delete();
            // DB::commit();
            // return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError(500,$e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'email' => 'required|exists:users,email',
                'password' => 'required|min:5',
                'confirm_password' => 'required|same:password',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user = User::where('email', $request->email)->first();
            $password = bcrypt($request->password);
            $user->password = $password;
            $user->save();
            PasswordResetToken::where('email', $user['email'])->delete();
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnError(500,$e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'old_password' => 'required',
                'password' => 'required|min:5',
                'confirm_password' => 'required|min:5|same:password',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $user_id = Auth::user()->id;
            $user = User::find($user_id);
            if (!Hash::check($request->input('old_password'), $user->password)) {
                return $this->returnError(225, 'invalid old password');
            }
            $user->password = bcrypt($request->password);
            $user->save();
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError(500,$e->getMessage());
        }
    }
}
