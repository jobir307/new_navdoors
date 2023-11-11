@extends('layouts.manager')
@section('content')
  <div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('orders') }}" class="fw-light">Shartnomalar / </a><span class="fw-light">Shartnoma ma'lumotlarini o'zgartirish(nalichnik)</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <form method="POST"  action="{{ route('order-jambs.update', $order->id) }}">
            @method('PUT')
            @csrf
            <div class="card-body jambs_content">
              <div class="row">
                @if (Auth::user()->role_id != 8)
                  <div class="mb-3 col-md-1 mt-2">
                      @if ($order->customer_type == "Diler")
                      <div class="form-check mt-3">
                          <input
                              name="customer_radio"
                              class="form-check-input customerRadio"
                              type="radio"
                              value="customer"
                              id="customerRadio"
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
                              checked
                          />
                          <label class="form-check-label" for="dealerRadio"> Diler </label>
                      </div>
                      @else
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
                      @endif
                  </div>
                @endif
                @if ($order->customer_type == "Diler")
                  @if (Auth::user()->role_id == 8) 
                    <div class="mb-3 col-md-4 dealer_div">
                      <label for="diler" class="form-label">Diler</label><br>
                      <select class="form-control js-example-basic-single" id="diler" name="dealer" style="width: 100%;" disabled>
                        @foreach($dealers as $key => $value)
                          @if($order->customer_id == $value->id)
                            <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                          @else
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                  @else
                    <div class="mb-3 col-md-3 dealer_div">
                      <label for="diler" class="form-label">Diler</label><br>
                      <select class="form-control js-example-basic-single" id="diler" name="dealer" style="width: 100%;">
                        @foreach($dealers as $key => $value)
                          @if($order->customer_id == $value->id)
                            <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                          @else
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3 col-md-3 customer_div" style="display: none;">
                      <label for="diler" class="form-label">Xaridor</label>
                      <select class="form-control js-example-basic-single" name="customer" style="width: 100%;">
                        @foreach($customers as $key => $value)
                          @if($order->customer_id == $value->id)
                            <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                          @else
                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                          @endif
                        @endforeach
                      </select>
                    </div>
                  @endif

                @else
                  <div class="mb-3 col-md-3 dealer_div" style="display: none;">
                    <label for="diler" class="form-label">Diler</label><br>
                    <select class="form-control js-example-basic-single" id="diler" name="dealer" style="width: 100%;">
                      @foreach($dealers as $key => $value)
                        @if($order->customer_id == $value->id)
                          <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                        @else
                          <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-3 customer_div">
                    <label for="diler" class="form-label">Xaridor</label>
                    <select class="form-control js-example-basic-single" name="customer" style="width: 100%">
                      @foreach($customers as $key => $value)
                        @if($order->customer_id == $value->id)
                          <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                        @else
                          <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                @endif
                <div class="mb-3 col-md-2">
                  <label for="contract_number" class="form-label">Shartnoma raqami</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="text" name="contract_number" id="contract_number" autocomplete="off" value="{{ $order->contract_number }}">
                </div>
                <div class="mb-3 col-md-2">
                  <label for="jamb_color" class="form-label">Nalichnik rangi</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="text" name="jamb_color" id="jamb_color" autocomplete="off" value="{{ $jamb_results[0]->jamb_color }}">
                </div>
                <div class="mb-3 col-md-2">
                  <label for="deadline" class="form-label">Topshirish muddati</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="date" name="deadline" id="deadline" autocomplete="off" value="{{ $order->deadline }}">
                </div>
                <div class="mb-3 col-md-1">
                  @if($order->with_installation == 1) 
                    <div class="form-check">
                        <label for="with_installation" class="form-check-label">Ustanovka</label>
                        <input class="form-check-input installation_check" type="checkbox" name="with_installation" id="with_installation" checked>
                    </div>
                    <input type="text" class="form-control installation_price mt-2" name="jamb_installation_price" style="display: block;" placeholder="Ustanovka narxi" autocomplete="off" value="{{ $order->installation_price }}">
                  @else
                    <div class="form-check">
                      <label for="with_installation" class="form-check-label">Ustanovka</label>
                      <input class="form-check-input installation_check" type="checkbox" name="with_installation" id="with_installation">
                    </div>
                    <input type="text" class="form-control installation_price mt-2" name="jamb_installation_price" style="display: none;" placeholder="Ustanovka narxi" autocomplete="off">
                  @endif
                </div>
                <div class="mb-3 col-md-1">
                    @if($order->with_courier == 1)
                        <div class="form-check">
                            <label for="with_courier" class="form-check-label">Dostavka</label>
                            <input class="form-check-input courier_check" type="checkbox" name="with_courier" id="with_courier" checked>
                        </div>
                        <input type="number" class="form-control courier_price mt-2" name="courier_price" style="display: block;" placeholder="Dostavka narxi" autocomplete="off" value="{{ $order->courier_price }}">
                    @else
                        <div class="form-check">
                            <label for="with_courier" class="form-check-label">Dostavka</label>
                            <input class="form-check-input courier_check" type="checkbox" name="with_courier" id="with_courier">
                        </div>
                        <input type="number" class="form-control courier_price mt-2" name="courier_price" style="display: none;" placeholder="Dostavka narxi" autocomplete="off">
                    @endif
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button type="button" class="btn btn-sm btn-outline-success mt-4 jamb_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-warning mt-4 jamb_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                </div>
              </div>
              <div class="row mt-4 jamb_div justify-content-end" data-index="0">
                <div class="mb-3 col-md-3">
                    <label for="jamb" class="form-label">Nalichnik turi</label>
                    <select class="form-select" name="jamb_id[]" id="jamb">
                        <option value=""></option>
                        @foreach($jambs as $key => $value)
                            @if ($jamb_results[0]->name == $value->name . '(' . $value->height . 'x' . $value->width . ')')
                                <option value="{{ $value->id }}" selected>{{ $value->name }}({{ $value->height }}x{{ $value->width }})</option>
                            @else
                                <option value="{{ $value->id }}">{{ $value->name }}({{ $value->height }}x{{ $value->width }})</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="mb-3 col-md-1">
                    <label for="count" class="form-label">Soni</label>
                    <input class="form-control" type="number" name="count[]" id="count" autocomplete="off" value="{{ $jamb_results[0]->count }}">
                </div>
              </div>
              @for($i = 1; $i < count($jamb_results); $i++)
                <div class="row jamb_div justify-content-end" data-index="{{ $i }}">
                    <div class="mb-3 col-md-3">
                        <select class="form-select" name="jamb_id[]" id="jamb">
                            <option value=""></option>
                            @foreach($jambs as $key => $value)
                                @if ($jamb_results[$i]->name == $value->name . '(' . $value->height . 'x' . $value->width . ')')
                                    <option value="{{ $value->id }}" selected>{{ $value->name }}({{ $value->height }}x{{ $value->width }})</option>
                                @else
                                    <option value="{{ $value->id }}">{{ $value->name }}({{ $value->height }}x{{ $value->width }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-1">
                        <input class="form-control" type="number" name="count[]" id="count" autocomplete="off" value="{{ $jamb_results[$i]->count }}">
                    </div>
                </div>
              @endfor
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-md-10">
                  <textarea name="comments" class="form-control" rows="10" placeholder="Izoh qoldiring...">{{ $order->comments }}</textarea>
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary me-2 float-end">Saqlash</button>
                </div>  
              </div>
            </div>
          </form>
        </div>

        <!-- Labelsiz nalichnik parametrlari -->
        <div class="row mt-4 jamb_div_without_labels" style="display:none;">
            <div class="mb-3 col-md-3">
                <select class="form-select" name="jamb_id[]" id="depth">
                    <option value=""></option>
                    @foreach($jambs as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}({{ $value->height }}x{{ $value->width }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-1">
                <input class="form-control" type="number" name="count[]" id="count" autocomplete="off" />
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
      let i = $('.jambs_content .jamb_div').length;

      $('body').on('click', '.jamb_plus', function(){
        let targetDiv = document.getElementsByClassName("jamb_div_without_labels")[0];
        $(".jambs_content").last().append('<div class="row jamb_div justify-content-end">'+targetDiv.innerHTML+'</div>');
        i++;
      });

      $('body').on('click', '.jamb_minus', function(){
        if(i>1){
          let childDiv = $('body').find('.jambs_content .row').last();
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