@extends('layouts.admin.app')

@section('content')
  <div class="container d-flex align-items-center justify-content-center" style="height: 80vh">
    <div class="card shadow-sm col-sm-8 col-md-6">
      <div class="card-body">
        <form action="{{ route('category.update.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" value="{{ $category->id }}" name="category_id">
          <div class="row mb-3">
            <div class="col-sm-6">
              <label class="form-label fw-bold">Tên Danh Mục (Tiếng Việt)</label>
              <input type="text" name="name" class="form-control" placeholder="Nhập tên danh mục"
                value="{{ old('name', $category->getRawNameAttribute()['vi'] ?? '') }}" required>
              @error('name')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-sm-6">
              <label class="form-label fw-bold">Tên Danh Mục (Tiếng Anh)</label>
              <input type="text" name="name_en" class="form-control" placeholder="Enter category name in English"
                value="{{ old('name_en', $category->getRawNameAttribute()['en'] ?? '') }}">
              @error('name_en')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Nhạc nền</label>
            <input type="file" name="background_music" class="form-control" placeholder="Chọn File nhạc nền">
            @if (isset($category) && $category->background_music)
              <label class="mt-2 form-label">Nhạc nền hiện tại</label>
              <div>
                <audio controls>
                  <source src="{{ asset('storage/' . $category->background_music) }}" type="audio/mpeg">
                  Trình duyệt của bạn không hỗ trợ phát nhạc.
                </audio>
              </div>
            @endif
            @error('background_music')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Sắp xếp</label>
            <input type="number" name="sort" class="form-control" placeholder="Tùy chọn"
              value="{{ old('sort', $category->sort) }}">
            @error('sort')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Trạng thái</label>
            <select name="status" class="form-select">
              <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
              <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>Không hoạt động
              </option>
            </select>
            @error('status')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Danh mục cha</label>
            <select name="parent_id" class="form-select">
              <option value="">Không có</option>
              @foreach ($categories as $item)
                <option value="{{ $item->id }}"
                  {{ old('parent_id', $category->parent_id) == $item->id ? 'selected' : '' }}>
                  {{ $item->name }}
                </option>
              @endforeach
            </select>
            @error('parent_id')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Cập nhật danh mục</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
