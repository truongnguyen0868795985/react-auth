<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function login(LoginRequest $request)
    {
        if ($this->attemptLogin($request)) {
            $user = Auth::user();

            return $this->sendLoginResponse($request, $user);
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function sendLoginResponse(Request $request, $user)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return response()->json([
            'data' => $user,
            'message' => 'Đăng nhập thành công',
        ], 200);
    }

    protected function sendFailedLoginResponse()
    {
        return response()->json([
            'message' => 'Email hoặc mật khẩu của bạn không chính xác',
        ], 400);
    }
}
