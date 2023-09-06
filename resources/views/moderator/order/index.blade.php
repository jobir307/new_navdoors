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
                class="nav-link active"
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
            <div class="tab-pane fade show active" id="navs-new-orders" role="tabpanel">
              <h5 class="text-primary">Yangi naryadlar ro'yxati</h5>
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover" id="new_orders_table" style="width:100%">
                  <thead>
                    <tr>
                      <th class="text-center" style="width: 20px;">T/r</th>
                      <th class="text-center">Shartnoma raqami</th>
                      <th class="text-center">Eshik turi</th>
                      <th class="text-center">Buyurtmachi</th>
                      <th class="text-center">Telefon raqami</th>
                      <th class="text-center">Muddati</th>
                      <th style="min-width: 130px; width: 130px;"></th>
                    </tr>
                    <tr>
                      <td></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Eshik turi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Muddati"></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($new_orders as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->contract_number }}</td>
                        <td>{{ $value->doortype }}</td>
                        <td>{{ $value->customer }}</td>
                        <td>{{ $value->phone_number }}</td>
                        <td>{{ date("d.m.Y", strtotime($value->deadline)) }}</td>
                        <td class="text-sm-end">
                          <button type="button" 
                             class="btn-sm btn btn-primary start_outfit"
                             title="Ishlab chiqarishni boshlash" 
                             data-bs-toggle="modal"
                             data-bs-target="#start_outfit_modal"
                             data-order_id="{{ $value->id }}">
                            Boshlash
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane fade" id="navs-inprocess-orders" role="tabpanel">
              <h5 class="text-primary">Jarayondagi naryadlar ro'yxati</h5>
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover w-100" id="inprocess_orders_table">
                  <thead>
                    <tr>
                      <th class="text-center align-middle" style="width: 20px;">T/r</th>
                      <th class="text-center align-middle">Shartnoma raqami</th>
                      <th class="text-center">Eshik turi</th>
                      <th class="text-center align-middle">Buyurtmachi</th>
                      <th class="text-center align-middle" style="width: 160px;">Telefon raqami</th>
                      <th class="text-center align-middle">Muddati</th>
                      <th class="text-center align-middle">Holati</th>
                      <th rowspan="2" style="max-width: 50px !important; width: 50px !important;"></th>
                    </tr>
                    <tr>
                      <td></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Eshik turi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Muddati"></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($inprocess_orders as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->contract_number }}</td>
                        <td>{{ $value->doortype }}</td>
                        <td>{{ $value->customer }}</td>
                        <td>{{ $value->phone_number }}</td>
                        <td>{{ date("d.m.Y", strtotime($value->deadline)) }}</td>
                        <td>{{ $value->job_name }}</td>
                        <td class="text-sm-end">
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
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover w-100" id="completed_orders_table">
                  <thead>
                    <tr>
                      <th class="text-center align-middle" style="width: 20px;">T/r</th>
                      <th class="text-center align-middle">Shartnoma raqami</th>
                      <th class="text-center">Eshik turi</th>
                      <th class="text-center align-middle">Buyurtmachi</th>
                      <th class="text-center align-middle" style="width: 160px;">Telefon raqami</th>
                      <th class="text-center align-middle">Muddati</th>
                      <th class="text-center align-middle">Holati</th>
                      <th rowspan="2" style="max-width: 50px !important; width: 50px !important;"></th>
                    </tr>
                    <tr>
                      <td></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Eshik turi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Muddati"></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($completed_orders as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->contract_number }}</td>
                        <td>{{ $value->doortype }}</td>
                        <td>{{ $value->customer }}</td>
                        <td>{{ $value->phone_number }}</td>
                        <td>{{ date("d.m.Y", strtotime($value->deadline)) }}</td>
                        <td>{{ $value->job_name }}</td>
                        <td class="text-sm-end">
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
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="start_outfit_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-primary" id="modalCenterTitle">Ishlab chiqarish oynasi</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col mb-3">
                  <h5>Ishlab chiqarish jarayonini boshlashga ruhsat beraman.</h5>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <form action="{{ route('start-process') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id"  class="started_order_id" value="">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Yopish
                </button>
                <button type="submit" class="btn btn-primary">Tasdiqlash</button>
              </form>
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
      let table_new_orders = $('#new_orders_table').DataTable({
        dom: 'Qlrtp',
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

      let inprocess_orders_table = $('#inprocess_orders_table').DataTable({
        dom: 'Qlrtp',
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

      $('body').on('click', '.start_outfit', function(){
        let order_id = $(this).data('order_id');
        $(".started_order_id").val(order_id);
      });
    });
  </script>
@endsection


