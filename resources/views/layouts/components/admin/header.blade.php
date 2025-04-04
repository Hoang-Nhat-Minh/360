<div class="app-header d-flex align-items-center">
  <div class="d-flex">
    <button class="toggle-sidebar" id="toggle-sidebar">
      <i class="bi bi-list lh-1"></i>
    </button>
    <button class="pin-sidebar" id="pin-sidebar">
      <i class="bi bi-list lh-1"></i>
    </button>
  </div>

  <div class="app-brand py-2 ms-3">
    <a href="{{ route('index') }}" class="d-sm-block d-none">
      <img src="{{ asset('assets/images/logo.webp') }}" class="logo" alt="VRLogo" />
    </a>
    <a href="{{ route('index') }}" class="d-sm-none d-block">
      <img src="{{ asset('assets/images/logo.webp') }}" class="logo" alt="VRLogo" />
    </a>
  </div>

  <div class="header-actions col">
    <div class="dropdown ms-2">
      <a id="userSettings" class="dropdown-toggle d-flex py-2 align-items-center text-decoration-none" href="#!"
        role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ asset('assets/admin/images/user.jpg') }}" class="rounded-2 img-3x" alt="Bootstrap Gallery" />
        <span class="ms-2 text-truncate d-lg-block d-none">{{ auth()->user()->name }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-end shadow-lg">
        <div class="mx-3 mt-2 d-grid">
          <form action="{{ route('logout.auth') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary btn-sm w-100">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
