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
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-primary">Xodim ish haqi detallari</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover" id="salary_details_table">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" style="width:20px;" rowspan=2>T/r</th>
                                    <th class="text-center align-middle" rowspan=2>Shartnoma raqami</th>
                                    <th class="text-center align-middle" rowspan=2>Lavozimi</th>
                                    <th class="text-center align-middle" rowspan=2>Mahsulot</th>
                                    <th class="text-center align-middle" rowspan=2>Soni</th>
                                    <th class="text-center align-middle" rowspan=2>Maosh</th>
                                    <th class="text-center align-middle" colspan=2>Vaqti</th>
                                </tr>
                                <tr>
                                    <td class="text-center">To'lovga yuborilgan</td>
                                    <td class="text-center">To'langan</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Lavozimi"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Soni"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="Maosh"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="To'lovga yuborilgan"></td>
                                    <td><input class="form-control form-control-sm" type="text" placeholder="To'langan"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total_salary = 0; ?>
                                @foreach($details as $key => $value)
                                    <?php $total_salary += $value->salary; ?>
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $value->order_id }}/{{ $value->contract_number }}</td>
                                        <td>{{ $value->job }}</td>
                                        <td>{{ $value->order_process_product }}</td>
                                        <td>{{ $value->product_count }}</td>
                                        <td>{{ number_format($value->salary, 2, ",", " ")}} so'm</td>
                                        <td>{{ $value->paid_time }}</td>
                                        <td>{{ $value->cashier_paid_time }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Jami:</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{number_format($total_salary, 2, ",", " ")}} so'm</td>
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
@endsection

@section('scripts')
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      let salary_details_table = $('#salary_details_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100],
            [25, 50, 100]
        ],
        scrollX: true,
        "ordering": false
      });
      salary_details_table.columns().every(function(){
        let column = this;
        $('input', this.header()).on('keyup change', function() {
            column
                .search( this.value )
                .draw();
        });
      });
    });
  </script>
@endsection