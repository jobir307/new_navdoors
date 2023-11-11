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
      <h4 class="fw-bold py-3 mb-4"><a href="{{ route('cashier', $date=date('Y-m-d')) }}" class="fw-light">Asosiy / </a><span class="text-muted fw-light">Xodimlar</span></h4>
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <h5 class="card-header">Xodimlar ro'yxati</h5>
                  <div class="card-body">
                      <div class="table-responsive text-nowrap">
                          <table class="table table-bordered table-hover w-100" id="workers_table">
                              <thead>
                                <tr>
                                    <th class="text-center align-middle" style="width:20px;">T/r</th>
                                    <th class="text-center align-middle">FIO</th>
                                    <th class="text-center align-middle">Jamg'arma, so'm</th>
                                    <th class="text-center align-middle">Telefon raqami</th>
                                    <th class="text-center align-middle">Lavozimi</th>`
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="FIO"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Jamg'arma"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                                    <td></td>
                                </tr>
                              </thead>
                              <tbody>
                                  @foreach($workers as $key => $value)
                                      <tr>
                                          <td class="text-center">{{ $key + 1 }}</td>
                                          <td><a href="{{ route('cashier-worker-salaries', $value->id) }}">{{ $value->fullname }}</a></td>
                                          @if (!empty($value->total_salary))
                                            <td><span class="badge bg-success">{{ number_format($value->total_salary, 2, ",", " ") }}</span></td>
                                          @else
                                            <td>{{ number_format($value->total_salary, 2, ",", " ") }}</td>
                                          @endif
                                          <td>{{ $value->phone_number }}</td>
                                          <td>{{ $value->jobs }}</td>
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

  <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
  <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
  
  <script src="../assets/js/main.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      let table = $('#workers_table').DataTable({
        dom: 'Qrltp',
        lengthMenu: [
            [25, 50, 100],
            [25, 50, 100]
        ],
        "ordering": false
      });

      table.columns().every( function () {
        var column = this;
     
        $( 'input', this.header() ).on( 'keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });
    });
  </script>
@endsection
