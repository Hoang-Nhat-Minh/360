@extends('layouts.admin.app')

@section('content')
  <script src="https://cdn.tiny.cloud/1/coimli1zufzen9bkrl2hlb0aldob0hpzwmhh4ovc0q8inm1o/tinymce/7/tinymce.min.js"
    referrerpolicy="origin"></script>

  <div class="container-fluid mt-4 mb-4">
    <h2 class="mb-4">Sửa điểm ảnh</h2>
    <form action="{{ route('location.update', $location->id) }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="name" class="form-label">Tên địa điểm</label>
          <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $location->getRawNameAttribute()['vi'] ?? '') }}" required>

          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label for="name_en" class="form-label">Tên địa điểm (English)</label>
          <input type="text" name="name_en" id="name_en" class="form-control @error('name_en') is-invalid @enderror"
            value="{{ old('name_en', $location->getRawNameAttribute()['en'] ?? '') }}" required>

          @error('name_en')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <script>
        document.getElementById('name').addEventListener('input', function() {
          const name = this.value;

          // Tạo slug bằng cách gọi hàm slugify
          function slugify(string) {
            const a = 'àáäâãåăæąçćčđďèéěėëêęğǵḧìíïîįłḿǹńňñòóöôœøṕŕřßşśšșťțùúüûǘůűūųẃẍÿýźžż·/_,:;';
            const b = 'aaaaaaaaacccddeeeeeeegghiiiiilmnnnnooooooprrsssssttuuuuuuuuuwxyyzzz------';
            const p = new RegExp(a.split('').join('|'), 'g');
            return string
              .toString()
              .toLowerCase()
              .replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a')
              .replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e')
              .replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i')
              .replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o')
              .replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u')
              .replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y')
              .replace(/đ/gi, 'd')
              .replace(/\s+/g, '-')
              .replace(p, (c) => b.charAt(a.indexOf(c)))
              .replace(/&/g, '-and-')
              .replace(/[^\w\-]+/g, '')
              .replace(/\-\-+/g, '-')
              .replace(/^-+/, '')
              .replace(/-+$/, '');
          }

          const slug = slugify(name);

          // Cập nhật giá trị slug
          document.getElementById('slug').value = slug;
        });
      </script>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="slug" class="form-label">Slug</label>
          <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', $location->slug ?? '') }}" required readonly>

          @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6 mb-3">
          <label for="paronama_id" class="form-label">Ảnh 360</label>
          <select name="paronama_id" id="paronama_id" class="form-select @error('paronama_id') is-invalid @enderror"
            required>
            <option value="" disabled selected>Chọn ảnh 360 từ thư viện</option>
            @foreach ($paronamas as $paronama)
              <option value="{{ $paronama->id }}" {{ $location->paronama_id == $paronama->id ? 'selected' : '' }}>
                {{ $paronama->name }}
              </option>
            @endforeach
          </select>

          @error('paronama_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="yaw" class="form-label">Kinh Độ</label>
          <input type="text" name="yaw" id="yaw" class="form-control @error('yaw') is-invalid @enderror"
            value="{{ old('yaw', $location->yaw ?? '') }}">

          @error('yaw')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-md-6 mb-3">
          <label for="pitch" class="form-label">Vĩ Độ</label>
          <input type="text" name="pitch" id="pitch" class="form-control @error('pitch') is-invalid @enderror"
            value="{{ old('pitch', $location->pitch ?? '') }}">

          @error('pitch')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <div class="file_sound">
            <label for="voice" class="form-label">File âm thanh (Vietnamese)</label>
            <input type="file" name="voice" id="voice" class="form-control @error('voice') is-invalid @enderror"
              accept="audio/*">

            @error('voice')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if (isset($location->voice) && file_exists(public_path('storage/' . $location->voice)))
              <audio controls>
                <source src="{{ asset('storage/' . $location->voice) }}" type="audio/mpeg">
                Your browser does not support the audio element.
              </audio>
            @endif
          </div>
        </div>

        <div class="col-md-6 mb-3">
          <div class="file_sound">
            <label for="voice_en" class="form-label">File âm thanh (English)</label>
            <input type="file" name="voice_en" id="voice_en"
              class="form-control @error('voice_en') is-invalid @enderror" accept="audio/*">

            @error('voice_en')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if (isset($location->voice_en) && file_exists(public_path('storage/' . $location->voice_en)))
              <audio controls>
                <source src="{{ asset('storage/' . $location->voice_en) }}" type="audio/mpeg">
                Your browser does not support the audio element.
              </audio>
            @endif
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="next_location_id" class="form-label">Điểm ảnh kế tiếp</label>
          <select name="next_location_id" id="next_location_id"
            class="form-select @error('next_location_id') is-invalid @enderror">
            <option value="" disabled selected>Chọn điểm kế tiếp</option>
            @foreach ($locations as $loc)
              @if ($location->id != $loc->id)
                <option value="{{ $loc->id }}" {{ $location->next_location_id == $loc->id ? 'selected' : '' }}>
                  {{ $loc->name }}
                </option>
              @endif
            @endforeach
          </select>

          @error('next_location_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="category_id" class="form-label">Thuộc Danh Mục</label>
          <select name="category_id" class="form-select">
            @foreach ($categories as $category)
              <option value="{{ $category->id }}" @if (old('category_id', $location->category_id) == $category->id) selected @endif>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>


      <div class="row">
        <div class="col-md-12 mb-3">
          <div class="form-check form-switch">
            <input type="checkbox" name="status" id="status"
              class="form-check-input @error('status') is-invalid @enderror" {{ $location->status ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Tình trạng</label>

            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="description" class="form-label">Mô tả</label>
          <textarea name="description" id="description">{{ old('description', $location->getRawDescriptionAttribute()['vi'] ?? '') }}</textarea>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mb-3">
          <label for="description_en" class="form-label">Mô tả (English)</label>
          <textarea name="description_en" id="description_en">{{ old('description_en', $location->getRawDescriptionAttribute()['en'] ?? '') }}</textarea>
        </div>
      </div>

      <button type="submit" class="btn btn-lg btn-primary w-100">Lưu</button>
    </form>
  </div>



  <script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
  </script>
@endsection
