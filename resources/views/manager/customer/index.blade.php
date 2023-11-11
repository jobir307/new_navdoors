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
        <div class="row">
          <div class="col-md-12">
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalcustomer" style="float: right;">Yaratish</button>
          </div>
        </div>
          <div class="card">
            <h5 class="card-header">Xaridorlar ro'yxati</h5>
            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover" id="customer_table">
                  <thead>
                    <tr>
                      <th class="text-center" style="width: 20px;">T/r</th>
                      <th class="text-center" style="width: 450px;">FIO</th>
                      <th class="text-center">Manzili</th>
                      <th class="text-center" style="width: 160px;">Telefon raqami</th>
                      <th style="min-width: 100px; width: 100px;"></th>
                    </tr>
                    <tr>
                      <td></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="FIO"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Manzili"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($customers as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->address }}</td>
                        <td>{{ $value->phone_number }}</td>
                        <td class="text-sm-end">
                          <button data-id="{{ $value->id }}" data-url="{{ route('manager-customers.edit', $value->id) }}" data-update_url="{{ route('manager-customers.update', $value->id) }}" class="btn-sm btn btn-outline-primary customer_edit">
                            O'zgartirish
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Create new customer -->
          <div class="modal modal-top fade" id="modalcustomer" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form action="{{ route('manager-customers.store') }}" method="POST">
                  @csrf
                  <div class="modal-header">
                    <h3>Yangi xaridor qo'shish</h3>
                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                    ></button>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-4 mb-3">
                        <label class="form-label" for="username">FIO</label><span style="color: red; font-size: 20px;">*</span>
                        <input type="text" name="name" class="form-control" id="username" autocomplete="off" required>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label" for="phone_number">Telefon raqami</label><span style="color: red; font-size: 20px;">*</span>
                        <input type="text" name="phone_number" class="form-control" id="phone_number" autocomplete="off" value="+998" required>
                      </div>
                      <div class="col-md-4 mb-3">
                        <?php 
                          $customer_types = array('Xaridor' => "Jismoniy shaxs", "Yuridik" => "Yuridik shaxs");
                        ?>
                        <label class="form-label" for="customer_type">Xaridor turi</label><span style="color: red; font-size: 20px;">*</span>
                        <select name="customer_type" class="form-select" id="customer_type">
                          @foreach($customer_types as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label" for="region">Viloyat</label>
                        <select class="form-select regions" id="region" name="region_id" style="width: 100%;">
                          <option readonly></option>
                          @foreach($regions as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label" for="district">Tuman</label>
                        <select class="form-select districts" name="district_id" id="district" style="width: 100%;">
                            <option value=""></option>
                        </select>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label" for="mahalla">Mahalla</label>
                        <select class="form-select mahalla" name="mahalla_id" id="mahalla" style="width: 100%;">
                            <option value=""></option>
                        </select>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label" for="street">Ko'cha</label>
                        <select class="form-select streets" name="street_id" id="street" style="width: 100%;">
                            <option value=""></option>
                        </select>
                      </div>
                      <div class="col-md-4 mb-3">
                        <label class="form-label" for="home">Uy</label>
                        <input type="text" id="home" name="home" class="form-control home" autocomplete="off">
                      </div>
                      <div class="col-md-4 mb-3 inn_div" style="display:none;">
                        <label class="form-label" for="inn">INN</label>
                        <input type="text" id="inn" name="inn" class="form-control" autocomplete="off">
                      </div>
                      <div class="col-md-12 mb-3">
                        <label class="form-label" for="address">To'liq manzili</label><span style="color: red; font-size: 20px;">*</span>
                        <input type="text" name="address" class="form-control full_address" id="address" autocomplete="off">
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Yopish</button>
                    <button type="submit" class="btn btn-primary">Saqlash</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Update customer -->
          <div class="modal modal-top fade" id="modalcustomerupdate" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form method="POST" class="customer_form">
                  @csrf
                  @method("PUT")
                  <div class="modal-header">
                    <h3>Xaridor ma'lumotlarini o'zgartirish</h3>
                    <button
                      type="button"
                      class="btn-close"
                      data-bs-dismiss="modal"
                      aria-label="Close"
                    ></button>
                  </div>
                  <div class="modal-body customer_body"></div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Yopish</button>
                    <button type="submit" class="btn btn-primary">Saqlash</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/js/select2.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/js/main.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/datatable/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/datatable/js/dataTables.bootstrap5.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/js/managerCustomerType.js') }}"></script>
  <script type="text/javascript">
    let region_name = "";
    let district_name = "";
    let mahalla_name = "";
    let street_name = "";
    let home = "";

    function getFullAddress(){
      $('.full_address').val(region_name + ' ' + district_name + ' ' + mahalla_name + ' ' + street_name + ' ' + home);
    }

    $(document).ready(function () {
      let table = $('#customer_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, "Hammasi"]
        ],
        "ordering": false
      });

      table.columns().every( function () {
        var column = this;
     
        $( 'input', this.header()).on('keyup change', function() {
            column
                .search( this.value )
                .draw();
        });
      });

      $('body').on('click', '.customer_edit', function(){
        let url = $(this).data("url"), id = $(this).data("id"), update_url=$(this).data('update_url');

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: url,
          method: "GET",
          data: {id: id},
          success: function(data) {
            let customer_form = document.getElementsByClassName("customer_form")[0];
            customer_form.setAttribute("action", update_url);
            $(".customer_body").html(data);
            $("#modalcustomerupdate").modal("show"); 
          }
        });
      });

      $('body').on("change", '.regions', function(){
        let region_id = $(this).val(), region = $(".regions option:selected").text();
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{ route('get-region-districts') }}",
          method: "POST",
          data: {region_id: region_id},
          success: function(data) {
            $('.districts').html('<option></option>');
            $('.districts').append(data);
            region_name = region;
            getFullAddress();
          }
        });
      });

      $('body').on("change", '.districts', function(){
        let district_id = $(this).val(), district = $(".districts option:selected").text();
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{ route('get-district-mahalla') }}",
          method: "POST",
          data: {district_id: district_id},
          success: function(data) {
            $('.mahalla').html('<option></option>');
            $('.mahalla').append(data);
            district_name = district;
            getFullAddress();
          }
        });
      });

      $('body').on("change", '.mahalla', function(){
        let mahalla_id = $(this).val(), mahalla = $(".mahalla option:selected").text();
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{ route('get-mahalla-streets') }}",
          method: "POST",
          data: {mahalla_id: mahalla_id},
          success: function(data) {
            $('.streets').html('<option></option>');
            $('.streets').append(data);
            mahalla_name = mahalla + ' махалласи';
            getFullAddress();
          }
        });
      });

      $('body').on('change', '.streets', function() {
        let street = $(".streets option:selected").text();
        street_name = street + ' кучаси';
        getFullAddress();
      });

      $('body').on('input', '.home', function() {
          home = $(this).val();
          getFullAddress();
      });
    });
  </script>
@endsection