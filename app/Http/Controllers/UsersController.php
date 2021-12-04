<?php


namespace App\Http\Controllers;


use App\Models\Token;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function authenticate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'username' => 'required|max:100',
            'password' => 'required|max:20'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'data' => [
                    'message' => $validate->errors()->first()
                ]
            ]);
        }

        $user = Users::query()->where('username', $request->input('username'))->first();
        if ((new BcryptHasher())->check($request->input('password'), $user->password)) {
            $accessToken = base64_encode(Str::random(40));
            $refreshToken = base64_encode(Str::random(40));
            $token = new Token();
            $token->user_id = $user->id;
            $token->access_token = $accessToken;
            $token->refresh_token = $refreshToken;
            $token->expires_in = Carbon::now()->addHours(24)->toDateTimeString();
            $token->save();

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                ]
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => [
                    'message' => 'incorrect username or password',
                ]
            ], 401);
        }
    }
}
