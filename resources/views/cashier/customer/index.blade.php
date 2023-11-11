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
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('cashier', $date=date('Y-m-d')) }}" class="fw-light">Asosiy / </a><span class="fw-light">Buyurtmachilar</span></h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-primary">Buyurtmachilar</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover" id="workers_table">
                            <thead>
                            <tr>
                                <th class="text-center" style="width:20px;">T/r</th>
                                <th class="text-center">FIO(Nomi)</th>
                                <th class="text-center">Xaridor turi</th>
                                <th class="text-center">Telefon raqami</th>
                                <th class="text-center" style="width:100px;">Xaridlar soni</th>
                                <th class="text-center">Balans, so'm</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input class="form-control form-control-sm" type="text" placeholder="FIO(Nomi)"></td>
                                <td><input class="form-control form-control-sm" type="text" placeholder="Xaridor turi"></td>
                                <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                                <td><input class="form-control form-control-sm" type="text" placeholder="Xaridlar soni"></td>
                                <td><input class="form-control form-control-sm" type="text" placeholder="Balans"></td>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $key => $value)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td><a href="{{ route('customer-shoppings', $value->id) }}">{{ $value->name }}</a></td>
                                    <td>{{ $value->type }}</td>
                                    <td>{{ $value->phone_number }}</td>
                                    @if (!empty($value->shopping_count))
                                      <td class="text-center"><span class="badge bg-success">{{ $value->shopping_count }}</span></td>
                                    @else
                                      <td class="text-center">{{ $value->shopping_count }}</td>
                                    @endif
                                    @if (empty($value->payed) || $value->payed - $value->contract_price < 0)
                                      <td><span class="badge bg-warning">{{ number_format($value->payed - $value->contract_price, 2, ",", " ") }}</span></td>
                                    @else
                                      <td><span class="badge bg-success">{{ number_format($value->payed - $value->contract_price, 2, ",", " ") }}</span></td>
                                    @endif
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
@endsection

@section('scripts')
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      let table_workers = $('#workers_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, "Hammasi"]
        ],
        "ordering": false
      });
      table_workers.columns().every( function () {
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