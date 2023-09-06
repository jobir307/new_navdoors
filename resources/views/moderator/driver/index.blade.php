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
      <div class="col-md-3">
        <h5 class="text-primary">Haydovchilar ro'yxati</h5>
      </div>
      <div class="col-md-9">
        <button class="btn btn-primary add_btn" style="float: right;" type="button">Yaratish</button>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-12">
        <div class="table-responsive text-nowrap">
          <table class="table table-bordered table-hover" id="drivers_table" style="width:100%">
            <thead>
              <tr>
                <th class="text-center" style="width: 20px;">T/r</th>
                <th class="text-center">FIO</th>
                <th class="text-center">Telefon raqami</th>
                <th class="text-center">Modeli</th>
                <th class="text-center" style="width: 130px;">Davlat raqami</th>
                <th class="text-center">Turi</th>
                <th style="min-width: 130px; width: 130px;"></th>
              </tr>
              <tr>
                <td></td>
                <td><input class="form-control form-control-sm" type="text" placeholder="FIO"></td>
                <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                <td></td>
                <td><input class="form-control form-control-sm" type="text" placeholder="Davlat raqami"></td>
                <td></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $auto_types = array('carrier' => 'Kuryer', 'company' => 'Korxona'); ?>
              @foreach($drivers as $key => $value)
                <tr>
                  <td class="text-center">{{ $key + 1 }}</td>
                  <th class="text-center">{{ $value->driver }}</th>
                  <th class="text-center">{{ $value->phone_number }}</th>
                  <td>{{ $value->car_model }}</td>
                  <td>{{ $value->gov_number }}</td>
                  <td>{{ $auto_types[$value->type] }}</td>
                  <td class="text-sm-end"></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Create Driver Modal -->
    <div class="modal fade" id="create-driver-car-modal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-primary" id="modalCenterTitle">Yangi haydovchi yaratish oynasi</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <form action="{{ route('drivers.store') }}" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-md-2">
                      <label class="form-label">FIO</label>
                      <input class="form-control" name="name" autocomplete="off">
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Telefon raqami</label>
                      <input class="form-control" name="phone_number" autocomplete="off">
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Model</label>
                      <select class="form-select" name="carmodel_id">
                        @foreach($car_models as $key => $value)
                          <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Davlat raqami</label>
                      <input class="form-control" name="gov_number" autocomplete="off">
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Turi</label>
                      <?php 
                        $auto_types = array('carrier' => 'Kuryer', 'company' => 'Korxona');
                      ?>
                      <select class="form-select" name="type">
                        @foreach($auto_types as $key => $value)
                          <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-2">
                      <button type="submit" class="btn btn-outline-primary mt-4">Saqlash</button>
                    </div>
                  </div>
                </form>
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
      let drivers_table = $('#drivers_table').DataTable({
        responsive: true,
        dom: 'Qlrtp',
        "ordering": false
      });
      drivers_table.columns().every( function () {
        let column = this;
        $('input', this.header()).on('keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });

      $('body').on('click', '.add_btn', function() {
        $("#create-driver-car-modal").modal("show");
      });
    });
  </script>
@endsection


