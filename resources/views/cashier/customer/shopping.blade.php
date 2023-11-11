@extends('layouts.cashier')
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
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('cashier', $date=date('Y-m-d')) }}" class="fw-light">Asosiy / </a><a href="{{ route('cashier-customer') }}" class="fw-light">Buyurtmachilar / </a><span class="fw-light">Buyurtmachi xaridlari</span></h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if (!empty($customer_orders))
                        <h5 class="text-primary">Buyurtmachi <span class="text-success">{{ $customer_orders[0]->customer }}</span> xaridlari ro'yxati</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered table-hover w-100" id="shopping_table">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" rowspan="2" style="width:20px;">T/r</th>
                                        <th class="text-center align-middle" rowspan="2">Shartnoma raqami</th>
                                        <th class="text-center align-middle" rowspan="2">Mahsulot</th>
                                        <th class="text-center align-middle" colspan="4">Summa</th>
                                        <th class="text-center align-middle" rowspan="2">Holati</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Umumiy narxi</th>
                                        <th class="text-center">Shartnoma narxi</th>
                                        <th class="text-center">To'landi</th>
                                        <th class="text-center">Qoldi</th>
                                    </tr>
                                    <tr>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Umumiy narxi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma narxi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="To'landi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Qoldi"></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer_orders as $key => $value)
                                    <tr class="text-nowrap">
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $value->id }}/{{ $value->contract_number }}</td>
                                        <td>{{ $value->product }}</td>
                                        <td>{{ number_format($value->contract_price, 2, ",", " ") }}</td>
                                        <td>{{ number_format($value->last_contract_price, 2, ",", " ") }}</td>
                                        <td>{{ number_format($value->paid, 2, ",", " ") }}</td>
                                        <td>{{ number_format($value->last_contract_price-$value->paid, 2, ",", " ") }}</td>
                                        <td>{{ $value->job_name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <h5 class="text-primary">Ushbu buyurtmachi tomonidan xarid amalga oshirilmagan.</h5>
                    @endif
                </div>
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
  <script type="text/javascript">
    $(document).ready(function () {
      let shopping_table = $('#shopping_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100,-1],
            [25, 50, 100,"Hammasi"]
        ],
        "ordering": false
      });
      shopping_table.columns().every( function () {
        let column = this;
        $('input', this.header()).on('keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });
    });
  </script>
@endsection