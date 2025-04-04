@extends('layouts.admin.app')

@section('content')
  <div class="app-body">
    <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" id="add_paronama">
      @csrf

      <div class="mb-3">
        <div class="row mb-3">
          <div class="col-sm-12 col-md-6">
            <label for="name" class="form-label">Tên ảnh 360</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
              oninput="generateSlug()" value="{{ old('name') }}">
            @error('name')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
          <div class="col-sm-12 col-md-6">
            <label for="slug" class="form-label">Slug (Tự sinh)</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug"
              readonly value="{{ old('slug') }}">
            @error('slug')
              <span class="text-danger">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="input-group mb-3">
              <label class="input-group-text" for="image360">Ảnh 360</label>
              <input type="file" class="form-control @error('image360') is-invalid @enderror" id="image360"
                name="image360" accept="image/*" onchange="previewImage()">
            </div>
            @error('image360')
              <span class="text-danger">{{ $message }}</span>
            @enderror
            <div class="image-container">
              <img id="image-preview" src="" alt="" style="max-width: 100%; display: none;">
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-success">Xác nhận</button>
    </form>
  </div>
  <div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
  </div>
  <script>
    const form = document.getElementById('add_paronama');
    const loadingOverlay = document.getElementById('loadingOverlay');

    form.addEventListener('submit', function() {
      // Hiển thị overlay
      loadingOverlay.classList.add('active');
      console.log('Loading overlay activated');
    });
    // Function to generate slug from name input
    function generateSlug() {
      const name = document.getElementById('name').value;
      const slug = removeVietnameseTones(name)
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
      document.getElementById('slug').value = slug;
    }


    function removeVietnameseTones(str) {
      return str
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd')
        .replace(/Đ/g, 'D');
    }

    function previewImage() {
      const fileInput = document.getElementById('image360');
      const preview = document.getElementById('image-preview');
      const file = fileInput.files[0];

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    }
  </script>
@endsection
