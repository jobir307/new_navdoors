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
            <div class="card">
                <h5 class="card-header">Shisha shakllari</h5>
                <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered table-hover" id="glassfigure_table">
                    <thead>
                        <tr>
                        <th style="width: 20px;" class="text-center">T/r</th>
                        <th style="width: 50px;" class="text-center">Rasmi</th>
                        <th class="text-center">Nomi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($glassfigures as $key => $value)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                              <img src="{{ asset($value->path) }}" alt="shisha shakli" style="width: 50px; height: 100px;">
                            </td>
                            <td>{{ $value->name }}</td>
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
      let table_glassfigure = $('#glassfigure_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100],
            [25, 50, 100]
        ],
        "ordering": false
      });
      table_glassfigure.columns().every( function () {
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