<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Trang quản lý</title>

  <!-- Meta -->
  <meta name="description" content="Trang quản lý" />
  <meta name="author" content="Hoang Nhat Minh" />
  <link rel="canonical" href="http://vrdemo.test">
  <meta property="og:url" content="http://vrdemo.test">
  <meta property="og:title" content="VR360">
  <meta property="og:description" content="Trang quản lý">
  <meta property="og:type" content="Website">
  <meta property="og:site_name" content="VR360">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset('assets/images/logo.webp') }}" />

  <link rel="stylesheet" href="{{ asset('assets/sweetalert2/sweetalert2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/fonts/bootstrap/bootstrap-icons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/admin/css/main.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/admin/vendor/overlay-scroll/OverlayScrollbars.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/items_css/location_type_5.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/items_css/location_type_6.css') }}">
</head>

<body>
  <div class="page-wrapper">
    @include('layouts.components.admin.header')
    <div class="main-container">
      @include('layouts.components.admin.sidebar')
      <div class="app-container">
        @include('layouts.components.admin.hero')
        @yield('content')
        @include('layouts.components.admin.footer')
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
  <script src="{{ asset('assets/admin/vendor/overlay-scroll/jquery.overlayScrollbars.min.js') }}"></script>
  <script src="{{ asset('assets/admin/vendor/overlay-scroll/custom-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/admin/js/custom.js') }}"></script>
  <script src="{{ asset('assets/admin/js/todays-date.js') }}"></script>
</body>

</html>
