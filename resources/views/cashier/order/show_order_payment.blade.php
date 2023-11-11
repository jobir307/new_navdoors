@extends('layouts.cashier')
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('cashier', $date=date('Y-m-d')) }}" class="fw-light">Asosiy / </a><a href="{{ route('cashier-order') }}" class="fw-light">Shartnomalar / </a><span class="text-muted fw-light">To'lov tarixi</span></h4>
    @if (isset($orders) && !empty($orders))
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <h5 class="card-header">Shartnoma raqami №{{ $orders[0]->contract_number }} bo'yicha to'lovlar tarixi</h5>
            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th class="text-center align-middle" style="width: 20px;" rowspan="3">T/r</th>
                      <th class="text-center align-middle" rowspan="3">Xaridor</th>
                      <th class="text-center align-middle" colspan="5">Summa</th>
                      <th class="text-center align-middle" rowspan="3">Sana</th>
                    </tr>
                    <tr>
                      <th class="text-center align-middle" rowspan="2">Shartnoma</th>
                      <th class="text-center align-middle" colspan="3">To'landi</th>
                      <th class="text-center align-middle" rowspan="2">Qoldi</th>
                    </tr>
                    <tr>
                      <th class="text-center">Raqamlarda</th>
                      <th class="text-center">So'z bilan</th>
                      <th class="text-center align-middle" rowspan="3">To'lov turi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($orders as $key => $value)
                      <tr>
                        @if (Auth::user()->role_id == 1)
                          <td onclick="setNewPrice({{ $value->id }})" class="text-center">{{ $key + 1 }}</td>
                        @else
                          <td class="text-center">{{ $key + 1 }}</td>
                        @endif
                        <td>{{ $value->customer }}</td>
                        <td>{{ number_format($value->contract_price, 2, ",", " ") }} сум</td>
                        <td>{{ is_null($value->payed) ? 0 : number_format($value->payed, 2, ",", " ") }} сум</td>
                        <td>{{ $value->in_words }}</td>
                        <td class="text-center">{{ $value->payment_type }}</td>
                        <td>{{ is_null($value->debt) ? 0 : number_format($value->debt, 2, ",", " ") }} сум</td>
                        <td>{{ date('d.m.Y H:i', strtotime($value->created_at)) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
    <div class="modal fade" id="set-new-price-modal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
          <form action="{{ route('set-admin-new-price-in-cashier') }}" method="POST">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title text-primary" id="modalCenterTitle">To'langan summa narxini o'zgartirish</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <label for="new_price" class="form-label">Yangi summa narxi</label>
                  <input id="new_price" class="form-control" type="number" name="new_price" autocomplete="off">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="stock_id" class="stock_id" value="">
              <input type="hidden" name="invoice_id" value="{{ $orders[0]->invoice_id ?? 0 }}">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Yopish
              </button>
              <button type="submit" class="btn btn-primary">Saqlash</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>

  <script>
    function setNewPrice(stock_id) {
      $(".stock_id").val(stock_id);
      $("#set-new-price-modal").modal("show");
    }
  </script>
@endsection
