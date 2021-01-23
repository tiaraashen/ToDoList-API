<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class UserController extends Controller
{

    public $successStatus = 200;

    public function googleSignin(Request $request){
        $name = $request->name;
        $email = $request->email;

        if (User::where('email', $email)->exists()) {
            $user = User::where('email', $email)->first();
            Auth::login($user);
            $success['token'] =  $user->createToken('nApp')->accessToken;
            echo "success";
            return response()->json(['token' => $success['token']], $this->successStatus);
        }else{
            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->save();
            $success['token'] =  $user->createToken('nApp')->accessToken;
            echo "failed";
            return response()->json(['token' => $success['token']], $this->successStatus);
        }
    }

    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            //if('password' => Hash:make(request('password'))){
                //Hash::make($password);
            //}
            $user = Auth::user();
            $success['token'] =  $user->createToken('nApp')->accessToken;
            return response()->json(['token' => $success['token']], $this->successStatus);
        }
        else{
            return response()->json(['message'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        return response()->json(['message'=>"Registered Successfully !"], $this->successStatus);
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if($logout){
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
    }

    public function details()
    {
        $user = DB::table('users')
                ->select('user_id', 'name', 'email')
                ->where('user_id', Auth::user()->user_id)
                ->first();
        return response()->json(['user' => $user], $this->successStatus);
    }
}
