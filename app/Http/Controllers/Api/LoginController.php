<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use Validator;
use Carbon;
use Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => $validator->errors(),
                'message' => 'Invalid parameters',
            ], 422);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            
            return response()->json([
                'token' => $request->user()->createToken("API TOKEN")->plainTextToken,
                'message' => 'Login successful'
            ], 200); 
        }

        return response()->json([
            'message' => 'Invalid login details'
        ], 401);
    }
}
