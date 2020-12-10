<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SessionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $dataCheckLogin = [
            'email'=>$request->email,
            'password'=>$request->password,
        ];
        if(auth()->attempt($dataCheckLogin))
        {
            $checkTokenExits = SessionUser::where('user_id',auth()->id())->first();
            if(empty($checkTokenExits))
            {
                $useSession = SessionUser::create([
                    'token'=> Str::random(40),
                    'refresh_token'=> Str::random(40),
                    'token_expried'=> date('Y-m-d H:i:s', strtotime('+30 day')),
                    'refresh_token_expried'=> date('Y-m-d H:i:s', strtotime('+365 day')),
                    'user_id' => auth()->id()
                ]);
            }
            else
            {
                $useSession = $checkTokenExits;
            }
            return response()->json([
                'code'=>200,
                'data'=>$useSession
            ],200);
        }
        else
        {
            return response()->json([
                'code'=>401,
                'message'=>'User hoac password khong dung'
            ],200);
        }

    }
}
