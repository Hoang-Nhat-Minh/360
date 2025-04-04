@extends('layouts.admin.app')

@section('content')
  @include('layouts.components.web.alert')
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      {{-- <button class="btn btn-danger">
        <i class="bi bi-x-circle"></i> Xóa tất cả
      </button> --}}
      <a class="btn btn-primary" href="{{ route('category.add') }}">
        <i class="bi bi-plus-circle"></i> Thêm địa điểm
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead class="text-white">
          <tr>
            <th class="bg-secondary">ID</th>
            <th class="bg-secondary">Tên Danh Mục</th>
            <th class="bg-secondary">Sắp Xếp</th>
            <th class="bg-secondary">Status</th>
            <th class="bg-secondary">Danh Mục Cha</th>
            <th class="bg-secondary">Hành Động</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($categories as $category)
            <tr>
              <td>{{ $category->id }}</td>
              <td>{{ $category->name }}</td>
              <td>{{ $category->sort ?? '-' }}</td>
              <td>
                <span class="badge bg-{{ $category->status ? 'success' : 'danger' }}">
                  {{ $category->status ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td>{{ $category->parent->name ?? '-' }}</td>
              <td>
                <div class="btn-group">
                  <a href="{{ route('category.edit', $category->id) }}" class="btn rounded btn-warning me-3">
                    <i class="bi bi-pencil"></i> Sửa
                  </a>
                  <form action="{{ route('category.delete') }}" method="POST" class="d-inline">
                    @csrf

                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    <button type="submit" class="btn rounded btn-danger delete-button">
                      <i class="bi bi-trash"></i> Xóa
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll(".delete-button").forEach(button => {
        button.addEventListener("click", function() {
          event.preventDefault();

          Swal.fire({
            title: "Xóa danh mục này?",
            text: "Không thể hoàn tác!",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Hủy!",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Xóa!"
          }).then((result) => {
            if (result.isConfirmed) {
              this.closest("form").submit();
            }
          });
        });
      });
    });
  </script>
@endsection
