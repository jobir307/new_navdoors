@extends('layouts.moderator')
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
  <div class="container-fluid flex-grow-1 container-p-y" style="background-color:#cc99ff;">
    <h4 class="text-primary">{{ $worker->fullname }}ning naryad bo'yicha maoshlari</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="nav-align-top mb-4"  id="tabs">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <button
                  type="button"
                  class="nav-link"
                  role="tab"
                  data-bs-toggle="tab"
                  data-bs-target="#navs-inprocess-orders"
                  aria-controls="navs-inprocess-orders"
                  aria-selected="true"
                >
                  Jarayondagi
                </button>
              </li>
              <li class="nav-item">
                <button
                  type="button"
                  class="nav-link"
                  role="tab"
                  data-bs-toggle="tab"
                  data-bs-target="#navs-completed-orders"
                  aria-controls="navs-completed-orders"
                  aria-selected="false"
                >
                  Yakunlangan
                </button>
              </li>
              <li class="nav-item">
                <button
                  type="button"
                  class="nav-link"
                  role="tab"
                  data-bs-toggle="tab"
                  data-bs-target="#navs-send-topayment-orders"
                  aria-controls="navs-send-topayment-orders"
                  aria-selected="false"
                >
                  To'lovga yuborilgan
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
              <div class="tab-pane fade" id="navs-inprocess-orders" role="tabpanel">
                <h5 class="text-primary">Jarayondagi naryadlar ro'yxati</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-bordered table-hover w-100" id="inprocess_orders_table">
                    <thead>
                      <tr>
                        <th class="text-center" style="width:30px !important;">T/r</th>
                        <th class="text-center">Shartnoma raqami</th>
                        <th class="text-center">Lavozim</th>
                        <th class="text-center">Mahsulot</th>
                        <th class="text-center">Soni</th>
                        <th class="text-center">Maosh</th>
                        <th>Holati</th>
                      </tr>
                      <tr>
                        <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                        <td><input class="form-control form-control-sm" type="text" placeholder="Lavozim"></td>
                        <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                        <td><input class="form-control form-control-sm" type="text" placeholder="Soni"></td>
                        <td><input class="form-control form-control-sm" type="text" placeholder="Maosh"></td>
                        <td><input class="form-control form-control-sm" type="text" placeholder="Holati"></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $total_salary = 0; ?>
                      @foreach($in_process_orders_salaries as $key => $value)
                        <?php $total_salary += $value->salary; ?>
                        <tr>
                          <td>{{ $key + 1 }}</td>
                          <td>{{ $value->order_id }}/{{ $value->contract_number }}</td>
                          <td>{{ $value->job }}</td>
                          <td>{{ $value->order_process_product }}</td>
                          <td>{{ $value->product_count }}</td>
                          <td>{{ number_format($value->salary, 2, ",", " ") }} so'm</td>
                          <td>{{ $value->job_name ?? "" }}</td>
                        </tr>
                      @endforeach
                      <tr>
                        <td>Jami: </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($total_salary, 2, ",", " ") }} so'm</td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="navs-completed-orders" role="tabpanel">
                <h5 class="text-primary">Yakunlangan naryadlar ro'yxati</h5>
                <div class="table-responsive text-nowrap">
                  <form action="{{ route('pay-worker-salary') }}" method="POST">
                    @csrf
                    <table class="table table-bordered table-hover w-100" id="completed_orders_table" style="table-layout:fixed;">
                      <thead>
                          <tr>
                              <th class="text-center" style="min-width:30px !important;">T/r</th>
                              <th class="text-center">Shartnoma raqami</th>
                              <th class="text-center">Lavozim</th>
                              <th class="text-center">Mahsulot</th>
                              <th class="text-center">Soni</th>
                              <th class="text-center">Maosh</th>
                              <th class="text-center">Vaqti</th>
                              <td style="border-collapse:collapse; width:50px;"></td>
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
                                <button type="submit" class="btn btn-sm btn-outline-primary text-center">To'lash</button>
                              </td>
                          </tr>
                      </thead>
                      <tbody>
                      <?php $total_salary = 0; ?>
                      @foreach($completed_orders_salaries as $key => $value)
                      <?php $total_salary += $value->salary; ?>
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $value->order_id }}/{{ $value->contract_number }}</td>
                            <td>{{ $value->job }}</td>
                            <td>{{ $value->order_process_product }}</td>
                            <td>{{ $value->product_count }}</td>
                            <td>{{ number_format($value->salary, 2, ",", " ") }} so'm</td>
                            <td>{{ date('d.m.Y H:i', strtotime($value->completed_time)) }}</td>
                            <td>
                              <div class="form-check">
                                <input type="checkbox" class="form-check-input salary_checkbox" data-salary="{{ $value->salary }}" name="orderprocess_id[]" value="{{ $value->id }}">
                                <input type="hidden" name="worker_id" value="{{ $worker->id }}">
                              </div>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                          <td>Jami: </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>{{ number_format($total_salary, 2, ",", " ") }} so'm</td>
                          <td></td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table>
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="navs-send-topayment-orders" role="tabpanel">
                <h5 class="text-primary">To'lovga yuborilgan naryadlar ro'yxati</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-bordered table-hover w-100" id="sendto_payment_orders_table" style="table-layout:auto;">
                    <thead>
                        <tr>
                            <th class="text-center" style="min-width:30px !important;">T/r</th>
                            <th class="text-center">Shartnoma raqami</th>
                            <th class="text-center">Lavozim</th>
                            <th class="text-center">Mahsulot</th>
                            <th class="text-center">Soni</th>
                            <th class="text-center">Maosh</th>
                            <th class="text-center">Vaqti</th>
                        </tr>
                        <tr>
                            <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Lavozim"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Soni"></td>
                            <td><input class="form-control form-control-sm" type="text" placeholder="Maosh"></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                      <?php $total_salary = 0; ?>
                      @foreach($send_payment_orders_salaries as $key => $value)
                      <?php $total_salary += $value->salary; ?>
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $value->order_id }}/{{ $value->contract_number }}</td>
                            <td>{{ $value->job }}</td>
                            <td>{{ $value->order_process_product }}</td>
                            <td>{{ $value->product_count }}</td>
                            <td>{{ number_format($value->salary, 2, ",", " ") }} so'm</td>
                            <td>{{ date('d.m.Y H:i', strtotime($value->paid_time)) }}</td>
                        </tr>
                      @endforeach
                      <tr>
                        <td>Jami: </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($total_salary, 2, ",", " ") }} so'm</td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="tab-pane fade" id="navs-paid-orders" role="tabpanel">
                <h5 class="text-primary">To'langan naryadlar ro'yxati</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-bordered table-hover w-100" id="paid_orders_table">
                    <thead>
                      <tr>
                        <th class="text-center" style="width: 30px !important; max-width:30px !important;">T/r</th>
                        <th class="text-center">Maosh</th>
                        <th class="text-center">To'langan vaqti</th>
                        <th style="width:100px !mportant;"></th>
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
                    @foreach($paid_orders_salaries as $key => $value)
                      <?php $total_salary += $value->salary; ?>
                      <tr>
                        <td class="text-center">{{ $value->id }}</td>
                        <td>{{ number_format($value->salary, 2, ",", " ") }} so'm</td>
                        <td>{{ date("d.m.Y H:i", strtotime($value->cashier_paid_time)) }}</td>  
                        <td>
                          <a href="{{ route('show-stock-details', $value->id) }}" class="text-right btn btn-sm btn-primary">Ko'rish</a>
                        </td>
                      </tr>
                    @endforeach
                      <tr>
                        <td>Jami: </td>
                        <td>{{ number_format($total_salary, 2, ",", " ") }} so'm</td>
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
@endsection

@section('scripts')
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      let inprocess_orders_table = $('#inprocess_orders_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100],
            [25, 50, 100]
        ],
        "ordering": false
      });
      inprocess_orders_table.columns().every( function () {
        let column = this;
        $('input', this.header()).on('keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });
      let completed_orders_table = $('#completed_orders_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100],
            [25, 50, 100]
        ],
        "ordering": false
      });
      completed_orders_table.columns().every( function () {
        let column = this;
        $('input', this.header()).on('keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });

      let sendto_payment_orders_table = $('#sendto_payment_orders_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100],
            [25, 50, 100]
        ],
        "ordering": false
      });
      sendto_payment_orders_table.columns().every( function () {
        let column = this;
        $('input', this.header()).on('keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });

      let paid_orders_table = $('#paid_orders_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100],
            [25, 50, 100]
        ],
        "ordering": false
      });
      paid_orders_table.columns().every(function(){
        let column = this;
        $('input', this.header()).on('keyup change', function () {
            column
                .search(this.value)
                .draw();
        });
      });

      $('body').on('change', '.salary_checkbox', function(){
        let sum = 0;
        sum += $(this).data('salary');
      });      
    });

    $(function(){
      $('button[data-bs-toggle="tab"]').on('click', function(){
        localStorage.setItem('salaryActiveTab', $(this).attr('data-bs-target'));
      });
      
      let activeTab = localStorage.getItem('salaryActiveTab');
      
      if(activeTab){
        $(".tab-pane .fade").removeClass("show active");
        $("div.tab-pane"+activeTab).addClass("show active");
        $('.nav-item button').removeClass('active');
        $('.nav-item button[data-bs-target="' + activeTab + '"]').addClass('active');
        $('.nav-item button[data-bs-target="' + activeTab + '"]').attr("aria-selected", "true");
      } else {
        $("div#navs-inprocess-orders").addClass("show active");
        $('.nav-item button[data-bs-target="#navs-inprocess-orders"]').addClass('active');
        $('.nav-item button[data-bs-target="#navs-inprocess-orders"]').attr("aria-selected", "true");
      }
    });
  </script>
@endsection