<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $email=$request->email;
        $password=$request->password;

        $this->validate($request, [
            'email'=>'required|email',
            'password'=>'required|min:8'
        ]);

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function register(Request $request)
    {

        $email=$request->email;
        $password=$request->password;

        $this->validate($request, [
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:8'
        ]);

        //Check If User Already Exist
        if(User::where('email',$email)->exists())
        {
            return response()->json(['status'=>'error', 'message'=> 'User Already Exists With This Email' ]);
        }

        //Create New User
        try{
            $user=new User();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password=app('hash')->make($request->password);
            if($user->save())
            {
                return $this->login($request);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'error', 'message'=>$e->getMessage()]);
        }





    }
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

}
