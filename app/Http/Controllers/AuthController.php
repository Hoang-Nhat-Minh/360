<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function logout(Request $request)
  {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('login');
  }

  public function register()
  {
    return view('frontend.auth.register');
  }

  public function register_check(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:6|confirmed',
    ], [
      'name.required' => 'Họ và tên là bắt buộc.',
      'email.required' => 'Email là bắt buộc.',
      'email.unique' => 'Email này đã được sử dụng.',
      'password.required' => 'Mật khẩu là bắt buộc.',
      'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    Auth::login($user);

    $alert = [
      "type" => "success",
      "title" => __("Đăng ký thành công!"),
      "body" => __("Chào mừng bạn đến với hệ thống!"),
    ];

    return redirect()->route('dashboard')->with('alert', $alert);
  }


  public function login()
  {
    return view('frontend.auth.login');
  }

  public function auth_check(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required|min:6',
    ], [
      'email.required' => 'Email là bắt buộc.',
      'email.email' => 'Email không đúng định dạng.',
      'password.required' => 'Mật khẩu là bắt buộc.',
      'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
    ]);

    $credentials = $request->only('email', 'password');


    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();

      $alert = [
        "type" => "success",
        "title" => __("Đăng nhập thành công!"),
        "body" => __("Chào mừng quay lại!")
      ];

      return redirect()->route('dashboard')->with('alert', $alert);
    }

    $alert = [
      "type" => "error",
      "title" => __("Đăng nhập thất bại!"),
      "body" => __("Kiểm tra lại các trường!")
    ];

    return redirect()->back()->with('alert', $alert)->onlyInput('email');
  }
}
