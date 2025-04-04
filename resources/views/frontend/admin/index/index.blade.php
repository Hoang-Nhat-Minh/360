@extends('layouts.admin.app')

@section('content')
  <div class="app-body">

    <!-- Row start -->
    <div class="row gx-3">
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card mb-3">
          <div class="card-body">
            <div class="mb-2">
              <i class="bi bi-bar-chart fs-1 text-primary lh-1"></i>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="m-0 text-secondary fw-normal">Sales</h5>
              <h3 class="m-0 text-primary">3500</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card mb-3">
          <div class="card-body">
            <div class="mb-2">
              <i class="bi bi-bag-check fs-1 text-primary lh-1"></i>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="m-0 text-secondary fw-normal">Orders</h5>
              <h3 class="m-0 text-primary">2900</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card mb-3">
          <div class="card-body">
            <div class="arrow-label">+18%</div>
            <div class="mb-2">
              <i class="bi bi-box-seam fs-1 text-primary lh-1"></i>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="m-0 text-secondary fw-normal">Items</h5>
              <h3 class="m-0 text-primary">6500</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
        <div class="card mb-3">
          <div class="card-body">
            <div class="arrow-label">+24%</div>
            <div class="mb-2">
              <i class="bi bi-bell fs-1 text-primary lh-1"></i>
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <h5 class="m-0 text-secondary fw-normal">Signups</h5>
              <h3 class="m-0 text-primary">7200</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Row end -->

    <!-- Row start -->
    <div class="row gx-3">
      <div class="col-xxl-12">
        <div class="card mb-3">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">Overview</h5>
            <button class="btn btn-outline-primary btn-sm ms-auto">
              Download
            </button>
          </div>
          <div class="card-body">
            <!-- Row start -->
            <div class="row gx-3">
              <div class="col-lg-5 col-sm-12 col-12">
                <h6 class="text-center mb-3">Visitors</h6>
                <div id="visitors"></div>
                <div class="my-3 text-center">
                  <div class="badge bg-danger bg-opacity-10 text-danger">
                    10% higher than last month
                  </div>
                </div>
              </div>
              <div class="col-lg-2 col-sm-12 col-12">
                <div class="border px-2 py-4 rounded-5 h-100 text-center">
                  <h6 class="mt-3 mb-5">Monthly Average</h6>
                  <div class="mb-5">
                    <h2 class="text-primary">9600</h2>
                    <h6 class="text-secondary fw-light">Visitors</h6>
                  </div>
                  <div class="mb-4">
                    <h2 class="text-danger">$450<sup>k</sup></h2>
                    <h6 class="text-secondary fw-light">Sales</h6>
                  </div>
                </div>
              </div>
              <div class="col-lg-5 col-sm-12 col-12">
                <h6 class="text-center mb-3">Sales</h6>
                <div id="sales"></div>
                <div class="my-3 text-center">
                  <div class="badge bg-primary bg-opacity-10 text-primary">
                    12% higher than last month
                  </div>
                </div>
              </div>
            </div>
            <!-- Row ends -->
          </div>
        </div>
      </div>
    </div>
    <!-- Row ends -->
  </div>

  <script src="{{ asset('assets/admin/vendor/apex/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/admin/vendor/apex/custom/dash1/visitors.js') }}"></script>
  <script src="{{ asset('assets/admin/vendor/apex/custom/dash1/sales.js') }}"></script>
  <script src="{{ asset('assets/admin/vendor/apex/custom/dash1/sparkline.js') }}"></script>
  <script src="{{ asset('assets/admin/vendor/apex/custom/dash1/tasks.js') }}"></script>
  <script src="{{ asset('assets/admin/vendor/apex/custom/dash1/income.js') }}"></script>
@endsection
