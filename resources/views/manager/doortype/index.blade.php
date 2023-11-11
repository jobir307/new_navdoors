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
                <h5 class="card-header">Eshik turlari ro'yxati</h5>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover w-100" id="doortype_table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:20px;" rowspan=2>T/r</th>
                                    <th class="text-center" rowspan=2>Nomi</th>
                                    <th class="text-center" colspan=2>Narxi</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Diler uchun</th>
                                    <th class="text-center">Xaridor uchun</th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Nomi"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Diler uchun"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Xaridor uchun"></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($doortypes as $key => $value)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ number_format($value->dealer_price, 2, ",", " ")}} so'm</td>
                                    <td>{{ number_format($value->retail_price, 2, ",", " ")}} so'm</td>
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
    $(document).ready(function(){
      let table = $('#doortype_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100],
            [25, 50, 100]
        ],
        "ordering": false,
        scrollX: true
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