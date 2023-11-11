@extends('layouts.manager')
@section('content')
  <div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('orders') }}" class="fw-light">Shartnomalar / </a><span class="fw-light">Yangi shartnoma yaratish(dobor)</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <form method="POST"  action="{{ route('order-transoms.store') }}">
            @csrf
            <div class="card-body transoms_content">
              <div class="row">
                @if (Auth::user()->role_id != 8)
                  <div class="mb-3 col-md-1 mt-2">
                    <div class="form-check mt-3">
                      <input
                        name="customer_radio"
                        class="form-check-input customerRadio"
                        type="radio"
                        value="customer"
                        id="customerRadio"
                        checked
                      />
                      <label class="form-check-label" for="customerRadio"> Xaridor </label>
                    </div>
                    <div class="form-check">
                      <input
                        name="customer_radio"
                        class="form-check-input customerRadio"
                        type="radio"
                        value="dealer"
                        id="dealerRadio"
                      />
                      <label class="form-check-label" for="dealerRadio"> Diler </label>
                    </div>
                  </div>
                @endif
                @if (Auth::user()->role_id == 8)
                  <div class="mb-3 col-md-4 dealer_div" style="display:block;">
                    <label for="diler" class="form-label">Diler</label><span style="color: red; font-size: 20px;">*</span>
                    <select class="form-control js-example-basic-single" id="diler" name="dealer" style="width: 100%;" disabled>
                      @foreach($dealers as $key => $value)
                        @if (Auth::user()->dealer_id == $value->id)
                          <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                        @else
                          <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                @else
                  <div class="mb-3 col-md-3 dealer_div" style="display: none;">
                    <label for="diler" class="form-label">Diler</label><span style="color: red; font-size: 20px;">*</span>
                    <select class="form-control js-example-basic-single" id="diler" name="dealer" style="width: 100%;">
                      @foreach($dealers as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-3 customer_div">
                    <label for="diler" class="form-label">Xaridor</label><span style="color: red; font-size: 20px;">*</span>
                    <select class="form-select js-example-basic-single" name="customer">
                      @foreach($customers as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endforeach
                    </select>
                  </div>
                @endif
                <div class="mb-3 col-md-2">
                  <label for="contract_number" class="form-label">Shartnoma raqami</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="text" name="contract_number" id="contract_number" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-2">
                  <label for="transom_color" class="form-label">Dobor rangi</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="text" name="transom_color" id="transom_color" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-2">
                  <label for="deadline" class="form-label">Topshirish muddati</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="date" name="deadline" id="deadline" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-1">
                  <div class="form-check">
                    <label for="with_installation" class="form-check-label">Ustanovka</label>
                    <input class="form-check-input installation_check" type="checkbox" name="with_installation" id="with_installation">
                  </div>
                  <input type="text" class="form-control installation_price mt-2" name="transom_installation_price" style="display: none;" placeholder="Ustanovka narxi" autocomplete="off">
                </div>
                <div class="mb-3 col-md-1">
                  <div class="form-check">
                    <label for="with_courier" class="form-check-label">Dostavka</label>
                    <input class="form-check-input courier_check" type="checkbox" name="with_courier" id="with_courier">
                  </div>
                  <input type="text" class="form-control courier_price mt-2" name="courier_price" style="display: none;" placeholder="Dostavka narxi" autocomplete="off">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button type="button" class="btn btn-sm btn-outline-success mt-4 transom_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-warning mt-4 transom_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                </div>
              </div>
              <div class="row mt-4 transom_div justify-content-end" data-index="0">
                <div class="mb-3 col-md-4">
                    <label for="transom" class="form-label">Dobor turi</label>
                    <select class="form-select" name="transom_id[]" id="transom">
                        <option value=""></option>
                        @foreach($transoms as $key => $value)
                            <option value="{{ $value->id }}">{{ $value->transom_name }}({{ $value->doortype_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-md-2">
                    <label for="transom_height" class="form-label">Bo'yi</label>
                    <input class="form-control" type="number" name="transom_height[]" id="transom_height" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-2">
                    <label for="width_top" class="form-label">Eni (tepa)</label>
                    <input class="form-control" type="number" name="transom_width_top[]" id="width_top" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-2">
                    <label for="width_bottom" class="form-label">Eni (past)</label>
                    <input class="form-control" type="number" name="transom_width_bottom[]" id="width_bottom" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-2">
                    <label for="count" class="form-label">Soni</label>
                    <input class="form-control" type="number" name="count[]" id="count" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-md-10">
                  <textarea name="comments" class="form-control" rows="10" placeholder="Izoh qoldiring..."></textarea>
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary me-2 float-end">Saqlash</button>
                </div>  
              </div>
            </div>
          </form>
        </div>

        <!-- Labelsiz dobor parametrlari -->
        <div class="row mt-4 transom_div_without_labels" style="display:none;">
            <div class="mb-3 col-md-4">
                <select class="form-select" name="transom_id[]">
                    <option value=""></option>
                    @foreach($transoms as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->transom_name }}({{ $value->doortype_name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-2">
                <input class="form-control" type="number" name="transom_height[]" autocomplete="off" />
            </div>
            <div class="mb-3 col-md-2">
                <input class="form-control" type="number" name="transom_width_top[]" autocomplete="off" />
            </div>
            <div class="mb-3 col-md-2">
                <input class="form-control" type="number" name="transom_width_bottom[]" autocomplete="off" />
            </div>
            <div class="mb-3 col-md-2">
                <input class="form-control" type="number" name="count[]" autocomplete="off" />
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
  <script type="text/javascript">
    $(document).ready(function(){
      let i = 1;

      $('body').on('click', '.transom_plus', function(){
        let targetDiv = document.getElementsByClassName("transom_div_without_labels")[0];
        $(".transoms_content").last().append('<div class="row transom_div justify-content-end">'+targetDiv.innerHTML+'</div>');
        i++;
      });

      $('body').on('click', '.transom_minus', function(){
        if(i>1){
          let childDiv = $('body').find('.transoms_content .row').last();
          childDiv.remove();
          i--;
        }
      });

      $('body').on('change', '.customerRadio', function(){
        let val = $(this).val();
        if (val == 'customer') {
          $('.customer_div').css('display', 'block');
          $('.dealer_div').css('display', 'none');
        } else {
          $('.customer_div').css('display', 'none');
          $('.dealer_div').css('display', 'block');
        }
      });

      $('body').on('change', '.courier_check', function(){
        if ($(this).is(':checked')) {
          $("input.courier_price").css("display", "block");
        } else {
          $("input.courier_price").css("display", "none");
        }
      });

      $('body').on('change', '.installation_check', function(){
        if ($(this).is(':checked')) {
          $("input.installation_price").css("display", "block");
        } else {
          $("input.installation_price").css("display", "none");
        }
      });

      $('.js-example-basic-single').select2();
    });
  </script>
@endsection