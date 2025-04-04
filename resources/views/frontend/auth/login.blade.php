<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Đăng nhập Admin</title>

  <meta name="description" content="Hệ thống quản lý VR360" />
  <meta name="author" content="Hoang Nhat Minh" />
  <link rel="canonical" href="http://vrdemo.test">
  <meta property="og:url" content="http://vrdemo.test">
  <meta property="og:title" content="VR360">
  <meta property="og:description" content="Hệ thống quản lý VR360">
  <meta property="og:type" content="Website">
  <meta property="og:site_name" content="VR360">
  <link rel="shortcut icon" href="{{ asset('assets/images/logo.webp') }}" />
  <link rel="stylesheet" href="{{ asset('assets/admin/fonts/bootstrap/bootstrap-icons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/admin/css/main.min.css') }}" />
</head>

<body class="bg-white">
  @include('layouts.components.web.alert')

  <div class="container">
    <div class="row justify-content-center" style="height:100vh">
      <div class="d-flex justify-content-center align-items-center">
        <form action="{{ route('login.auth') }}" method="POST">
          @csrf

          <div class="border rounded-2 p-4" style="width: 400px">
            <div class="text-center mb-3">
              <img src="{{ asset('assets/images/logo.webp') }}" alt=""
                style="height: 150px;width:150px;object-fit: cover">
            </div>
            <div class="login-form">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                  placeholder="Nhập Email" value="{{ old('email') }}" required />
                @error('email')
                  <label class="text-danger">{{ $message }}</label>
                @enderror
              </div>

              <div class="mb-3">
                <label class="form-label">Mật Khẩu</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                  placeholder="Nhập Mật Khẩu" required />
                @error('password')
                  <label class="text-danger">{{ $message }}</label>
                @enderror
              </div>
              <div class="d-grid py-3 mt-4">
                <button type="submit" class="btn btn-lg btn-primary">
                  Đăng nhập
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
