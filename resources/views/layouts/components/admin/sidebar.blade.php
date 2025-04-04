<nav id="sidebar" class="sidebar-wrapper">
  <div class="shop-profile">
    <p id="greeting" class="mb-1 fw-bold text-primary"></p>
    <p class="m-0">Thái Nguyên, <span id="live-time"></span></p>
  </div>

  <script>
    //     function getGreeting(hour) {
    //       if (hour < 12) return '(●\'◡\'●)';
    //       if (hour < 18) return '╰(*°▽°*)╯';
    //       if (hour < 21) return 'ლ(╹◡╹ლ)';
    //       return '(´▽`ʃ♡ƪ)';
  //   }


  function updateTime() {
    const timeElement = document.getElementById('live-time');
    const greetingElement = document.getElementById('greeting');
    const now = new Date();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    // greetingElement.textContent = getGreeting(now.getHours());

    timeElement.textContent = `${hours}:${minutes}:${seconds}`;
    }

    setInterval(updateTime, 1000);
    updateTime();
  </script>


  <div class="sidebarMenuScroll">
    <ul class="sidebar-menu">
      <li class="{{ Route::currentRouteName() === 'dashboard' ? 'active current-page' : '' }}">
        <a href="{{ route('dashboard') }}">
          <i class="bi bi-pie-chart"></i>
          <span class="menu-text">Dashboard</span>
        </a>
      </li>
      <li
        class="{{ in_array(Route::currentRouteName(), ['category', 'category.add', 'category.edit']) ? 'active current-page' : '' }}">
        <a href="{{ route('category') }}">
          <i class="bi bi-archive-fill"></i>
          <span class="menu-text">Quản Lý Danh Mục</span>
        </a>
      </li>
      <li
        class="{{ in_array(Route::currentRouteName(), ['location', 'location.add', 'location.edit']) ? 'active current-page' : '' }}">
        <a href="{{ route('location') }}">
          <i class="bi bi-geo-alt"></i>
          <span class="menu-text">Quản Lý Điểm Ảnh</span>
        </a>
      </li>
      <li class="{{ in_array(Route::currentRouteName(), ['gallery', 'gallery.add']) ? 'active current-page' : '' }}">
        <a href="{{ route('gallery') }}">
          <i class="bi bi-images"></i>
          <span class="menu-text">Thư Viện Ảnh</span>
        </a>
      </li>
    </ul>
    <hr>
    <ul class="sidebar-menu">
      <li class="{{ in_array(Route::currentRouteName(), ['setting']) ? 'active current-page' : '' }}">
        <a href="{{ route('setting') }}">
          <i class="bi bi-gear-wide-connected"></i>
          <span class="menu-text">Cấu Hình Trang</span>
        </a>
      </li>
    </ul>
  </div>
</nav>
