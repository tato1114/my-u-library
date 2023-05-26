<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $input = $request->only('name', 'email', 'password');
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $response['token'] = $user->createToken('MyApp')->plainTextToken;
        $response['message'] = 'User register successfully.';

        return response()->json($response, Response::HTTP_OK);
    }

    public function login(LoginRequest $request)
    {
        $input = $request->only('email', 'password');
        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            $user = Auth::user();
            $response['token'] = $user->createToken('MyULibrary')->plainTextToken;
            $response['message'] = 'User login successfully.';

            return response()->json($response, Response::HTTP_OK);
        } else {
            return response()->json(['error' => 'Unauthorised'], Response::HTTP_UNAUTHORIZED);
        }
    }
}
