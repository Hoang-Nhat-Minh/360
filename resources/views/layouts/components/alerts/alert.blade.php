@if (session('alert_type') && session('alert_content'))
  <div class="toast align-items-center text-bg-{{ session('alert_type') }} border-0 position-fixed top-0 end-0 m-3"
    id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999">
    <div class="d-flex">
      <div class="toast-body">
        {{ session('alert_content') }}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
        aria-label="Close"></button>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toastElement = document.getElementById('alert_toast');
      if (toastElement) {
        const toast = new bootstrap.Toast(toastElement, {
          autohide: true,
          delay: 3000,
        });
        toast.show();
      }
    });
  </script>
@endif
