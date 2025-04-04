@extends('layouts.admin.app')

@section('content')
  <div class="container d-flex align-items-center justify-content-center" style="height: 80vh">
    <div class="card shadow-sm col-sm-8 col-md-6">
      <div class="card-body">
        <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="row mb-3">
            <div class="col-sm-6">
              <label class="form-label fw-bold">Tên Danh Mục (Tiếng Việt)</label>
              <input type="text" name="name" class="form-control" placeholder="Nhập tên danh mục" required>
              @error('name')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-sm-6">
              <label class="form-label fw-bold">Tên Danh Mục (Tiếng Anh)</label>
              <input type="text" name="name_en" class="form-control" placeholder="Enter category name in English">
              @error('name_en')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Nhạc nền</label>
            <input type="file" name="background_music" class="form-control" placeholder="Chọn File nhạc nền">
            @error('background_music')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Sắp xếp</label>
            <input type="number" name="sort" class="form-control">
            @error('sort')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Trạng thái</label>
            <select name="status" class="form-select">
              <option value="1">Hoạt động</option>
              <option value="0">Không hoạt động</option>
            </select>
            @error('status')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Danh mục cha</label>
            <select name="parent_id" class="form-select">
              <option value="">Không có</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}">
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('parent_id')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Lưu danh mục</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
