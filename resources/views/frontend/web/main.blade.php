@extends('layouts.web.app')

@section('content')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <style>
    .map-location-image {
      height: 150px;
      width: 300px;
    }

    .high-index {
      z-index: 100;
    }

    .nav-item {
      position: relative;
      padding: 0;
    }

    .nav-item::before {
      content: "";
      position: absolute;
      top: 50%;
      left: 0;
      width: 3px;
      height: 90%;
      background-color: white;
      transform: translateY(-50%);
    }

    ul {
      padding: 0;
    }

    .web-logo {
      max-width: 100%;
      height: auto;
      width: 150px;
    }

    .nav-link,
    .location-item {
      transition: all 0.3s ease;
    }

    .nav-link:hover,
    .location-item:hover {
      transform: scale(1.1);
      color: #47b8f5 !important;
    }

    .teal-color {
      background-color: #0369a1;
      height: 42px;
      width: 41px;
      font-size: larger;
      padding: 0
    }

    .large-font {
      font-size: x-large;
    }

    .largest-font {
      font-size: x-large;
    }







    button {
      transition: all 0.3s ease-in-out;
      position: relative;
    }

    @keyframes hideButtons {
      0% {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
      }

      100% {
        opacity: 0;
        visibility: hidden;
        transform: translateY(50px);
      }
    }

    @keyframes showButtons {
      0% {
        opacity: 0;
        visibility: hidden;
        transform: translateY(50px);
      }

      100% {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
      }
    }

    .hidden-animation {
      opacity: 0;
      visibility: visible;
      transform: translateY(50px);
      animation: hideButtons 0.5s forwards;
    }

    .show-animation {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
      animation: showButtons 0.5s forwards;
    }

    #three-dots {
      z-index: 10;
    }

    .voice_reader_avatar {
      height: 150px;
      width: 150px;
      border-radius: 50%;
    }

    .voice-reader-avatar-holder {
      position: relative;
      margin-right: 80px;
    }

    .voice-toggle-btn {
      position: absolute;
      top: 100px;
      left: 50%;
      transform: translateX(-50%);
      padding: 0px 10px;
      font-size: 20px;
      color: #fff;
      border: 1px solid #ccc;
      border-radius: 4px;
      background-color: #ff4c4c;
      cursor: pointer;
      white-space: nowrap;
    }

    .info-btn {
      position: absolute;
      top: 0px;
      left: 10px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: #0369a1;
      color: white;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 12px;
      font-size: larger;
    }

    .voice-toggle-btn:hover {
      background-color: #f5f5f5;
      color: black;
    }

    .disabled-color {
      background-color: #050505;
    }

    .info-btn:hover {
      background-color: #025c87;
    }






    .info-description {
      display: none;
      overflow: hidden;
      position: absolute;
      top: -210px;
      left: -50%;
      /* transform: translateX(-50%); */
    }

    #location-description-voice {
      height: 200px;
      width: 200px;
      overflow-y: scroll;
    }

    .show-description-custom {
      display: block;
      animation: slideDown 0.5s ease-out;
    }


    .hide-description-custom {
      animation: slideUp 0.5s ease-in;
    }

    /* Slide down effect */
    @keyframes slideDown {
      0% {
        transform: translateY(-100%);
      }

      100% {
        transform: translateY(0);
      }
    }

    /* Slide up effect */
    @keyframes slideUp {
      0% {
        transform: translateY(0);
      }

      100% {
        transform: translateY(-100%);
      }
    }


    .next-location-css {
      cursor: pointer;
      backdrop-filter: blur(5px);
      user-select: none;
      -webkit-user-select: none;
      -ms-user-select: none;
      -moz-user-select: none;
    }

    .next-location-btn {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: rgba(0, 0, 0, 0.7);
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease-in-out;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
    }

    .next-location-btn i {
      color: white;
      font-size: 1.5rem;
    }

    .next-location-btn:hover {
      background-color: rgba(0, 0, 0, 0.9);
      transform: scale(1.1);
    }

    #share_panel {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
  </style>
  <div id="app" class="w-100 position-relative">
    <div id="viewer" style="width: 100vw; height: 100vh;"></div>
    <div class="main-side-bar position-absolute top-0 start-0 mt-3 ms-3 fw-bolder high-index">
      <img class="web-logo" src="{{ $logoMainUrl }}" alt="logo" class="main-logo" draggable="false">
      @if ($categories->isEmpty())
        <div class="text-center p-4 mt-3">
          <h4>Chưa có điểm ảnh nào</h4>
        </div>
      @else
        <ul class="nav flex-column mt-3" id="location-list">
          @foreach ($categories as $category)
            @if (!$category->parent_id)
              <li class="nav-item">
                <a class="nav-link text-white text-decoration-none" data-bs-toggle="collapse"
                  href="#category-{{ $category->id }}" role="button" draggable="false">
                  {{ $category->name ?? 'Không tên' }}
                  <i class="bi bi-caret-down ms-3"></i>
                </a>

                <div class="collapse" id="category-{{ $category->id }}">
                  @if ($category->location->count() > 0)
                    <ul class="list-unstyled ms-3">
                      @foreach ($category->location()->orderBy('sort')->get() as $item_location_outside)
                        <li>
                          <a class="nav-link location-item text-white text-decoration-none hover-trigger" href="#"
                            onclick="updateOrder({{ $item_location_outside->id }})"
                            data-value-id="{{ $item_location_outside->id }}" draggable="false"
                            data-image-src="{{ $item_location_outside->paronama->image }}"
                            data-image-alt="{{ $item_location_outside->name }}">
                            {{ $item_location_outside->name ?? 'Không tên' }}
                          </a>
                          <i class="bi bi-geo-alt-fill text-white ms-1"></i>
                        </li>
                      @endforeach
                    </ul>
                  @endif

                  <!-- Submenu for child categories -->
                  @if ($category->children->count() > 0)
                    <ul class="list-unstyled ms-3">
                      @foreach ($category->children()->orderBy('sort')->get() as $child)
                        <li class="nav-item-location">
                          <a class="nav-link text-white text-decoration-none" data-bs-toggle="collapse"
                            href="#child-{{ $child->id }}" role="button" draggable="false">
                            {{ $child->name ?? 'Không tên' }}
                            <i class="bi bi-caret-down ms-3"></i>
                          </a>

                          <div class="collapse" id="child-{{ $child->id }}">
                            @if ($child->location->count() > 0)
                              <ul class="list-unstyled ms-3">
                                @foreach ($child->location()->orderBy('sort')->get() as $item_location)
                                  <li>
                                    <a class="nav-link location-item text-white text-decoration-none hover-trigger"
                                      href="#" onclick="updateOrder({{ $item_location->id }})"
                                      data-value-id="{{ $item_location->id }}" draggable="false"
                                      data-image-src="{{ $item_location->paronama->image }}"
                                      data-image-alt="{{ $item_location->name }}">
                                      {{ $item_location->name ?? 'Không tên' }}
                                      <i class="bi bi-geo-alt-fill text-white ms-1"></i>
                                    </a>
                                  </li>
                                @endforeach
                              </ul>
                            @endif
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  @endif
                </div>
              </li>
            @endif
          @endforeach
        </ul>
      @endif
    </div>
    <div class="position-absolute bottom-0 start-0 rounded-circle high-index">
      <button class="btn btn-primary rounded-circle mb-3 ms-3 teal-color" id="toggle-sidebar-btn">
        <i class="bi bi-list-ul fw-bold text-white" id="sidebar-icon"></i>
      </button>
      <button class="btn btn-primary rounded-circle mb-3 ms-2 teal-color">
        <i class="bi bi-share fw-bold text-white" onclick="showSharePanel()"></i>
      </button>
      <button class="btn btn-primary rounded-circle mb-3 ms-2 teal-color" onclick="changeLanguage()">
        @if (App::getLocale() == 'vi')
          <span class="flag-icon flag-icon-vn"></span>
        @else
          <span class="flag-icon flag-icon-us"></span>
        @endif
      </button>
    </div>
    <div class="d-flex flex-column position-absolute bottom-0 end-0 rounded-circle me-3 high-index">
      <div class="button-container-right">
        <button type="button"
          class="btn btn-primary d-flex align-items-center justify-content-center rounded-circle mb-2 teal-color"
          title="{{ __('geo_location') }}" id="showMapLocation">
          <i class="bi bi-geo-alt-fill fw-bold text-white large-font"></i>
        </button>
        <button type="button"
          class="btn btn-primary d-flex align-items-center justify-content-center rounded-circle mb-2 teal-color"
          title="{{ __('screenshot') }}">
          <i class="bi bi-camera fw-bold text-white large-font" id="screenshot-btn"></i>
        </button>
        <button type="button" id="toggle-volume-bg"
          class="btn btn-primary d-flex align-items-center justify-content-center rounded-circle mb-2 teal-color"
          title="{{ __('toggle_volume') }}">
          <i class="bi bi-volume-up-fill fw-bold text-white large-font" id="volume-icon"></i>
        </button>
        <button type="button" id="toggle-rotate-btn"
          class="btn btn-primary d-flex align-items-center justify-content-center rounded-circle mb-2 teal-color"
          title="{{ __('auto_rotate') }}">
          <i class="bi bi-play fw-bold text-white largest-font" id="autorotate-icon"></i>
        </button>
        <button type="button" id="fullscreen-btn"
          class="btn btn-primary d-flex align-items-center justify-content-center rounded-circle mb-2 teal-color"
          title="{{ __('fullscreen') }}">
          <i class="bi bi-arrows-fullscreen fw-bold text-white" id="fullscreen-icon"></i>
        </button>
      </div>
      <button class="btn btn-primary d-flex align-items-center justify-content-center rounded-circle mb-2 teal-color"
        id="three-dots" title="{{ __('three_dots') }}">
        <i class="bi bi-three-dots fw-bold text-white"></i>
      </button>
    </div>

    <div class="position-absolute bottom-0 end-0 rounded-circle mb-2 voice-reader-avatar-holder high-index">
      <img src="{{ $voice_reader_avatar }}" alt="avatar_nguoi_doc" class="voice_reader_avatar" draggable="false">

      <audio id="voice-reader-audio" style="display: none">
        <source src="" type="audio/mpeg">
        Your browser does not support the audio element.
      </audio>

      <button class="voice-toggle-btn" title="Tắt bật giọng đọc" id="voice-toggle-btn">
        <i class="bi bi-volume-up-fill" id="voice-toggle-icon-btn"></i>
      </button>

      <button class="info-btn" title="Thông tin" id="info-btn">
        <i class="bi bi-info-lg"></i>
      </button>

      <!-- New div for textarea -->
      <div id="info-description" class="info-description hide-description-custom form-floating">
        <div id="location-description-voice" class="card shadow px-2 py-1"></div>
      </div>
    </div>

    <div
      class="position-absolute top-50 end-0 next-location-css bg-dark bg-opacity-50 text-light px-3 py-2 align-items-center gap-2 rounded-start shadow-lg"
      onclick="updateOrder()" id="next-location-action">
      <span class="fw-semibold text-uppercase" id="next-location-name"></span>
      <button class="next-location-btn">
        <i class="bi bi-arrow-90deg-right"></i>
      </button>
    </div>


    {{-- @dd(Session::get('locale')) --}}
  </div>

  {{-- Youtube iframe --}}
  <div class="overlay" id="videoOverlay">
    <iframe id="youtubeIframe" src="" frameborder="0" allow="autoplay; encrypted-media"
      allowfullscreen></iframe>
  </div>
  {{-- End youtube --}}

  {{-- Hover_location_show --}}
  <div class="overlay-low">
    <img src="" alt="" class="hover-image-location">
  </div>
  {{-- End_over_location_show --}}

  <div class="overlay" id="mapLocation">
    <div id="map_location" style="height: 600px; width: 800px;"></div>
  </div>

  <div class="overlay" id="shareVR" onclick="hideSharePanel(event)">
    <div id="share_panel" class="p-4"
      style="background-color: #f8f9fa67; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
      <h3 class="text-center mb-4">Chia sẻ trải nghiệm này</h3>
      <div class="d-flex justify-content-center gap-3">
        <button class="btn btn-primary d-flex align-items-center gap-2" onclick="shareOnFacebook()">
          <i class="bi bi-facebook"></i> Facebook
        </button>
        <button class="btn btn-info d-flex align-items-center gap-2" onclick="shareOnTwitter()">
          <i class="bi bi-twitter"></i> Twitter
        </button>
        <button class="btn btn-danger d-flex align-items-center gap-2" onclick="shareOnGmail()">
          <i class="bi bi-envelope"></i> Gmail
        </button>
        <button class="btn btn-success d-flex align-items-center gap-2" onclick="shareOnZalo()">
          <i class="bi bi-chat-left-text"></i> Zalo
        </button>
      </div>

      <div class="mt-4">
        <div class="d-flex align-items-center">
          <input type="text" id="website-domain" class="form-control" value="" readonly
            style="width: 100%; margin-right: 10px;">
          <button class="btn btn-light" onclick="copyToClipboard()">
            <i class="bi bi-clipboard"></i>
          </button>
        </div>
      </div>
    </div>
  </div>



  <script>
    let language = @json(app()->getLocale());
    let locationYaw = @json($locationYaw);
    let locationPitch = @json($locationPitch);
    let locationData = @json($locations);

    // console.log(locationData);

    function changeLanguage() {
      fetch(`/change-language/api-call`, {
          method: 'GET',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => response.json())
        .then(data => {
          location.reload();
        });
    }

    function hideMap() {
      document.getElementById("mapLocation").style.display = "none";
    }


    document.addEventListener("DOMContentLoaded", function() {
      const threeDotsButton = document.getElementById("three-dots");
      const buttons = document.querySelectorAll(".button-container-right button");
      const fullscreen_icon = document.getElementById('fullscreen-icon');

      threeDotsButton.addEventListener("click", function() {
        buttons.forEach(button => {
          if (button.classList.contains("hidden-animation")) {
            button.classList.remove("hidden-animation");
            button.classList.add("show-animation");
          } else {
            button.classList.remove("show-animation");
            button.classList.add("hidden-animation");
          }
        });
      });

      document.getElementById('fullscreen-btn').addEventListener('click', function() {
        if (!document.fullscreenElement) {
          document.documentElement.requestFullscreen();
          fullscreen_icon.classList.remove("bi-arrows-fullscreen");
          fullscreen_icon.classList.add("bi-fullscreen-exit");
        } else {
          document.exitFullscreen();
          fullscreen_icon.classList.remove("bi-fullscreen-exit");
          fullscreen_icon.classList.add("bi-arrows-fullscreen");
        }
      });




      document.getElementById('info-btn').addEventListener('click', function() {
        const infoDescription = document.getElementById('info-description');

        if (infoDescription.classList.contains('hide-description-custom')) {
          infoDescription.classList.remove('hide-description-custom');
          infoDescription.classList.add('show-description-custom');
        } else {
          infoDescription.classList.remove('show-description-custom');
          infoDescription.classList.add('hide-description-custom');
        }
      });


      if (window.innerWidth >= 768) {
        document.querySelectorAll(".hover-trigger").forEach(anchor => {
          anchor.addEventListener("mouseenter", function() {
            const overlay = document.querySelector(".overlay-low");
            const img = overlay.querySelector(".hover-image-location");

            // Lấy giá trị từ data attributes của thẻ <a> đang hover
            let newSrc = this.getAttribute("data-image-src");
            const newAlt = this.getAttribute("data-image-alt");

            newSrc = newSrc.replace(/\/[^/]+$/, '/low.webp');

            // Cập nhật ảnh
            img.src = window.location.origin + "/storage/" + newSrc;
            img.alt = newAlt;

            // Hiển thị overlay
            overlay.style.display = "block";
          });

          anchor.addEventListener("mouseleave", function() {
            document.querySelector(".overlay-low").style.display = "none";
          });
        });
      }

      let map;

      const showMapLocation = document.getElementById("showMapLocation");
      showMapLocation.addEventListener('click', function() {
        document.getElementById("mapLocation").style.display = "flex";
        if (!map) {
          createMap();
        }
      });

      function createMap() {
        map = L.map('map_location').setView([locationYaw, locationPitch], 12); // Khởi tạo map chỉ một lần

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        locationData.forEach(location => {
          if (location.yaw && location.pitch) {
            let marker = L.marker([location.yaw, location.pitch], {
              title: location.name,
              riseOnHover: true,
            }).addTo(map);

            let imagePath = location.paronama.image;
            let folderPath = imagePath.replace(/\/[^/]+$/, '/low.webp');
            let imageUrl = `${window.location.origin}/storage/${folderPath}`;

            let popupContent = `
                <div style="text-align: center;">
                    <h5 class="mb-1">${location.name}</h5>
                    <img class="map-location-image" src="${imageUrl}" alt="${location.name}">
                    <p>Latitude: ${location.yaw}</p>
                    <p>Longitude: ${location.pitch}</p>
                    <a href="#" onclick="(function() { updateOrder(${location.id}); hideMap(); })(); return false;">Ghé thăm</a>
                </div>
            `;

            marker.bindPopup(popupContent);

            marker.on('click', function() {
              marker.openPopup();
            });
          }
        });
      }

      document.getElementById("mapLocation").addEventListener("click", function(event) {
        if (event.target === this) {
          this.style.display = "none";
        }
      });




      document.getElementById('website-domain').value = window.location.hostname;
    });







    function copyToClipboard() {
      var copyText = document.getElementById("website-domain");
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      document.execCommand("copy");
    }


    function hideSharePanel(event) {
      if (event.target === document.getElementById('shareVR')) {
        document.getElementById('shareVR').style.display = 'none';
      }
    }

    function showSharePanel() {
      document.getElementById('shareVR').style.display = 'flex';
    }


    function shareOnFacebook() {
      const url = encodeURIComponent(window.location.href);
      const fbUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
      window.open(fbUrl, '_blank');
    }

    function shareOnTwitter() {
      const url = encodeURIComponent(window.location.href);
      const tweetUrl = `https://twitter.com/intent/tweet?url=${url}`;
      window.open(tweetUrl, '_blank');
    }

    function shareOnGmail() {
      const subject = encodeURIComponent('Check out this VR experience!');
      const body = encodeURIComponent(`I found this amazing VR experience: ${window.location.href}`);
      const gmailUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=&su=${subject}&body=${body}`;
      window.open(gmailUrl, '_blank');
    }

    function shareOnZalo() {
      const url = encodeURIComponent(window.location.href);
      const zaloUrl = `https://chat.zalo.me/https://www.zalo.me/share?url=${url}`;
      window.open(zaloUrl, '_blank');
    }
  </script>


  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
@endsection
