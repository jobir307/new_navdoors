@extends('layouts.cashier')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-3">
      <div class="col-md-4">
        <span class="text-success align-middle h5 border border-primary border-2 rounded p-2">Kunning boshi: {{ number_format($data[0]->saldo-$data[0]->income+$data[0]->expense, 2, ",", " ") }} so'm</span>
      </div>
      <div class="col-md-4">
        <input type="date" class="form-control" name="date" onchange="document.location.href = '/cashier/' + this.value" value="{{ $date }}">
      </div>
      <div class="col-md-4">
        <span class="text-success align-middle h5 border border-primary border-2 rounded p-2">Kunning oxiri: {{ number_format($data[0]->saldo, 2, ",", " ") }} so'm</span>
      </div>
    </div>
    <div class="row">
      <!-- Kirim -->
      <div class="col-md-6 col-lg-4 order-2 mb-4">
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Kirim</h5>
          </div>
          <div class="card-body">
            <ul class="p-0 m-0">
              @foreach($incomes as $key => $value)
              <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                  <img src="{{asset('assets/img/icons/unicons/cc-success.png')}}" alt="User" class="rounded" />
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <h6 class="mb-0">{{ $value->inout_type }}</h6>
                  </div>
                  <div class="user-progress d-flex align-items-center gap-1">
                    <h6 class="mb-0">{{ number_format($value->income, 2, ",", " ") }}</h6>
                    <span class="text-muted">So'm</span>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
      <!--/ Kirim -->
      <!-- Diagramma -->
      <div class="col-md-6 col-lg-4 order-2 mb-4">
        <div class="card h-100">
          <div class="card-header">
            <ul class="nav nav-pills" role="tablist">
              <li class="nav-item">
                <button
                  type="button"
                  class="nav-link active"
                  role="tab"
                  data-bs-toggle="tab"
                  data-bs-target="#navs-tabs-line-card-income"
                  aria-controls="navs-tabs-line-card-income"
                  aria-selected="true"
                >
                  Income
                </button>
              </li>
              <li class="nav-item">
                <button type="button" class="nav-link" role="tab">Expenses</button>
              </li>
              <li class="nav-item">
                <button type="button" class="nav-link" role="tab">Profit</button>
              </li>
            </ul>
          </div>
          <div class="card-body px-0">
            <div class="tab-content p-0">
              <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                <div class="d-flex p-4 pt-3">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../assets/img/icons/unicons/wallet.png" alt="User" />
                  </div>
                  <div>
                    <small class="text-muted d-block">Total Balance</small>
                    <div class="d-flex align-items-center">
                      <h6 class="mb-0 me-1">$459.10</h6>
                      <small class="text-success fw-semibold">
                        <i class="bx bx-chevron-up"></i>
                        42.9%
                      </small>
                    </div>
                  </div>
                </div>
                <div id="incomeChart"></div>
                <div class="d-flex justify-content-center pt-4 gap-2">
                  <div class="flex-shrink-0">
                    <div id="expensesOfWeek"></div>
                  </div>
                  <div>
                    <p class="mb-n1 mt-1">Expenses This Week</p>
                    <small class="text-muted">$39 less than last week</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/ Diagramma -->

      <!-- Chiqim -->
      <div class="col-md-6 col-lg-4 order-3 mb-4">
        <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">Chiqim</h5>
          </div>
          <div class="card-body">
            <ul class="p-0 m-0">
              @foreach($expenses as $key => $value)
                <li class="d-flex mb-4 pb-1">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="{{asset('assets/img/icons/unicons/cc-warning.png')}}" alt="User" class="rounded" />
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">{{ $value->inout_type }}</h6>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <h6 class="mb-0">{{ number_format($value->expense, 2, ",", " ") }}</h6>
                      <span class="text-muted">So'm</span>
                    </div>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
      <!--/ Chiqim -->
    </div>
    <div class="row">
      <div class="col-md-6 col-lg-4 order-2 mb-4">
        <span class="text-primary align-middle h5 border border-success border-2 rounded p-2">Umumiy kirim: {{ number_format($data[0]->income, 2, ",", " ") }} so'm</span>
      </div>
      <div class="col-md-6 col-lg-4 order-2 mb-4"></div>
      <div class="col-md-6 col-lg-4 order-2 mb-4">
        <span class="text-primary align-middle h5 border border-success border-2 rounded p-2">Umumiy chiqim: {{ number_format($data[0]->expense, 2, ",", " ") }} so'm</span>
      </div>
    </div>
  </div>
@endsection


@section('scripts')
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/menu.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/js/main.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/js/dashboards-analytics.js')}}" type="text/javascript"></script>
@endsection