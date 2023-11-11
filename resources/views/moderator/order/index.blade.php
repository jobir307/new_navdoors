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
  <div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-md-12">
        <div class="nav-align-top mb-4">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <button
                type="button"
                class="nav-link"
                role="tab"
                data-bs-toggle="tab"
                data-bs-target="#navs-new-orders"
                aria-controls="navs-new-orders"
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
                data-bs-target="#navs-inprocess-orders"
                aria-controls="navs-inprocess-orders"
                aria-selected="false"
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
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade" id="navs-new-orders" role="tabpanel">
              <h5 class="text-primary">Yangi naryadlar ro'yxati</h5>
              <div class="table-responsive">
                <table class="table table-bordered table-hover display nowrap w-100" id="new_orders_table">
                  <thead>
                    <tr>
                      <th class="text-center align-middle" style="width: 20px;" rowspan=2>T/r</th>
                      <th class="text-center align-middle" rowspan=2>Shartnoma raqami</th>
                      <th class="text-center align-middle" rowspan=2>Mahsulot</th>
                      <th class="text-center align-middle" rowspan=2>Buyurtmachi</th>
                      <th class="text-center align-middle" rowspan=2>Telefon raqami</th>
                      <th class="text-center align-middle" colspan=3>Vaqt</th>
                      <th class="text-center align-middle" rowspan=2>Kim yaratdi</th>
                      <th style="width: 70px;" rowspan=2></th>
                    </tr>
                    <tr>
                      <th class="text-center align-middle">Buyurtma qilingan</th>
                      <th class="text-center align-middle">Tasdiqlangan</th>
                      <th class="text-center align-middle">Topshirish kerak</th>
                    </tr>
                    <tr>
                      <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtma qilingan"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Tasdiqlangan"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Topshirish"></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($new_orders as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->id }}/{{ $value->contract_number }}</td>
                        <td>{{ $value->product }}</td>
                        <td>{{ $value->customer }}</td>
                        <td>{{ $value->phone_number }}</td>
                        <td>{{ date("d.m.Y H:i", strtotime($value->when_created)) }}</td>
                        <td>{{ !is_null($value->verified_time) ? date("d.m.Y H:i", strtotime($value->verified_time)) : "" }}</td>
                        <td>{{ date("d.m.Y", strtotime($value->deadline)) }}</td>
                        <td>{{ $value->who_created }}</td>
                        <td class="text-sm-end">
                          <a href="{{ route('moderator-order-show', $value->id)}}" class="btn-sm btn btn-outline-success" title="Ko'rish">
                            Ko'rish
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane fade" id="navs-inprocess-orders" role="tabpanel">
              <h5 class="text-primary">Jarayondagi naryadlar ro'yxati</h5>
              <div class="table-responsive">
                <table class="table table-bordered table-hover display nowrap w-100" id="inprocess_orders_table">
                  <thead>
                    <tr>
                      <th class="text-center align-middle" style="width: 20px;" rowspan=2>T/r</th>
                      <th class="text-center align-middle" rowspan=2>Shartnoma raqami</th>
                      <th class="text-center align-middle" rowspan=2>Mahsulot</th>
                      <th class="text-center align-middle" rowspan=2>Buyurtmachi</th>
                      <th class="text-center align-middle" style="width: 160px;" rowspan=2>Telefon raqami</th>
                      <th class="text-center align-middle" colspan=3>Vaqti</th>
                      <th class="text-center align-middle" rowspan=2>Holati</th>
                      <th class="text-center align-middle" rowspan=2>Kim yaratdi</th>
                      <th rowspan="2" style="max-width: 50px !important; width: 50px !important;"></th>
                    </tr>
                    <tr>
                      <th class="text-center align-middle">Buyurtma qilingan</th>
                      <th class="text-center align-middle">Tasdiqlangan</th>
                      <th class="text-center align-middle">Topshirish kerak</th>
                    </tr>
                    <tr>
                      <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtma qilingan"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Tasdiqlangan"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Topshirish kerak"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Holati"></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($inprocess_orders as $key => $value)
                    <?php 
                      $bgcolor = "";
                      $color = "";
                      if ($value->day_diff > 3 && $value->day_diff <= 7) {
                        $bgcolor = "#ffbf80";
                        $color = "white";
                      }
                      else if ($value->day_diff <= 3){
                        $bgcolor = "#a6a6a6";
                        $color = "white";
                      }
                    ?>
                      <tr style="background-color: {{ $bgcolor }}; color: {{ $color }}">
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->id }}/{{ $value->contract_number }}</td>
                        <td>{{ $value->product }}</td>
                        <td>{{ $value->customer }}</td>
                        <td>{{ $value->phone_number }}</td>
                        <td>{{ date("d.m.Y H:i", strtotime($value->when_created)) }}</td>
                        <td>{{ !is_null($value->verified_time) ? date("d.m.Y H:i", strtotime($value->verified_time)) : "" }}</td>
                        <td>{{ date("d.m.Y", strtotime($value->deadline)) }}</td>
                        <td>{{ $value->job_name }}</td>
                        <td>{{ $value->who_created }}</td>
                        <td class="text-sm-end">
                          <a href="{{ route('moderator-order-show', $value->id)}}" class="btn-sm btn btn-icon btn-outline-success" title="Ko'rish">
                            <i class="bx bx-show"></i>
                          </a>
                          <a href="{{ route('form-outfit', $value->id) }}" class="btn-sm btn btn-secondary" title="Naryad shakllantirish">
                            Naryad
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane fade" id="navs-completed-orders" role="tabpanel">
              <h5 class="text-primary">Yakunlangan naryadlar ro'yxati</h5>
              <div class="table-responsive">
                <table class="table table-bordered table-hover display nowrap w-100" id="completed_orders_table">
                  <thead>
                    <tr>
                      <th class="text-center align-middle" style="width: 20px;" rowspan="2">T/r</th>
                      <th class="text-center align-middle" rowspan="2">Shartnoma raqami</th>
                      <th class="text-center align-middle" rowspan="2">Mahsulot</th>
                      <th class="text-center align-middle" rowspan="2">Buyurtmachi</th>
                      <th class="text-center align-middle" style="width: 160px;" rowspan="2">Telefon raqami</th>
                      <th class="text-center align-middle" colspan="3">Vaqti</th>
                      <th class="text-center align-middle" rowspan="2">Holati</th>
                      <th class="text-center align-middle" rowspan="2">Kim yaratdi</th>
                      <th rowspan="2" style="width: 100px !important;"></th>
                    </tr>
                    <tr>
                      <th class="text-center align-middle">Buyurtma qilingan</th>
                      <th class="text-center align-middle">Tasdiqlangan</th>
                      <th class="text-center align-middle">Topshirish kerak</th>
                    </tr>
                    <tr>
                      <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtma qilingan"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Tasdiqlangan"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Topshirish kerak"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($completed_orders as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->id }}/{{ $value->contract_number }}</td>
                        <td>{{ $value->product }}</td>
                        <td>{{ $value->customer }}</td>
                        <td>{{ $value->phone_number }}</td>
                        <td>{{ date("d.m.Y H:i", strtotime($value->when_created)) }}</td>
                        <td>{{ !is_null($value->verified_time) ? date("d.m.Y H:i", strtotime($value->verified_time)) : "" }}</td>
                        <td>{{ date("d.m.Y", strtotime($value->deadline)) }}</td>
                        <td>{{ $value->job_name }}({{ date("d.m.Y H:i",  strtotime($value->moderator_send_time)) }})</td>
                        <td>{{ $value->who_created }}</td>
                        <td class="text-sm-end">
                          <a href="{{ route('moderator-order-show', $value->id)}}" class="btn btn-sm btn-outline-success" title="Ko'rish">Ko'rish</a>
                          <a href="{{ route('form-outfit', $value->id) }}" class="btn-sm btn btn-secondary" title="Naryad shakllantirish">Naryad</a>
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
      let table_new_orders = new DataTable('#new_orders_table', {
        dom: 'Qlrtp',
        lengthMenu: [
          [25, 50, 100, -1],
          [25, 50, 100, "Hammasi"]
        ],
        "ordering": false
      });
      table_new_orders.columns().every( function () {
        let column = this;
        $('input', this.header()).on('keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });

      let inprocess_orders_table = new DataTable('#inprocess_orders_table', {
        dom: 'Qlrtp',
        lengthMenu: [
          [25, 50, 100, -1],
          [25, 50, 100, "Hammasi"]
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

      let completed_orders_table = new DataTable('#completed_orders_table', {
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, "Hammasi"]
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
    });

    $(function(){
      $('button[data-bs-toggle="tab"]').on('click', function(){
        localStorage.setItem('orderActiveTab', $(this).attr('data-bs-target'));
      });
      
      let activeTab = localStorage.getItem('orderActiveTab');
      
      if(activeTab){
        $(".tab-pane .fade").removeClass("show active");
        $("div.tab-pane"+activeTab).addClass("show active");
        $('.nav-item button').removeClass('active');
        $('.nav-item button[data-bs-target="' + activeTab + '"]').addClass('active');
        $('.nav-item button[data-bs-target="' + activeTab + '"]').attr("aria-selected", "true");
      } else {
        $("div#navs-new-orders").addClass("show active");
        $('.nav-item button[data-bs-target="#navs-new-orders"]').addClass('active');
        $('.nav-item button[data-bs-target="#navs-new-orders"]').attr("aria-selected", "true");
      }
    });
  </script>
@endsection