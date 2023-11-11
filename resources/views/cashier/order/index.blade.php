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
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('cashier', $date=date('Y-m-d')) }}" class="fw-light">Asosiy / </a><span class="text-muted fw-light">Shartnomalar</span></h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">Shartnomalar ro'yxati</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="nav-align-top">
                                <ul class="nav nav-pills mb-2" role="tablist">
                                    <li class="nav-item">
                                        <button
                                            type="button"
                                            class="nav-link"
                                            role="tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-top-new"
                                            aria-controls="navs-pills-top-new"
                                            aria-selected="true"
                                        >
                                        Yangi
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button
                                            type="button"
                                            class="nav-link"
                                            role="tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-top-prepaid"
                                            aria-controls="navs-pills-top-prepaid"
                                            aria-selected="false"
                                        >
                                        Avans
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button
                                            type="button"
                                            class="nav-link"
                                            role="tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-top-full-paid"
                                            aria-controls="navs-pills-top-full-paid"
                                            aria-selected="false"
                                        >
                                        To'langan
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button
                                            type="button"
                                            class="nav-link"
                                            role="tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#navs-pills-top-all-order"
                                            aria-controls="navs-pills-top-all-order"
                                            aria-selected="false"
                                        >
                                        Hammasi
                                        </button>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade" id="navs-pills-top-new" role="tabpanel">
                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-bordered table-hover w-100" id="new_orders_table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center align-middle" style="width:30px;" rowspan="2">T/r</th>
                                                        <th class="text-center align-middle" style="width:60px;" rowspan="2">Shartnoma raqami</th>
                                                        <th class="text-center align-middle" rowspan="2">Xaridor</th>
                                                        <th class="text-center align-middle" colspan="4">Summa</th>
                                                        <th class="text-center align-middle" rowspan="2">Qachon yaratilgan</th>
                                                        <th class="text-center align-middle" style="width:80px;" rowspan="2"></th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Umumiy</th>
                                                        <th class="text-center">Shartnoma</th>
                                                        <th class="text-center">To'landi</th>
                                                        <th class="text-center">Qoldi</th>
                                                    </tr>
                                                    <tr>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="Xaridor"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($new_orders as $key => $value)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td>{{ $value->order_id }}/{{ $value->contract_number }}</td>
                                                        <td>{{ $value->customer }}</td>
                                                        <td>{{ number_format($value->contract_price, 2, ",", " ") }} so'm</td>
                                                        <td>{{ number_format($value->last_contract_price, 2, ",", " ") }} so'm</td>
                                                        <td>{{ is_null($value->payed) ? 0 : number_format($value->payed, 2, ",", " ") }} so'm</td>
                                                        <td>{{ is_null($value->debt) ? number_format($value->last_contract_price, 2, ",", " ") : number_format($value->debt, 2, ",", " ") }} so'm</td>
                                                        <td>{{ date('d.m.Y H:i', strtotime($value->manager_verified_time)) }}</td>
                                                        <td class="text-sm-end">
                                                            <button type="button" 
                                                                    data-bs-target="#createorderinsmodal" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-dismiss="modal"
                                                                    class="btn-sm btn btn-icon btn-outline-primary add_order_payment" 
                                                                    data-invoice_id="{{ $value->id }}" 
                                                                    title="Qo'shish">
                                                                <i class="bx bx-plus"></i>
                                                            </button>
                                                            <a href="{{ route('show_order_payments', $value->id) }}" class="btn-sm btn btn-icon btn-outline-success" title="Ko'rish">
                                                                <i class="bx bx-show"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-pills-top-prepaid" role="tabpanel">
                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-bordered table-hover w-100" id="prepaid_orders_table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center align-middle" style="width:30px;" rowspan="2">T/r</th>
                                                        <th class="text-center align-middle" style="width:60px" rowspan="2">Shartnoma raqami</th>
                                                        <th class="text-center align-middle" rowspan="2">Xaridor</th>
                                                        <th class="text-center align-middle" colspan="4">Summa</th>
                                                        <th class="text-center align-middle" rowspan="2">Qachon yaratilgan</th>
                                                        <th class="text-center align-middle" style="width:80px !important;" rowspan="2"></th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Umumiy</th>
                                                        <th class="text-center">Shartnoma</th>
                                                        <th class="text-center">To'landi</th>
                                                        <th class="text-center">Qoldi</th>
                                                    </tr>
                                                    <tr>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="Xaridor"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($prepaid_orders as $key => $value)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td>{{ $value->order_id }}/{{ $value->contract_number }}</td>
                                                        <td>{{ $value->customer }}</td>
                                                        <td>{{ number_format($value->contract_price, 2, ",", " ") }} so'm</td>
                                                        <td>{{ number_format($value->last_contract_price, 2, ",", " ") }} so'm</td>
                                                        <td>{{ is_null($value->payed) ? 0 : number_format($value->payed, 2, ",", " ") }} so'm</td>
                                                        <td>{{ is_null($value->debt) ? 0 : number_format($value->debt, 2, ",", " ") }} so'm</td>
                                                        <td>{{ date('d.m.Y H:i', strtotime($value->manager_verified_time)) }}</td>
                                                        <td class="text-sm-end">
                                                            <button type="button" 
                                                                    data-bs-target="#createorderinsmodal" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-dismiss="modal"
                                                                    class="btn-sm btn btn-icon btn-outline-primary add_order_payment" 
                                                                    data-invoice_id="{{ $value->id }}" 
                                                                    title="Qo'shish">
                                                                <i class="bx bx-plus"></i>
                                                            </button>
                                                            <a href="{{ route('show_order_payments', $value->id) }}" class="btn-sm btn btn-icon btn-outline-success" title="Ko'rish">
                                                                <i class="bx bx-show"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-pills-top-full-paid" role="tabpanel">
                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-bordered table-hover w-100" id="full_paid_orders_table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center align-middle" style="width: 30px;" rowspan="2">T/r</th>
                                                        <th class="text-center align-middle" style="width: 60px;" rowspan="2">Shartnoma raqami</th>
                                                        <th class="text-center align-middle" rowspan="2">Xaridor</th>
                                                        <th class="text-center align-middle" colspan="4">Summa</th>
                                                        <th class="text-center align-middle" rowspan="2">Qachon yaratilgan</th>
                                                        <th class="text-center align-middle" style="width: 90px;" rowspan="2"></th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Umumiy</th>
                                                        <th class="text-center">Shartnoma</th>
                                                        <th class="text-center">To'landi</th>
                                                        <th class="text-center">Qoldi</th>
                                                    </tr>
                                                    <tr>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="Xaridor"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($fullpaid_orders as $key => $value)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td>{{ $value->order_id }}/{{ $value->contract_number }}</td>
                                                        <td>{{ $value->customer }}</td>
                                                        <td>{{ number_format($value->contract_price, 2, ",", " ") }} so'm</td>
                                                        <td>{{ number_format($value->last_contract_price, 2, ",", " ") }} so'm</td>
                                                        <td>{{ is_null($value->payed) ? 0 : number_format($value->payed, 2, ",", " ") }} so'm</td>
                                                        <td>{{ is_null($value->debt) ? 0 : number_format($value->debt, 2, ",", " ") }} so'm</td>
                                                        <td>{{ date('d.m.Y H:i', strtotime($value->manager_verified_time)) }}</td>
                                                        <td class="text-sm-end">
                                                            <a href="{{ route('show_order_payments', $value->id) }}" class="btn-sm btn btn-outline-success" title="Ko'rish">
                                                                Ko'rish
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-pills-top-all-order" role="tabpanel">
                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-bordered table-hover w-100" id="all_orders_table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center align-middle" style="width: 30px;" rowspan="2">T/r</th>
                                                        <th class="text-center align-middle" style="width: 60px;" rowspan="2">Shartnoma raqami</th>
                                                        <th class="text-center align-middle" rowspan="2">Xaridor</th>
                                                        <th class="text-center align-middle" colspan="4">Summa</th>
                                                        <th class="text-center align-middle" rowspan="2">Qachon yaratilgan</th>
                                                        <th class="text-center align-middle" rowspan="2">Holati</th>
                                                        <th class="text-center align-middle" style="width: 90px;" rowspan="2"></th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center">Umumiy</th>
                                                        <th class="text-center">Shartnoma</th>
                                                        <th class="text-center">To'landi</th>
                                                        <th class="text-center">Qoldi</th>
                                                    </tr>
                                                    <tr>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                                                        <td><input class="form-control form-control-sm" type="text" placeholder="Xaridor"></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($all_orders as $key => $value)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td>{{ $value->order_id }}/{{ $value->contract_number }}</td>
                                                        <td>{{ $value->customer }}</td>
                                                        <td>{{ number_format($value->contract_price, 2, ",", " ") }} so'm</td>
                                                        <td>{{ number_format($value->last_contract_price, 2, ",", " ") }} so'm</td>
                                                        <td>{{ is_null($value->payed) ? 0 : number_format($value->payed, 2, ",", " ") }} so'm</td>
                                                        <td>{{ is_null($value->debt) ? number_format($value->last_contract_price, 2, ",", " ") : number_format($value->debt, 2, ",", " ") }} so'm</td>
                                                        <td>{{ date('d.m.Y H:i', strtotime($value->manager_verified_time)) }}</td>
                                                        <td>{{ $value->job_name ?? "" }}</td>
                                                        <td class="text-sm-end">
                                                            <a href="{{ route('show_order_payments', $value->id) }}" class="btn-sm btn btn-outline-success" title="Ko'rish">
                                                                Ko'rish
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

                    <div class="modal fade" id="createorderinsmodal" role="dialog" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form action="{{ route('order-ins') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"
                                        ></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="payment_type">To'lov turi</label>
                                                <select class="form-select" id="payment_type" name="payment_type">
                                                @foreach($payment_types as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="amount">Pul summasi</label>
                                                <input type="number" name="amount" id="amount" class="form-control">
                                                <input type="hidden" name="invoice_id" id="invoice_id" value="">
                                                <input type="hidden" name="inout_type" value="1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Saqlash</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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

  <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
  <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
  
  <script src="../assets/js/main.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
        let new_orders_table = $('#new_orders_table').DataTable({
            dom: 'Qrltp',
            lengthMenu: [
                [25, 50, 100,-1],
                [25, 50, 100, "Hammasi"]
            ],
            "ordering": false
        });

        new_orders_table.columns().every(function(){
            var column = this;
            $('input', this.header()).on('keyup change', function(){
                column
                    .search(this.value)
                    .draw();
            });
        });

        let prepaid_orders_table = $('#prepaid_orders_table').DataTable({
            dom: 'Qrltp',
            lengthMenu: [
                [25, 50, 100,-1],
                [25, 50, 100, "Hammasi"]
            ],
            "ordering":false,
        });

        prepaid_orders_table.columns().every(function(){
            var column = this;
            $('input', this.header()).on('keyup change', function(){
                column
                    .search(this.value)
                    .draw();
            });
        });

        let full_paid_orders_table = $('#full_paid_orders_table').DataTable({
            dom: 'Qrltp',
            lengthMenu: [
                [25, 50, 100,-1],
                [25, 50, 100, "Hammasi"]
            ],
            "ordering":false
        });

        full_paid_orders_table.columns().every(function(){
            var column = this;
            $('input', this.header()).on('keyup change', function(){
                column
                    .search(this.value)
                    .draw();
            });
        });

        let all_orders_table = $('#all_orders_table').DataTable({
            dom: 'Qrltp',
            lengthMenu: [
                [25, 50, 100,-1],
                [25, 50, 100, "Hammasi"]
            ],
            "ordering":false
        });

        all_orders_table.columns().every(function(){
            var column = this;
            $('input', this.header()).on('keyup change', function(){
                column
                    .search(this.value)
                    .draw();
            });
        });

        $('body').on('click', '.add_order_payment', function(){
            let invoice_id = $(this).data('invoice_id');
            $("#invoice_id").val(invoice_id);
        });
    });

    $(function(){
      $('button[data-bs-toggle="tab"]').on('click', function(){
        localStorage.setItem('cashierOrderActiveTab', $(this).attr('data-bs-target'));
      });
      
      let activeTab = localStorage.getItem('cashierOrderActiveTab');
      
      if(activeTab){
        $(".tab-pane .fade").removeClass("show active");
        $("div.tab-pane"+activeTab).addClass("show active");
        $('.nav-item button').removeClass('active');
        $('.nav-item button[data-bs-target="' + activeTab + '"]').addClass('active');
        $('.nav-item button[data-bs-target="' + activeTab + '"]').attr("aria-selected", "true");
      } else {
        $("div#navs-pills-top-new").addClass("show active");
        $('.nav-item button[data-bs-target="#navs-pills-top-new"]').addClass('active');
        $('.nav-item button[data-bs-target="#navs-pills-top-new"]').attr("aria-selected", "true");
      }
    });
  </script>
@endsection
