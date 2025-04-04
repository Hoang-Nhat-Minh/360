@extends('layouts.admin.app')

@section('content')
  @include('layouts.components.web.alert')
  <div class="container-fluid mt-4 mb-4">
    <h2 class="mb-4">Cấu Hình Trang Web</h2>
    <form action="{{ route('setting.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="name" class="form-label">Tên trang web</label>
          <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $setting->name ?? '') }}" required>

          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="description" class="form-label">Mô tả trang web</label>
          <input type="text" name="description" id="description"
            class="form-control @error('description') is-invalid @enderror"
            value="{{ old('description', $setting->description ?? '') }}">

          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="keywords" class="form-label">Keywords</label>
          <input type="text" name="keywords" id="keywords"
            class="form-control @error('keywords') is-invalid @enderror"
            value="{{ old('keywords', $setting->keywords ?? '') }}">

          @error('keywords')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="author" class="form-label">Author</label>
          <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror"
            value="{{ old('author', $setting->author ?? '') }}">

          @error('author')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="google_site_verification" class="form-label">Google_Site_Verification</label>
          <input type="text" name="google_site_verification" id="google_site_verification"
            class="form-control @error('google_site_verification') is-invalid @enderror"
            value="{{ old('google_site_verification', $setting->google_site_verification ?? '') }}">

          @error('google_site_verification')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="yaw" class="form-label">Kinh Độ</label>
          <input type="text" name="yaw" id="yaw" class="form-control @error('yaw') is-invalid @enderror"
            value="{{ old('yaw', $setting->yaw ?? '') }}">

          @error('yaw')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6 mb-3">
          <label for="pitch" class="form-label">Vĩ Độ</label>
          <input type="text" name="pitch" id="pitch" class="form-control @error('pitch') is-invalid @enderror"
            value="{{ old('pitch', $setting->pitch ?? '') }}">

          @error('pitch')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="logo" class="form-label">Logo</label>
          <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror"
            accept="image">

          @error('logo')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if ($setting->logo)
            <div class="mt-2">
              <label for="current_logo" class="form-label">Logo hiện tại:</label>
              <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="img-thumbnail"
                style="max-width: 150px;" id="current_logo">
            </div>
          @endif
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="logoMain" class="form-label">Logo Menu</label>
          <input type="file" name="logoMain" id="logoMain"
            class="form-control @error('logoMain') is-invalid @enderror" accept="image">

          @error('logoMain')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if ($setting->logoMain)
            <div class="mt-2">
              <label for="current_logoMain" class="form-label">Logo Menu hiện tại:</label>
              <img src="{{ asset('storage/' . $setting->logoMain) }}" alt="logoMain" class="img-thumbnail"
                style="max-width: 150px;" id="current_logoMain">
            </div>
          @endif
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="voice_reader_avater" class="form-label">Avatar Người Đọc</label>
          <input type="file" name="voice_reader_avater" id="voice_reader_avater"
            class="form-control @error('voice_reader_avater') is-invalid @enderror" accept="image">

          @error('voice_reader_avater')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if ($setting->voice_reader_avater)
            <div class="mt-2">
              <label for="current_voice_reader_avater" class="form-label">Avatar Người Đọc hiện tại:</label>
              <img src="{{ asset('storage/' . $setting->voice_reader_avater) }}" alt="voice_reader_avater"
                class="img-thumbnail" style="max-width: 150px;" id="current_voice_reader_avater">
            </div>
          @endif
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <div class="file_sound">
            <label for="background_music" class="form-label">Nhạc nền</label>
            <input type="file" name="background_music" id="background_music"
              class="form-control @error('background_music') is-invalid @enderror" accept="audio/*">

            @error('background_music')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($setting->background_music)
              <div class="mt-2">
                <label for="current_bg_music" class="form-label">Nhạc nền hiện tại:</label>
                <audio controls>
                  <source src="{{ asset('storage/' . $setting->background_music) }}" type="audio/mpeg"
                    id="current_bg_music">
                  Your browser does not support the audio element.
                </audio>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="bg_starter" class="form-label">Background Starter</label>
          <input type="file" name="bg_starter" id="bg_starter"
            class="form-control @error('bg_starter') is-invalid @enderror" accept="image">

          @error('bg_starter')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          @if ($setting->bg_starter)
            <div class="mt-2">
              <label for="current_bg_starter" class="form-label">Background hiện tại:</label>
              <img src="{{ asset('storage/' . $setting->bg_starter) }}" alt="bg_starter" class="img-thumbnail"
                style="max-width: 150px;" id="current_bg_starter">
            </div>
          @endif
        </div>
      </div>

      <button type="submit" class="btn btn-primary w-100">Lưu</button>
    </form>
  </div>
@endsection
