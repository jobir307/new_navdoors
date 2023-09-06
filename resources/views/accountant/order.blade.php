@extends('layouts.accountant')
<style type="text/css">
  thead input {
      width: 100%;
      padding: 10px 3px;
      box-sizing: border-box;
      border-radius: 8px;
      border: 1px solid #F2F2F2;
    }
    thead input:focus {
        border-color: #696CFF;
        outline: none;
        font-weight: 500;
    }
    </style>
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                      <table class="table table-bordered table-hover w-100" id="orders_table">
                        <thead>
                          <tr>
                            <th class="text-center align-middle" rowspan="2" style="width:20px;">T/r</th>
                            <th class="text-center align-middle" rowspan="2">Buyurtmachi</th>
                            <th class="text-center align-middle" rowspan="2">INN</th>
                            <th class="text-center align-middle" rowspan="2" style="max-width: 120px; width: 120px;">Telefon raqami</th>
                            <th class="text-center align-middle" rowspan="2">Shartnoma raqami</th>
                            <th class="text-center align-middle" rowspan="2">Mahsulot</th>
                            <th class="text-center align-middle" colspan="3">Summa</th>
                            <th rowspan="2" style="width: 120px"></th>
                          </tr>
                          <tr>
                            <th class="text-center">Shartnoma narxi</th>
                            <th class="text-center">To'landi</th>
                            <th class="text-center">Qoldi</th>
                          </tr>
                          <tr>
                            <td></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="INN"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma narxi"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="To'landi"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Qoldi"></td>
                            <td></td>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($orders as $key => $value)
                            <tr>
                              <td class="text-center">{{ $key + 1 }}</td>
                              <td>{{ $value->customer }}</td>
                              <td>{{ $value->inn }}</td>
                              <td>{{ $value->phone_number }}</td>
                              <td>{{ $value->contract_number }}</td>
                              <td>{{ $value->product }}</td>
                              <td>{{ number_format($value->last_contract_price, 2, ",", " ") }}</td>
                              <td>{{ number_format($value->paid, 2, ",", " ") }}</td>
                              <td>{{ number_format($value->last_contract_price-$value->paid, 2, ",", " ") }}</td>
                              <td class="text-sm-end">
                                <button type="button" data-invoice_id="{{ $value->invoice_id }}" class="btn-sm btn btn-outline-primary cashin_button" title="Kirim qilish">
                                  Kirim
                                </button>
                                <a href="{{ route('accountant-show-order-payments', $value->invoice_id) }}" class="btn-sm btn btn-icon btn-outline-success" title="To'lovlar tarixi">
                                  <i class="bx bx-show"></i>
                                </a>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>           
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cashin-order-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <form action="{{ route('accountant-order-cashin') }}" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title text-primary" id="modalCenterTitle">Shartnomaga kirim qilish</h5>
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
                    <label class="form-label">Kirim summasi</label>
                    <input type="number" name="amount" autocomplete="off" class="form-control">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="invoice_id" class="invoice_id" value="">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Yopish
            </button>
            <button type="submit" class="btn btn-primary">Tasdiqlash</button>
        </div>
        </form>
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

  <script type="text/javascript">
    $(document).ready(function () {
      let table_confirmed = $('#orders_table').DataTable({
        dom: 'Qlrtp',
        "ordering": false
      });

      table_confirmed.columns().every( function () {
        let column = this;
        $( 'input', this.header() ).on( 'keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });

      $('body').on('click', '.cashin_button', function() {
        let invoice_id = $(this).data('invoice_id');
        $(".invoice_id").val(invoice_id);
        $("#cashin-order-modal").modal("show");
      })
    });
  </script>
  
@endsection