@extends('layouts.admin.app')

@section('content')
  @include('layouts.components.web.alert')

  <div class="app-body">
    <a class="btn btn-success mb-3" href="{{ route('gallery.add') }}">Thêm ảnh</a>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">STT</th>
          <th scope="col">Tên</th>
          <th scope="col">Slug</th>
          <th scope="col">Hình ảnh</th>
          <th scope="col">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($paronamas as $key => $paronama)
          <tr>
            <th scope="row">{{ $key + 1 }}</th>
            <td>{{ $paronama->name }}</td>
            <td>{{ $paronama->slug }}</td>
            <td>
              <img src="{{ Storage::url($paronama->image) }}" alt="{{ $paronama->slug }}"
                style="height: 25px; width: 50px;">
            </td>
            <td>
              <form class="delete-form" action="{{ route('gallery.delete') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $paronama->id }}">
                <button type="button" class="btn btn-danger delete-button">Xóa</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll(".delete-button").forEach(button => {
        button.addEventListener("click", function() {
          Swal.fire({
            title: "Xóa ảnh này?",
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
