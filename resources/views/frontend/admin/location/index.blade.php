@extends('layouts.admin.app')

@section('content')

  <style>
    /* Flexbox Layout */
    .d-flex {
      display: flex;
    }

    /* Sidebar */
    .sidebar {
      width: 300px;
      height: 820px;
      overflow-y: auto;
      background-color: #f8f9fa;
      border-right: 1px solid #dee2e6;
      display: flex;
      flex-direction: column;
    }

    /* Search Bar */
    .search-bar {
      padding: 10px;
      border-bottom: 1px solid #dee2e6;
    }

    .search-bar input {
      width: 100%;
      padding: 8px;
      border: 1px solid #dee2e6;
      border-radius: 4px;
    }

    /* Content */
    .content {
      flex: 1;
      padding: 10px;
    }

    .content ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .content li {
      margin: 5px 0;
      font-size: 14px;
    }

    .icon {
      margin-right: 8px;
      font-size: 16px;
      vertical-align: middle;
    }

    /* Button */
    .btn-bottom {
      padding: 10px;
    }

    .btn-custom {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 4px;
      color: white;
      font-size: 14px;
      cursor: pointer;
    }

    .btn-primary-add {
      background-color: #007bff;
    }

    .btn-primary-add:hover {
      background-color: #0056b3;
    }

    .btn-primary-save {
      background-color: #198754;
    }

    .btn-primary-save:hover {
      background-color: #157347;
    }

    /* Viewer */
    #viewer {
      flex: 1;
      height: 820px;
      background-color: #e9ecef;
    }

    @media (max-width: 1440px) {
      #viewer {
        height: 820px;
      }

      .sidebar {
        height: 820px;
      }
    }

    @media (max-width: 1366px) {
      #viewer {
        height: 440px;
      }

      .sidebar {
        height: 440px;
      }
    }
  </style>
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/index.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/plugins/markers_plugin.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <div class="d-flex">
    <div class="sidebar">
      <!-- Search Bar -->
      <div class="search-bar">
        <input type="text" class="form-control" placeholder="Tìm kiếm...">
      </div>

      {{-- @dd($locations) --}}
      <!-- Scrollable Content -->
      <div class="content">
        @if ($locations->isEmpty())
          <div class="text-center p-4">
            <h4>Chưa có điểm ảnh nào</h4>
          </div>
        @else
          <ul class="list-unstyled" id="location-list">
            @foreach ($locations as $key => $location)
              <li id="location-{{ $location->id }}"
                class="d-flex justify-content-between location-item {{ $key == 0 ? 'active' : '' }}"
                onclick="updateOrder({{ $location->id }})" data-value-id="{{ $location->id }}">
                <div>
                  <span class="icon bi bi-geo-alt"></span>
                  {{ $location->name ?? 'Không tên' }}
                </div>


                <i class="icon bi bi-sort-alpha-down-alt handle"></i>
              </li>
            @endforeach
          </ul>
        @endif
      </div>

      <!-- Button at Bottom -->
      <div class="btn-bottom">
        <a class="btn btn-custom btn-primary-add" href="{{ route('location.add') }}" id="primaryAddLocation">
          <span class="icon bi-plus-circle"></span> Thêm điểm ảnh
        </a>

        <a href="#" class="btn btn-custom btn-primary-save" style="display: none" id="primarySaveListLocation">
          <span class="icon bi-save"></span> Cập nhật danh sách
        </a>
      </div>
    </div>

    <!-- Viewer Area -->
    <div id="app" class="w-100 position-relative">
      <div id="viewer" style="width: 100%;"></div>

      <div class="position-absolute top-0 start-0 ms-2 mt-2">
        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          Các Hot Spot
        </button>
        <div class="dropdown-menu p-2">
          <p class="text-muted fw-bold m-0">Danh sách: </p>
          <hr class="mt-0 mb-1">
          <ul id="hotlinks_list" style="min-width: max-content;padding:0"
            data-delete-route="{{ route('location.hotspot.delete') }}"
            data-delete-route-special="{{ route('location.special.hotspot.delete') }}">
          </ul>
        </div>
      </div>

      <div class="position-absolute bottom-0 start-50 translate-middle-x w-100 d-flex justify-content-between"
        style="background-color: rgba(0, 0, 0, 0.5)" style="background-color: lightslategray">
        <div class="left-btn d-flex">
          {{-- <button class="btn btn-success m-0 rounded-0" data-bs-toggle="modal" data-bs-target="#addHotSpot"><i
              class="bi bi-pin-angle-fill"></i></button> --}}
          <form action="{{ route('location.edit') }}" method="GET" class="m-0 d-inline">
            <input type="hidden" value="" class="location-id" name="location_id_edit">
            <button type="submit" class="btn btn-warning m-0 rounded-0">
              <i class="bi bi-wrench"></i>
            </button>
          </form>
        </div>
        <span class="text-white d-flex align-items-center" id="get-location-name-app"></span>
        <div class="right-btn d-flex">
          <form action="{{ route('location.delete') }}" method="POST" id="deleteLocationForm">
            @csrf
            <input type="hidden" value="" class="location-id" name="location_id">
            <button type="button" class="btn btn-danger m-0 rounded-0" data-bs-toggle="modal"
              data-bs-target="#deleteModal">
              <i class="bi bi-x-circle-fill"></i>
            </button>
          </form>
          <button class="btn btn-info m-0 rounded-0" id="saveEyeBtn">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa điểm ảnh</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Xóa điểm ảnh này?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" tabindex="-1" id="addHotSpot" aria-labelledby="Thêm Hot Spot" aria-hidden="true">
    <div class="modal-dialog modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm Hot Spot</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
              aria-controls="home" aria-selected="true">Hot Spot Link Địa Điểm</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" id="settings-tab" data-bs-toggle="tab" href="#settings" role="tab"
              aria-controls="settings" aria-selected="false">Hot Spot Đặc Biệt</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <form action="{{ route('location.hotspot.store') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="currentScore" class="form-label">Điểm hiện tại</label>
                <span class="form-control" id="get-location-name-modal"></span>
                <input type="hidden" class="location-id" value="{{ old('location_id') }}" name="location_id">
                @error('location_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="nextScore" class="form-label">Điểm kế tiếp</label>
                <div class="dropdown">
                  <input type="text" class="form-control" id="search-next-score" placeholder="Tìm điểm kế tiếp"
                    data-bs-toggle="dropdown">
                  <ul class="dropdown-menu" id="dropdown-menu" aria-labelledby="search-next-score">
                    @foreach ($locations as $location_select)
                      <li>
                        <a class="dropdown-item" href="#"
                          data-value="{{ $location_select->id }}">{{ $location_select->name }}
                        </a>
                      </li>
                    @endforeach
                  </ul>
                  <input type="hidden" name="link_to_location_id" id="link-to-location-id">
                </div>

                @error('link_to_location_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="hotspotType" class="form-label">Loại Hot Spot</label>
                <select class="form-select" id="hotspotType" name="type">
                  <option value="">Chọn loại Hot Spot</option>
                  <option value="1" {{ old('type') == 1 ? 'selected' : '' }}>Tổng quan</option>
                  <option value="2" {{ old('type') == 2 ? 'selected' : '' }}>Dưới đất</option>
                  <option value="3" {{ old('type') == 3 ? 'selected' : '' }}>Trên cao</option>
                  <option value="5" {{ old('type') == 5 ? 'selected' : '' }}>Thông Tin</option>
                </select>
                @error('type')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="yaw" class="form-label">Yaw (Tọa độ)</label>
                  <input type="text" class="yaw-value form-control" name="yaw" placeholder="Nhập giá trị Yaw"
                    value="{{ old('yaw') }}">
                  @error('yaw')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="pitch" class="form-label">Pitch (Tọa độ)</label>
                  <input type="text" class="pitch-value form-control" name="pitch"
                    placeholder="Nhập giá trị Pitch" value="{{ old('pitch') }}">
                  @error('pitch')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <button type="submit" class="btn btn-primary w-100">Tạo Hot Spot</button>
            </form>
          </div>



          <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            <form action="{{ route('location.special.hotspot.store') }}" method="POST">
              @csrf

              <input type="hidden" class="location-id" value="{{ old('location_id') }}" name="location_id">
              <div class="mb-3">
                <label for="specialType" class="form-label">Loại Hot Spot</label>
                <select class="form-select" id="specialType" name="specialType">
                  <option value="">Chọn loại Hot Spot</option>
                  <option value="4" {{ old('type') == 4 ? 'selected' : '' }}>Mặt Trời</option>
                  <option value="6" {{ old('type') == 6 ? 'selected' : '' }}>Video</option>
                  <option value="7" {{ old('type') == 7 ? 'selected' : '' }}>Thông Tin</option>
                </select>
                @error('specialType')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="yaw" class="form-label">Yaw (Tọa độ)</label>
                  <input type="text" class="yaw-value form-control" name="yaw" placeholder="Nhập giá trị Yaw"
                    value="{{ old('yaw') }}">
                  @error('yaw')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="pitch" class="form-label">Pitch (Tọa độ)</label>
                  <input type="text" class="pitch-value form-control" name="pitch"
                    placeholder="Nhập giá trị Pitch" value="{{ old('pitch') }}">
                  @error('pitch')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="video-link" class="form-label">Link Video (Dành cho hotspot video)</label>
                  <input type="text" class="video-link-value form-control" name="video-link"
                    placeholder="Nhập link video" value="{{ old('video-link') }}">
                  @error('video-link')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="info-content-vi" class="form-label">Nội dung thông tin Tiếng Việt (Dành cho hotspot
                    thông tin)</label>
                  <textarea name="info-content-vi" id="info-content-vi">{{ old('info-content-vi') }}</textarea>
                  @error('info-content-vi')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="info-content-en" class="form-label">Nội dung thông tin Tiếng Anh (Dành cho hotspot
                    thông tin)</label>
                  <textarea name="info-content-en" id="info-content-en">{{ old('info-content-en') }}</textarea>
                  @error('info-content-en')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <button type="submit" class="btn btn-primary w-100">Tạo Hot Spot</button>
            </form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
      </div>
    </div>
  </div>


  {{-- Youtube iframe --}}
  <div class="overlay" id="videoOverlay">
    <iframe id="youtubeIframe" src="" frameborder="0" allow="autoplay; encrypted-media"
      allowfullscreen></iframe>
  </div>
  {{-- End youtube --}}

  <script src="https://cdn.tiny.cloud/1/coimli1zufzen9bkrl2hlb0aldob0hpzwmhh4ovc0q8inm1o/tinymce/7/tinymce.min.js"
    referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: 'textarea',
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });
  </script>


  <script>
    window.baseUrl = "{{ asset('') }}";
    const locationIdCurrent = @json(session('location_id_current'));
  </script>
  <script src="{{ mix('js/app.js') }}"></script>
  <script type="module">
    import Sortable from '{{ asset('assets/sortablejs/sortable.core.esm.js') }}';


    document.addEventListener('DOMContentLoaded', function() {
      var el = document.getElementById('location-list');
      let primaryAddLocation = document.getElementById("primaryAddLocation");
      let primarySaveListLocation = document.getElementById("primarySaveListLocation");
      let jsonData = [];


      if (el) {
        var sortable = new Sortable(el, {
          handle: '.handle',
          easing: "cubic-bezier(1, 0, 0, 1)",
          animation: 150,

          onStart(evt) {
            primaryAddLocation.style.display = "none";
            primarySaveListLocation.style.display = "block";
          },

          onEnd(ent) {
            let orderedIds = [];

            let locationItems = document.querySelectorAll('#location-list li');

            locationItems.forEach(function(item) {
              let id = item.getAttribute('data-value-id');
              if (id) {
                orderedIds.push(id);
              }
            });

            jsonData = JSON.stringify(orderedIds);
          }
        });


        primarySaveListLocation.addEventListener('click', function() {
          let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

          fetch("{{ route('location.list.update') }}", {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
              },
              body: JSON.stringify({
                order: jsonData
              })
            })
            .then(response => response.json())
            .then(data => {
              console.log("Order updated successfully:", data);
              location.reload();
            })
            .catch(error => {
              console.error("Error updating order:", error);
            });
        });
      } else {
        console.error('Element #location-list not found');
      };
    });
  </script>


  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('search-next-score');
      const dropdownMenu = document.getElementById('dropdown-menu');
      const dropdownItems = dropdownMenu.getElementsByClassName('dropdown-item');
      const hiddenInput = document.getElementById('link-to-location-id');



      // Hiển thị dropdown khi người dùng focus vào input
      searchInput.addEventListener('focus', function() {
        dropdownMenu.style.display = 'block';
      });

      // Ẩn dropdown nếu người dùng click ra ngoài
      document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown')) {
          dropdownMenu.style.display = 'none';
        }
      });

      // Tìm kiếm trong danh sách dropdown
      searchInput.addEventListener('input', function() {
        const searchTerm = searchInput.value.toLowerCase();
        Array.from(dropdownItems).forEach(item => {
          const text = item.textContent || item.innerText;
          if (text.toLowerCase().includes(searchTerm)) {
            item.style.display = ''; // Hiển thị nếu có khớp
          } else {
            item.style.display = 'none'; // Ẩn nếu không khớp
          }
        });
      });

      // Chọn giá trị khi click vào một item trong dropdown
      Array.from(dropdownItems).forEach(item => {
        item.addEventListener('click', function() {
          searchInput.value = item.textContent;
          dropdownMenu.style.display = 'none'; // Ẩn dropdown sau khi chọn
          const selectedValue = item.getAttribute('data-value');

          // Gán giá trị vào hidden input
          hiddenInput.value = selectedValue;

          console.log('Selected value:', selectedValue);
        });
      });


      //Modal xóa Location
      document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        document.getElementById('deleteLocationForm').submit();
      });
    });
  </script>

  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const modalInstance = new bootstrap.Modal(document.getElementById('addHotSpot'), {
          backdrop: 'static',
          keyboard: false
        });
        modalInstance.show();
      });
    </script>
  @endif

  @include('layouts.components.alerts.alert')
@endsection
