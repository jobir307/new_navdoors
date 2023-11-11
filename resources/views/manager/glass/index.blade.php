@extends('layouts.manager')
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
                <h5 class="card-header">Shishalar ro'yxati</h5>
                <div class="table-responsive text-nowrap">
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="glass_table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:20px;">T/r</th>
                                    <th class="text-center" style="width:450px;">Shisha turi</th>
                                    <th class="text-center">Shisha shakli</th>
                                    <th class="text-center" style="width: 160px;">Shisha narxi</th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Shisha turi"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Shisha shakli"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Shisha narxi"></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($glasses as $key => $value)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>{{ $value->glasstype }}</td>
                                    <td>{{ $value->glassfigure }}</td>
                                    <td>{{ number_format($value->price, 2, ",", " ")}} so'm</td>
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
  <script src="{{asset('assets/js/select2.min.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/menu.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/js/main.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      let table = $('#glass_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100],
            [25, 50, 100]
        ],
        "ordering": false
      });

      table.columns().every(function(){
        var column = this;
        $( 'input', this.header()).on('keyup change', function(){
            column
                .search(this.value)
                .draw();
        });
      });
    });
  </script>
@endsection