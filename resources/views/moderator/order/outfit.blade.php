@extends('layouts.moderator')
@section('content')
  <div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
      <h4 class="fw-bold py-3 mb-4"><a href="{{ route('moderator') }}" class="fw-light">Naryadlar / </a><span class="text-muted fw-light">Naryad №{{ $order_processes[0]->order_id }} ma'lumotlarini boshqarish (eshik turi: {{ $order_processes[0]->doortype }})</h4>
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <div class="table-responsive text-nowrap">
          <table class="table table-bordered table-hover" style="width:100%">
            <thead>
              <tr>
                <th class="text-center align-middle" style="width: 20px;" rowspan="2">T/r</th>
                <th class="text-center align-middle" rowspan="2" style="width: 400px;">Jarayon nomi</th>
                <th class="text-center align-middle" rowspan="2">Xodim</th>
                <th class="text-center align-middle" style="width: 240px !important;" colspan="2">Holati</th>
                <th class="text-center align-middle" style="width: 50px !important;" rowspan="2"></th>
              </tr>
              <tr>
                <td class="text-center align-middle" style="width: 120px !important;">Boshladi</td>
                <td class="text-center align-middle" style="width: 120px !important;">Tugatdi</td>
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
                  <td>
                    @if($value->started == 0)
                      <button type="button" class="btn btn-sm btn-success start_outfit" data-order_process="{{ $value->id }}" data-order_id="{{ $value->order_id }}">Boshlash</button>
                    @else
                      <h6 class="text-center text-success align-middle my-1">Boshlandi</h6>
                    @endif
                  </td>
                  <td>
                    @if($value->done == 0)
                      <button type="button" class="btn btn-sm btn-primary end_outfit" data-order_process="{{ $value->id }}">Tugatish</button>
                    @else
                      <h6 class="text-center text-primary align-middle my-1">Tugatildi</h6>
                    @endif
                  </td>
                  <td class="align-middle text-center">
                    <a href="{{ route('order-job-assignment', $value->id) }}" class="btn btn-sm btn-icon btn-outline-secondary" title="Chop etish">
                      <i class="bx bx-printer"></i>
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-2"></div>
    </div>
    <?php 
      $process_over = false;
      $check_if_process_over = DB::select('SELECT * 
                                           FROM order_processes 
                                           WHERE order_id=? AND started=0 OR done=0', [$order_processes[0]->order_id]);
      if (empty($check_if_process_over)){
        $process_over  = true;
        $waybill = DB::select('SELECT a.name as driver, a.type as driver_type, b.driver_id, b._from, b._to
                               FROM waybills b
                               INNER JOIN drivers a ON a.id=b.driver_id
                               WHERE b.order_id=?', [$order_processes[0]->order_id]);
      }
    ?>
    @if($process_over)
    <div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <form action="{{ route('outfit-closed') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-2">
              <label class="form-label">Qayerdan</label>
              <input type="text" value="{{ $waybill[0]->_from ?? 'Zavoddan' }}" readonly class="form-control" name="_from">
            </div>
            <div class="col-md-3">
              <label class="form-label">Qayerga</label>
              <input type="text" class="form-control" name="_to" value="{{ $waybill[0]->_to ?? 'Skladga' }}" autocomplete="off">
            </div>
            <div class="col-md-2">
              <div class="form-check mt-3">
                <input
                  name="driver_radio"
                  class="form-check-input driverRadio"
                  type="radio"
                  value="company"
                  id="companyRadio"
                  {{ $waybill[0]->driver_type == 'company' ? 'checked' : '' }} 
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
                  {{ $waybill[0]->driver_type == 'carrier' ? 'checked' : '' }}
                />
                <label class="form-check-label" for="carrierRadio"> Kuryer </label>
              </div>
            </div>
            @if (!empty($waybill) && $waybill[0]->driver_type == 'company')
              <div class="col-md-3 company_div">
                <label class="form-label">Haydovchi</label>
                <select class="form-select" name="driver_id">
                  <option></option>
                  @foreach($company_drivers as $key => $value)
                    @if(!empty($waybill) && $waybill[0]->driver_id == $value->id)
                      <option value="{{ $value->id }}" selected>{{ $value->gov_number }} ({{ $value->car_model }})</option>
                    @else
                      <option value="{{ $value->id }}">{{ $value->gov_number }} ({{ $value->car_model }})</option>
                    @endif
                  @endforeach
                </select>
              </div>
              <div class="col-md-3 carrier_div" style="display: none;">
                <label class="form-label">Kuryer</label>
                <select class="form-select" name="courier_id">
                  <option></option>
                  @foreach($carrier_drivers as $key => $value)
                    @if(!empty($waybill) && $waybill[0]->driver_id == $value->id)
                      <option value="{{ $value->id }}" selected>{{ $value->gov_number }} ({{ $value->car_model }})</option>
                    @else
                      <option value="{{ $value->id }}">{{ $value->gov_number }} ({{ $value->car_model }})</option>
                    @endif
                  @endforeach
                </select>
              </div>
            @else
              <div class="col-md-3 company_div" style="display: none;">
                <label class="form-label">Haydovchi</label>
                <select class="form-select" name="driver_id">
                  <option></option>
                  @foreach($company_drivers as $key => $value)
                    @if(!empty($waybill) && $waybill[0]->driver_id == $value->id)
                      <option value="{{ $value->id }}" selected>{{ $value->gov_number }} ({{ $value->car_model }})</option>
                    @else
                      <option value="{{ $value->id }}">{{ $value->gov_number }} ({{ $value->car_model }})</option>
                    @endif
                  @endforeach
                </select>
              </div>
              <div class="col-md-3 carrier_div">
                <label class="form-label">Kuryer</label>
                <select class="form-select" name="courier_id">
                  <option></option>
                  @foreach($carrier_drivers as $key => $value)
                    @if(!empty($waybill) && $waybill[0]->driver_id == $value->id)
                      <option value="{{ $value->id }}" selected>{{ $value->gov_number }} ({{ $value->car_model }})</option>
                    @else
                      <option value="{{ $value->id }}">{{ $value->gov_number }} ({{ $value->car_model }})</option>
                    @endif
                  @endforeach
                </select>
              </div>
            @endif
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary mt-4" style="float:right;">Naryadni yakunlash</button>
            </div>
          </div>
          <input type="hidden" name="order_id" value="{{ $order_processes[0]->order_id }}">
          
        </form>
      </div>
      <div class="col-md-2"></div>
    </div>
    @endif
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
              // alert(data.message);
              location.reload();
            }
          });
        }
      });

      $('body').on('click', '.start_outfit', function() {
        let order_process = $(this).data('order_process'), order_id = $(this).data('order_id'), worker_id = $(this).closest('.outfit_tr').find('.worker_select').val();
        if (worker_id != 0) {
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('start-outfit') }}",
            method: "POST",
            data: {order_process: order_process, order_id: order_id},
            success: function(data) {
              // alert(data.message);
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
              // alert(data.message);
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
    });
  </script>
@endsection


