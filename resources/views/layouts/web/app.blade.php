<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $websiteName }}</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Mô tả ngắn về trang web -->
  <meta name="description" content="{{ $websiteDescription }}">

  <!-- Từ khóa cho SEO -->
  <meta name="keywords" content="{{ $websiteKeywords }}">

  <!-- Tác giả của trang web -->
  <meta name="author" content="{{ $websiteAuthor }}">
  <meta name="robots" content="index, follow">

  <!-- Facebook Open Graph (Chia sẻ lên Facebook) -->
  <meta property="og:title" content="{{ $websiteName }}">
  <meta property="og:description" content="{{ $websiteDescription }}">
  <meta property="og:image" content="{{ $logoUrl }}">
  <meta property="og:url" content="{{ $websiteUrl }}">
  <meta property="og:type" content="website">

  <!-- Twitter Card (Dành cho Twitter) -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $websiteName }}">
  <meta name="twitter:description" content="{{ $websiteDescription }}">
  <meta name="twitter:image" content="{{ $logoUrl }}">

  <!-- Ngôn ngữ của trang web -->
  <meta http-equiv="Content-Language" content="vi">

  <!-- Định dạng trang (HTML5) -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Chính sách bảo mật nội dung -->
  {{-- <meta http-equiv="Content-Security-Policy"
    content="default-src 'self'; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline';"> --}}

  <!-- Tránh trình duyệt nhận diện sai loại nội dung -->
  <meta http-equiv="X-Content-Type-Options" content="nosniff">

  <!-- Bảo vệ khỏi tấn công XSS -->
  <meta http-equiv="X-XSS-Protection" content="1; mode=block">

  <!-- Định nghĩa ứng dụng web như một ứng dụng gốc -->
  <meta name="mobile-web-app-capable" content="yes">

  <!-- Định nghĩa biểu tượng ứng dụng -->
  <meta name="application-name" content="Tên ứng dụng">
  <meta name="theme-color" content="#ffffff">

  <!-- Facebook App ID -->
  <meta property="fb:app_id" content="ID của ứng dụng Facebook">

  <!-- Google Search Console -->
  <meta name="google-site-verification" content="{{ $websiteGoogleSiteVerification }}">

  <!-- Bản quyền -->
  <meta name="copyright" content="© 2025 Kennatech">

  <link rel="icon" href="{{ $logoUrl }}" type="image/x-icon">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/7.3.2/css/flag-icons.min.css"
    integrity="sha512-+WVTaUIzUw5LFzqIqXOT3JVAc5SrMuvHm230I9QAZa6s+QRk8NDPswbHo2miIZj3yiFyV9lAgzO1wVrjdoO4tw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Oxygen:wght@300;400;700&family=Pacifico&display=swap"
    rel="stylesheet"> --}}
  <link rel="stylesheet" href="{{ asset('assets/flags_css/css/flag-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/fonts/bootstrap/bootstrap-icons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/admin/css/main.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/index.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/markers_plugin.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/items_css/location_type_5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/items_css/location_type_6.css') }}">
  <style>
    body {
      margin: 0;
    }
  </style>
</head>

<body>
  <div id="start-display" style="background-image: url('{{ $bgStarterUrl }}');">
    <button class="btn btn-primary rounded-pill btn-lg" id="play-audio-btn">{{ __('start_tour') }}</button>
  </div>

  <audio id="background-audio" style="display: none" loop data-audio-url="{{ $audioUrl }}">
    <source src="{{ $audioUrl }}" type="audio/mpeg">
    Your browser does not support the audio element.
  </audio>


  <style>
    #start-display {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      opacity: 1;
      transition: opacity 1s ease-in-out;
    }

    #start-display.fade-out {
      opacity: 0;
    }

    .main-side-bar {
      transform: translateX(-120%);
      transition: transform 0.5s ease-in-out;
    }

    .main-side-bar.show {
      transform: translateX(0);
    }

    .main-side-bar.hide {
      transform: translateX(-120%);
    }

    #play-audio-btn {
      background-image: linear-gradient(to right, #00aaff, #bbe5ff);
      border: none;
      color: white;
      transition: none;
    }
  </style>

  @yield('content')




  <script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
  <script>
    window.baseUrl = "{{ asset('') }}";
    const locationIdCurrent = @json(session('location_id_current'));
  </script>
  <script src="{{ mix('js/web_app.js') }}"></script>
</body>

</html>
