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
  .offcanvas-end {
    width: 800px !important;
  }
</style>
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('cashier', $date=date('Y-m-d')) }}" class="fw-light">Asosiy / </a><span class="text-muted fw-light">Xodim mehnat haqi</span></h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">{{ $worker->fullname }}ning naryadlar bo'yicha mehnat haqi</h5>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="nav-align-top mb-4">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <button
                                        type="button"
                                        class="nav-link active"
                                        role="tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#navs-notpaid-orders"
                                        aria-controls="navs-notpaid-orders"
                                        aria-selected="true"
                                        >
                                        To'lanmagan
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button
                                        type="button"
                                        class="nav-link"
                                        role="tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#navs-paid-orders"
                                        aria-controls="navs-paid-orders"
                                        aria-selected="false"
                                        >
                                        To'langan
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="navs-notpaid-orders" role="tabpanel">
                                        <h5 class="text-primary">To'lanmagan naryadlar ro'yxati</h5>
                                        <div class="table-responsive text-nowrap">
                                            <form action="{{ route('cashier-pay-worker-salary') }}" method="POST">
                                                @csrf
                                                <table class="table table-bordered table-hover w-100" id="notpaid_orders_table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" style="width:20px !important;">T/r</th>
                                                            <th class="text-center">Shartnoma raqami</th>
                                                            <th class="text-center">Lavozim</th>
                                                            <th class="text-center">Mahsulot</th>
                                                            <th class="text-center">Soni</th>
                                                            <th class="text-center">Maosh</th>
                                                            <th class="text-center">Vaqti</th>
                                                            <th class="text-center">
                                                                <button type="submit" class="btn btn-primary btn-round btn-sm">To'lash</button>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                                                            <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                                                            <td><input class="form-control form-control-sm" type="text" placeholder="Lavozim"></td>
                                                            <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                                                            <td><input class="form-control form-control-sm" type="text" placeholder="Soni"></td>
                                                            <td><input class="form-control form-control-sm" type="text" placeholder="Maosh"></td>
                                                            <td></td>
                                                            <td>
                                                                <input type="checkbox" class="form-check-input check_all">
                                                            </td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $total_salary = 0; ?>
                                                        @foreach($notpaid_salaries as $key => $value)
                                                            <?php $total_salary += $value->salary; ?>
                                                            <tr class="home_tr">
                                                                <td class="text-center">{{ $key + 1 }}</td>
                                                                <td>{{ $value->order_id }}/{{ $value->contract_number }}</td>
                                                                <td>{{ $value->job }}</td>
                                                                <td>{{ $value->order_process_product }}</td>
                                                                <td>{{ $value->product_count }}</td>
                                                                <td>{{ number_format($value->salary, 2, ",", " ") }} so'm</td>
                                                                <td>{{ date('d.m.Y H:i', strtotime($value->paid_time)) }}</td>
                                                                <td>
                                                                    <input 
                                                                        type="checkbox" 
                                                                        class="form-check-input" 
                                                                        data-salary="{{ $value->salary }}" 
                                                                        name="orderprocess_id[]" 
                                                                        value="{{ $value->id }}"
                                                                    />
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td>Jami:</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td id="total_salary">{{ number_format($total_salary, 2, ",", " ") }}</td>
                                                            <td></td>
                                                            <td>
                                                                <input type="hidden" name="worker_id" value="{{ $worker->id }}">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-paid-orders" role="tabpanel">
                                        <h5 class="text-primary">To'langan naryadlar ro'yxati</h5>
                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-bordered table-hover w-100" id="paid_orders_table">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width:20px !important;">T/r</th>
                                                    <th class="text-center">Maosh</th>
                                                    <th class="text-center">To'langan Vaqti</th>
                                                    <th style="width:100px !important;"></th>
                                                </tr>
                                                <tr>
                                                    <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                                                    <td><input class="form-control form-control-sm" type="text" placeholder="Maosh"></td>
                                                    <td><input class="form-control form-control-sm" type="text" placeholder="To'langan vaqti"></td>
                                                    <td></td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $total_salary = 0; ?>
                                                    @foreach($paid_salaries as $key => $value)
                                                    <?php $total_salary += $value->salary; ?>
                                                        <tr>
                                                            <td>{{ $value->id }}</td>
                                                            <td>{{ number_format($value->salary, 2, ",", " ") }} so'm</td>
                                                            <td>{{ date("d.m.Y H:i", strtotime($value->cashier_paid_time)) }}</td>
                                                            <td>
                                                                <a href="{{ route('cashier-show-stock-details', $value->id) }}" class="text-right btn btn-sm btn-primary">Ko'rish</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td>Jami:</td>
                                                        <td id="total_salary">{{ number_format($total_salary, 2, ",", " ") }} so'm</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div>
@endsection


@section('scripts')
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>

  <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
  <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
  
  <script src="../assets/js/main.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      let notpaid_orders_table = $('#notpaid_orders_table').DataTable({
        dom: 'Qrltp',
        lengthMenu: [
            [-1, 10, 25, 50],
            ['Hammasi', 10, 25, 50]
        ],
        "ordering": false
      });

      notpaid_orders_table.columns().every(function(){
        var column = this;
     
        $('input:text', this.header()).on('keyup change', function () {
            column
                .search(this.value)
                .draw();
        });
      });

      let paid_orders_table = $('#paid_orders_table').DataTable({
        dom: 'Qrltp',
        lengthMenu: [
            [-1, 10, 25, 50],
            ['Hammasi', 10, 25, 50]
        ],
        "ordering": false
      });

      paid_orders_table.columns().every(function(){
        var column = this;
     
        $('input', this.header()).on('keyup change', function(){
            column
                .search( this.value )
                .draw();
        });
      });

      $('body').on('change', '.check_all', function(){
        if (this.checked) {
            $('#notpaid_orders_table .home_tr input:checkbox').prop('checked', true);
        } else {
            $('#notpaid_orders_table .home_tr input:checkbox').prop('checked', false);
        }
      });

      $('.home_tr input:checkbox').change(function(){
        let total = 0;
        $('.home_tr input[type="checkbox"]:checked').each(function(){
                total += parseFloat($(this).data('salary'));
        });
        $('#total_salary').text(total);
      });

    });
  </script>
@endsection