@extends('layouts.moderator')
@section('content')
  <div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body" style="margin-bottom: 150px;">
            <h4 class="fw-bold py-3 mb-4"><a href="{{ route('moderator') }}" class="fw-light">Naryadlar / </a><span class="fw-light">Shartnoma №{{ $order->id }}/{{ $order->contract_number }} ma'lumotlarini boshqarish (mahsulot: {{ $product_model }} {{ $product_model2 ?? "" }} {{ $product_model3 ?? "" }} {{ $product_model4 ?? "" }})</h4>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive text-nowrap">
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="text-center align-middle" style="width: 20px;" rowspan="3">T/r</th>
                        <th class="text-center align-middle" rowspan="3" style="width: 240px !important;">Mahsulot</th>
                        <th class="text-center align-middle" rowspan="3" style="width:300px;">Jarayon nomi</th>
                        <th class="text-center align-middle" rowspan="3" style="width: 400px !important;">Xodim</th>
                        <th class="text-center align-middle" colspan="4">Holati</th>
                        <th class="text-center align-middle" style="width: 50px !important;" rowspan="3"></th>
                      </tr>
                      <tr>
                        <th class="text-center align-middle" colspan=2>Boshlash</th>
                        <th class="text-center align-middle" colspan=2>Tugatish</th>
                      </tr>
                      <tr>
                        <th class="text-center align-middle" style="width: 140px !important;">Boshlandi</th>
                        <th class="text-center align-middle">Vaqti</th>
                        <th class="text-center align-middle" style="width: 140px !important;">Tugatildi</th>
                        <th class="text-center align-middle">Vaqti</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($order_processes as $key => $value)
                        <?php
                          $workers = DB::select('SELECT a.id as worker_id, 
                                                        a.fullname as worker_name, 
                                                        b.id as job_id, 
                                                        b.name as job_name 
                                                 FROM (workers a, jobs b)
                                                 LEFT JOIN worker_jobs c ON c.worker_id=a.id AND c.job_id=b.id
                                                 WHERE a.active = 1 AND c.job_id=?', [$value->job_id]);
        
                        ?>
                        <tr class="outfit_tr">
                          <td class="text-center">{{ $key + 1 }}</td>
                          <td>{{ $value->product }}</td>
                          <td>{{ $value->job_name }}</td>
                          <td>
                            @if ($value->started == 1 && $value->done == 1)
                              <select class="form-select form-select-sm worker_select" data-order_process="{{ $value->id }}" disabled>
                                <option value="0">Tanlang</option>
                                @foreach($workers as $k => $v)
                                  @if($v->worker_id == $value->worker_id) 
                                    <option value="{{ $v->worker_id }}" selected>{{ $v->worker_name }}</option>
                                  @else
                                    <option value="{{ $v->worker_id }}">{{ $v->worker_name }}</option>
                                  @endif
                                @endforeach
                              </select>
                            @else
                              <select class="form-select form-select-sm worker_select" data-order_process="{{ $value->id }}">
                                <option value="0">Tanlang</option>
                                @foreach($workers as $k => $v)
                                  @if($v->worker_id == $value->worker_id) 
                                    <option value="{{ $v->worker_id }}" selected>{{ $v->worker_name }}</option>
                                  @else
                                    <option value="{{ $v->worker_id }}">{{ $v->worker_name }}</option>
                                  @endif
                                @endforeach
                              </select>
                            @endif
                          </td>
                          <td style="text-align:center;">
                            @if($value->started == 0)
                              <button type="button" class="btn btn-sm btn-success start_outfit" data-order_process="{{ $value->id }}" data-order_id="{{ $value->order_id }}">Boshlash</button>
                            @else
                              <h6 class="text-success align-middle my-1">Boshlandi</h6>
                            @endif
                          </td>
                          @if (!empty($value->started_datetime))
                            <td style="text-align:center;">{{ date('d.m.Y H:i', strtotime($value->started_datetime)) ?? "" }}</td>
                          @else
                            <td></td>
                          @endif
                          <td style="text-align:center;">
                            @if($value->done == 0)
                              <button type="button" class="btn btn-sm btn-primary end_outfit" data-order_process="{{ $value->id }}">Tugatish</button>
                            @else
                              <h6 class="text-center text-primary align-middle my-1">Tugatildi</h6>
                            @endif
                          </td>
                          @if (!empty($value->done_datetime))
                            <td style="text-align:center;">{{ date('d.m.Y H:i', strtotime($value->done_datetime)) ?? "" }}</td>
                          @else
                            <td></td>
                          @endif
                          <td class="align-middle text-center">
                            <?php
                              $pdf_route = "";
                              switch ($order->product) {
                                case 'jamb':
                                  $pdf_route = 'jamb-job-assignment';
                                  break;
                                case 'nsjamb':
                                  $pdf_route = 'nsjamb-job-assignment';
                                  break;
                                case 'transom':
                                  $pdf_route = 'transom-job-assignment';
                                  break;
                                case 'jamb+transom':
                                  $pdf_route = 'jamb-transom-job-assignment';
                                  break;
                                case 'door':
                                  $pdf_route = 'door-job-assignment';
                                  break;
                                default:
                                  $pdf_route = 'crown-boot-cube-job-assignment';
                                  break;
                              }
                            ?>
                            <a href="{{ route($pdf_route, $value->id) }}" class="btn btn-sm btn-icon btn-outline-secondary" title="Chop etish">
                              <i class="bx bx-printer"></i>
                            </a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php 
              switch ($order->product) {
                case 'jamb':
                  $product = "jamb";
                  break;
                case 'nsjamb':
                  $product = "nsjamb";
                  break;
                case 'transom':
                  $product = "transom";
                  break;
                case 'jamb+transom':
                  $product = "jamb+transom";
                  break;
                case 'door':
                  $product = "door";
                  break;
                default:
                  $product = "ccbj";
                  break;
              }
            ?>
            @if (!empty($details))
              <div class="row mt-3">
                <div class="col-md-12">
                  <form action="{{ route('moderator-create-waybill') }}" method="POST">
                    @csrf
                    @if ($check_process_over != 1 || $order->moderator_send != 1)
                      <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                        @if (Session::has('message'))
                          <div class="alert alert-danger text-center" style="font-size:16px;">{{ Session::get('message') }}</div>
                        @endif
                          <div class="row">
                            <div class="col-md-2">
                              <label class="form-label">Qayerdan</label>
                              <input type="text" readonly class="form-control" name="_from" value="Zavoddan">
                            </div>
                            <div class="col-md-2">
                              <label class="form-label">Qayerga</label>
                              <input type="text" class="form-control" name="_to" autocomplete="off" value="Skladga">
                            </div>
                            <div class="col-md-2">
                              <div class="form-check mt-4">
                                <input
                                  name="driver_radio"
                                  class="form-check-input driverRadio"
                                  type="radio"
                                  value="company"
                                  id="companyRadio"
                                  checked
                                />
                                <label class="form-check-label" for="companyRadio"> Korxona </label>
                              </div>
                              <div class="form-check">
                                <input
                                  name="driver_radio"
                                  class="form-check-input driverRadio"
                                  type="radio"
                                  value="carrier"
                                  id="carrierRadio"
                                />
                                <label class="form-check-label" for="carrierRadio"> Kuryer </label>
                              </div>
                            </div>
                            <div class="col-md-3 company_div">
                              <label class="form-label">Korxona</label>
                              <select class="form-select" name="driver_id">
                                <option></option>
                                @foreach($company_drivers as $key => $value)
                                  <option value="{{ $value->id }}">{{ $value->gov_number }} ({{ $value->car_model }})</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-3 carrier_div" style="display:none;">
                              <label class="form-label">Kuryer</label>
                              <select class="form-select" name="courier_id">
                                <option></option>
                                @foreach($carrier_drivers as $key => $value)
                                  <option value="{{ $value->id }}">{{ $value->gov_number }} ({{ $value->car_model }})</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-md-3">
                              <button type="submit" class="btn btn-success" style="margin-top:30px; margin-left:50px;">Yuk yuborish</button>
                            </div>
                          </div>
                          <input type="hidden" name="order_id" value="{{ $order_processes[0]->order_id }}">
                          <input type="hidden" name="product" value="{{ $product }}">
                          <input type="hidden" name="details" value="{{ json_encode($details) }}">

                        <div class="col-md-2"></div>
                      </div>
                    @endif
                    <div class="row mt-3">
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <table class="table table-bordered table-hover">
                          <thead>
                            <tr>
                              <th class="text-center align-middle" rowspan=2 style="width:15px;">T/r</th>
                              <th class="text-center align-middle" rowspan=2>Nomi</th>
                              <th class="text-center align-middle" rowspan=2 style="width: 100px;">Soni</th>
                              @if ($check_process_over != 1 || $order->moderator_send != 1)
                                <th class="text-center align-middle" colspan=2 style="width: 200px !important;">Jo'natiladi</th>
                              @endif
                            </tr>
                            @if ($check_process_over != 1 || $order->moderator_send != 1)
                              <tr>
                                <td style="width: 80px !important;">
                                  <input type="checkbox" class="form-check-input check_all_details" style="margin:auto">
                                </td>
                                <th style="width: 120px !important;" class="text-center align-middle">Soni</th>
                              </tr>
                            @endif
                          </thead>
                          <tbody>
                            @foreach($details as $key => $value)
                              @if (!empty($value))
                                <tr>
                                  <td class="text-center align-middle">{{ $key + 1 }}</td>
                                  <td class="align-middle">{{ $value['name'] }}</td>
                                  <td class="text-center align-middle">{{ $value['count'] }}</td>
                                  @if ($check_process_over != 1 || $order->moderator_send != 1)
                                    <td>
                                      <input type="checkbox" class="form-check-input sended_details_checkbox" data-product="{{ $value['name'] }}" style="margin:auto">
                                      <input type="hidden" name="sended_detail[]">
                                    </td>
                                    <td>
                                      <input type="number" step="0.5" class="form-control form-control-sm" name="detail_count[]" min="0.5" max="{{ $value['count'] }}" value="{{ $value['count'] }}"> 
                                    </td>
                                  @endif
                                </tr>
                              @endif
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <div class="col-md-2"></div>
                    </div>
                  </form>
                </div>
              </div>
            @endif
            @if (!empty($waybills))
            <div class="row mt-3">
              <div class="col-md-2"></div>
                <div class="col-md-8">
                  <h4 class="text-center text-primary">Yaratilgan yuk xatlari</h4>
                  <table class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th class="text-center align-middle" style="width:30px;">T/r</th>
                        <th class="text-center align-middle" style="width:10px;">Qayerdan</th>
                        <th class="text-center align-middle">Qayerga</th>
                        <th class="text-center align-middle">Haydovchi</th>
                        <th class="text-center align-middle">Qachon jo'natildi</th>
                        <th class="text-center align-middle" style="width:100px">Batafsil</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($waybills as $key => $value)
                        <tr>
                          <td class="text-center">{{ $key + 1 }}</td>
                          <td>{{ $value->_from }}</td>
                          <td>{{ $value->_to }} </td>
                          <td>{{ $value->car_model }} {{ $value->gov_number }}({{ $value->driver_type }})</td>
                          <td>{{ date("d.m.Y H:i", strtotime($value->created_at)) }} </td>
                          <td>
                            <a href="{{ route('moderator-waybill-show', $value->id) }}" class="btn btn-sm btn-outline-primary">Ko'rish</a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="col-md-2"></div>
              </div>
            @endif
            @if ($check_process_over)
              <div class="row mt-3">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                  @if ($order->moderator_send == 0)
                    <form action="{{ route('moderator-order-close') }}" method="POST">
                      @csrf
                      <input type="hidden" name="order_id" value="{{ $order_processes[0]->order_id }}">
                      <button type="submit" class="btn btn-primary">Naryadni yakunlash</button>
                    </form>
                  @else
                    <h4 class="text-primary">Naryad muvaffaqiyatli yakunlandi!(Yakunlangan vaqti: {{ date("d.m.Y H:i", strtotime($order->moderator_send_time)) }})</h4>
                  @endif
                </div>
                <div class="col-md-2"></div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}" type="text/javascript"></script>

  <script type="text/javascript">
    $(document).ready(function(){
      $('body').on('change', '.worker_select', function() {
        let worker_id = $(this).val(), order_process = $(this).data('order_process');
        if (worker_id != 0) {
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('set-worker') }}",
            method: "POST",
            data: {order_process: order_process, worker_id: worker_id},
            success: function(data) {
              location.reload();
            }
          });
        }
      });

      $('body').on('click', '.start_outfit', function() {
        let order_process = $(this).data('order_process'), 
            order_id = $(this).data('order_id'), 
            worker_id = $(this).closest('.outfit_tr').find('.worker_select').val();
        if (worker_id != 0) {
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('start-outfit') }}",
            method: "POST",
            data: {order_process: order_process, order_id: order_id},
            success: function(data) {
              location.reload();
            }
          });
        } else {
          alert('Xodimni naryadga biriktiring.');
        }
      });

      $('body').on('click', '.end_outfit', function() {
        let order_process = $(this).data('order_process'), worker_id = $(this).closest('.outfit_tr').find('.worker_select').val();
        if (worker_id != 0) {
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('end-outfit') }}",
            method: "POST",
            data: {order_process: order_process},
            success: function(data) {
              location.reload();
            }
          });
        } else {
          alert('Xodimni naryadga biriktiring.');
        }
      });

      $('body').on('change', '.driverRadio', function(){
        let val = $(this).val();
        if (val === 'carrier') {
          $('.carrier_div').css('display', 'block');
          $('.company_div').css('display', 'none');
          $('.company_div select[name="driver_id"]').val(null);
        } else {
          $('.carrier_div').css('display', 'none');
          $('.carrier_div select[name="courier_id"]').val(null);
          $('.company_div').css('display', 'block');
        }
      });
      
      $('body').on('change', '.sended_details_checkbox', function(){
        if(this.checked) {
          let detailName = $(this).data("product");
          $(this).closest("tr").find('input[name="sended_detail[]"]').val(detailName);
        } else {
          $(this).closest("tr").find('input[name="sended_detail[]"]').val('');
        }
      });

      $('body').on('change', '.check_all_details', function(){
        if (this.checked) {
          $('.sended_details_checkbox').each(function(){
            let detailName = $(this).data('product');
            $(this).closest('tr').find('input[name="sended_detail[]"]').val(detailName);
            $(this).prop('checked', true);
          });
        } else {
          $('.sended_details_checkbox').each(function(){
            $(this).closest('tr').find('input[name="sended_detail[]"]').val("");
            $(this).prop('checked', false);
          });
        }
      });
    });

  </script>
@endsection