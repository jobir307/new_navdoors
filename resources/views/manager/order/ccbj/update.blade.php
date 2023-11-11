@extends('layouts.manager')
@section('content')
  <div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('orders') }}" class="fw-light">Shartnomalar / </a><span class="fw-light">Shartnoma ma'lumotlarini o'zgartirish(nalichnik, korona, kubik va sapog)</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <form method="POST"  action="{{ route('order-ccbjs.update', $order->id) }}">
            @method('PUT')
            @csrf
            <div class="card-body">
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
                <div class="mb-3 col-md-1">
                  <label for="contract_number" class="form-label">Shartnoma raqami</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="text" name="contract_number" id="contract_number" autocomplete="off" value="{{ $order->contract_number }}" />
                </div>
                <div class="mb-3 col-md-1">
                  <label for="jamb_color" class="form-label">Nalichnik rangi</label>
                  <input class="form-control" type="text" name="jamb_color" id="jamb_color" autocomplete="off" value="{{ $jamb_results[0]->jamb_color ?? '' }}" />
                </div>
                <div class="mb-3 col-md-1">
                  <label for="crown_color" class="form-label">Korona rangi</label>
                  <input class="form-control" type="text" name="crown_color" id="crown_color" autocomplete="off" value="{{ $crown_results[0]->crown_color ?? '' }}" />
                </div>
                <div class="mb-3 col-md-1">
                  <label for="cube_color" class="form-label">Kubik rangi</label>
                  <input class="form-control" type="text" name="cube_color" id="cube_color" autocomplete="off" value="{{ $cube_results[0]->cube_color ?? '' }}" />
                </div>
                <div class="mb-3 col-md-1">
                  <label for="boot_color" class="form-label">Sapog rangi</label>
                  <input class="form-control" type="text" name="boot_color" id="boot_color" autocomplete="off" value="{{ $boot_results[0]->boot_color ?? '' }}" />
                </div>
                <div class="mb-3 col-md-1">
                  <label for="deadline" class="form-label">Topshirish muddati</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="date" name="deadline" id="deadline" autocomplete="off" value="{{ $order->deadline }}" />
                </div>
                <div class="mb-3 col-md-1">
                    @if($order->with_installation == 1) 
                        <div class="form-check">
                            <label for="with_installation" class="form-check-label">Ustanovka</label>
                            <input class="form-check-input installation_check" type="checkbox" name="with_installation" id="with_installation" checked>
                        </div>
                        <input type="text" class="form-control installation_price mt-2" name="installation_price" style="display: block;" placeholder="Ustanovka narxi" autocomplete="off" value="{{ $order->installation_price }}">
                    @else
                        <div class="form-check">
                            <label for="with_installation" class="form-check-label">Ustanovka</label>
                            <input class="form-check-input installation_check" type="checkbox" name="with_installation" id="with_installation">
                        </div>
                        <input type="text" class="form-control installation_price mt-2" name="installation_price" style="display: none;" placeholder="Ustanovka narxi" autocomplete="off">
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
                <div class="col-md-3">
                  <button type="button" class="btn btn-sm btn-outline-primary mt-4 crown_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-secondary mt-4 crown_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                </div>
                <div class="col-md-3">
                  <button type="button" class="btn btn-sm btn-outline-success mt-4 cube_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-warning mt-4 cube_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                </div>
                <div class="col-md-3">
                  <button type="button" class="btn btn-sm btn-outline-primary mt-4 boot_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-danger mt-4 boot_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                </div>
                <div class="col-md-3">
                  <button type="button" class="btn btn-sm btn-outline-secondary mt-4 jamb_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-success mt-4 jamb_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                </div>
              </div>
                <div class="row">
                    <div class="col-md-3 crowns_content">
                        <div class="row mt-4 crown_div" data-index="0">
                            <div class="mb-3 col-md-6">
                                <label for="crown" class="form-label">Korona turi</label>
                                <select class="form-select" name="crown_id[]" id="crown">
                                    <option value=""></option>
                                    @foreach($crowns as $key => $value)
                                        @if (!empty($crown_results) && $crown_results[0]->crown_id == $value->id)
                                            <option value="{{ $value->id }}" selected>{{ $value->name }}({{ $value->len }})</option>
                                        @else
                                            <option value="{{ $value->id }}">{{ $value->name }}({{ $value->len }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="door_width" class="form-label">Eshik eni</label>
                                <input class="form-control" type="number" name="door_width[]" id="door_width" value="{{ $crown_results[0]->door_width ?? '' }}" autocomplete="off" />
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="count" class="form-label">Soni</label>
                                <input class="form-control" type="number" name="crown_count[]" id="count" autocomplete="off" value="{{ $crown_results[0]->count ?? '' }}" />
                            </div>
                        </div>
                        @if(!empty($crown_results))
                            @for($i = 1; $i < count($crown_results); $i++)
                                <div class="row crown_div" data-index="{{ $i }}">
                                    <div class="mb-3 col-md-6">
                                        <select class="form-select" name="crown_id[]" id="crown">
                                            <option value=""></option>
                                            @foreach($crowns as $key => $value)
                                                @if ($crown_results[$i]->crown_id == $value->id)
                                                    <option value="{{ $value->id }}" selected>{{ $value->name }}({{ $value->len }})</option>
                                                @else
                                                    <option value="{{ $value->id }}">{{ $value->name }}({{ $value->len }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <input class="form-control" type="number" name="door_width[]" id="door_width" value="{{ $crown_results[$i]->door_width }}" autocomplete="off" />
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <input class="form-control" type="number" name="crown_count[]" id="count" autocomplete="off" value="{{ $crown_results[$i]->count }}" />
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>
                    <div class="col-md-3 cubes_content">
                        <div class="row mt-4 cube_div" data-index="0">
                            <div class="mb-3 col-md-9">
                                <label for="cube" class="form-label">Kubik turi</label>
                                <select class="form-select" name="cube_id[]" id="cube">
                                    <option value=""></option>
                                    @foreach($cubes as $key => $value)
                                        @if (!empty($cube_results) && $cube_results[0]->cube_id == $value->id)
                                            <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                                        @else
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="count" class="form-label">Soni</label>
                                <input class="form-control" type="number" name="cube_count[]" id="count" autocomplete="off" value="{{ $cube_results[0]->count ?? '' }}" />
                            </div>
                        </div>
                        @if (!empty($cube_results))
                            @for($i = 1; $i < count($cube_results); $i++)
                                <div class="row cube_div" data-index="{{ $i }}">
                                    <div class="mb-3 col-md-9">
                                        <select class="form-select" name="cube_id[]" id="cube">
                                            <option value=""></option>
                                            @foreach($cubes as $key => $value)
                                                @if ($cube_results[$i]->cube_id == $value->id)
                                                    <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                                                @else
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <input class="form-control" type="number" name="cube_count[]" id="count" autocomplete="off" value="{{ $cube_results[$i]->count }}" />
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>
                    <div class="col-md-3 boots_content">
                        <div class="row mt-4 boot_div" data-index="0">
                            <div class="mb-3 col-md-9">
                                <label for="boot" class="form-label">Sapog turi</label>
                                <select class="form-select" name="boot_id[]" id="boot">
                                    <option value=""></option>
                                    @foreach($boots as $key => $value)
                                        @if (!empty($boot_results) && $boot_results[0]->boot_id == $value->id)
                                            <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                                        @else
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="count" class="form-label">Soni</label>
                                <input class="form-control" type="number" name="boot_count[]" id="count" autocomplete="off" value="{{ $boot_results[0]->count ?? '' }}" />
                            </div>
                        </div>
                        @if (!empty($boot_results))
                            @for($i = 1; $i < count($boot_results); $i++)
                                <div class="row boot_div" data-index="{{ $i }}">
                                    <div class="mb-3 col-md-9">
                                        <select class="form-select" name="boot_id[]" id="boot">
                                            <option value=""></option>
                                            @foreach($boots as $key => $value)
                                                @if ($boot_results[$i]->boot_id == $value->id)
                                                    <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                                                @else
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <input class="form-control" type="number" name="boot_count[]" id="count" autocomplete="off" value="{{ $boot_results[$i]->count }}" />
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>
                    <div class="col-md-3 jambs_content">
                        <div class="row mt-4 jamb_div" data-index="0">
                            <div class="mb-3 col-md-9">
                                <label for="jamb" class="form-label">Nalichnik turi</label>
                                <select class="form-select" name="jamb_id[]" id="jamb">
                                    <option value=""></option>
                                    @foreach($jambs as $key => $value)
                                        @if (!empty($jamb_results) && $jamb_results[0]->jamb_id == $value->id)
                                            <option value="{{ $value->id }}" selected>{{ $value->name }}({{ $value->height }}x{{ $value->width }})</option>
                                        @else
                                            <option value="{{ $value->id }}">{{ $value->name }}({{ $value->height }}x{{ $value->width }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="count" class="form-label">Soni</label>
                                <input class="form-control" type="number" name="jamb_count[]" id="count" autocomplete="off" value="{{ $jamb_results[0]->count ?? '' }}" />
                            </div>
                        </div>
                        @if (!empty($jamb_results))
                            @for($i = 1; $i < count($jamb_results); $i++)
                                <div class="row jamb_div" data-index="{{ $i }}">
                                    <div class="mb-3 col-md-9">
                                        <select class="form-select" name="jamb_id[]" id="jamb">
                                            <option value=""></option>
                                            @foreach($jambs as $key => $value)
                                                @if ($jamb_results[$i]->jamb_id == $value->id)
                                                    <option value="{{ $value->id }}" selected>{{ $value->name }}({{ $value->height }}x{{ $value->width }})</option>
                                                @else
                                                    <option value="{{ $value->id }}">{{ $value->name }}({{ $value->height }}x{{ $value->width }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <input class="form-control" type="number" name="jamb_count[]" id="count" autocomplete="off" value="{{ $jamb_results[$i]->count }}" />
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>
                </div>
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

        <!-- Labelsiz korona parametrlari -->
        <div class="row mt-4 crown_div_without_labels" style="display:none;">
            <div class="mb-3 col-md-6">
                <select class="form-select" name="crown_id[]">
                    <option value=""></option>
                    @foreach($crowns as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}({{ $value->len }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-3">
                <input class="form-control" type="number" name="door_width[]" autocomplete="off" />
            </div>
            <div class="mb-3 col-md-3">
                <input class="form-control" type="number" name="crown_count[]" autocomplete="off" />
            </div>
        </div>

        <!-- Labelsiz kubik parametrlari -->
        <div class="row mt-4 cube_div_without_labels" style="display:none;">
            <div class="mb-3 col-md-9">
                <select class="form-select" name="cube_id[]" id="cube">
                    <option value=""></option>
                    @foreach($cubes as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-3">
                <input class="form-control" type="number" name="cube_count[]" id="count" autocomplete="off" />
            </div>
        </div>

        <!-- Labelsiz sapog parametrlari -->
        <div class="row mt-4 boot_div_without_labels" style="display:none;">
            <div class="mb-3 col-md-9">
                <select class="form-select" name="boot_id[]" id="cube">
                    <option value=""></option>
                    @foreach($boots as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-3">
                <input class="form-control" type="number" name="boot_count[]" id="count" autocomplete="off" />
            </div>
        </div>

        <!-- Labelsiz nalichnik parametrlari -->
        <div class="row mt-4 jamb_div_without_labels" style="display:none;">
            <div class="mb-3 col-md-9">
                <select class="form-select" name="jamb_id[]" id="jamb">
                    <option value=""></option>
                    @foreach($jambs as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}({{ $value->height }}x{{ $value->width }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-3">
                <input class="form-control" type="number" name="jamb_count[]" id="count" autocomplete="off" />
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
      let i = $(".crown_div").length;
      $('body').on('click', '.crown_plus', function(){
        let targetDiv = document.getElementsByClassName("crown_div_without_labels")[0];
        $(".crowns_content").last().append('<div class="row crown_div justify-content-end">'+targetDiv.innerHTML+'</div>');
        i++;
      });

      $('body').on('click', '.crown_minus', function(){
        if(i>1){
          let childDiv = $('body').find('.crowns_content .row').last();
          childDiv.remove();
          i--;
        }
      });

      let j = $(".cube_div").length;
      $('body').on('click', '.cube_plus', function(){
        let targetDiv = document.getElementsByClassName("cube_div_without_labels")[0];
        $(".cubes_content").last().append('<div class="row cube_div justify-content-end">'+targetDiv.innerHTML+'</div>');
        j++;
      });

      $('body').on('click', '.cube_minus', function(){
        if(j>1){
          let childDiv = $('body').find('.cubes_content .row').last();
          childDiv.remove();
          j--;
        }
      });

      let k = $(".boot_div").length;
      $('body').on('click', '.boot_plus', function(){
        let targetDiv = document.getElementsByClassName("boot_div_without_labels")[0];
        $(".boots_content").last().append('<div class="row boot_div justify-content-end">'+targetDiv.innerHTML+'</div>');
        k++;
      });

      $('body').on('click', '.boot_minus', function(){
        if(k>1){
          let childDiv = $('body').find('.boots_content .row').last();
          childDiv.remove();
          k--;
        }
      });

      let l = $(".jamb_div").length;
      $('body').on('click', '.jamb_plus', function(){
        let targetDiv = document.getElementsByClassName("jamb_div_without_labels")[0];
        $(".jambs_content").last().append('<div class="row jamb_div justify-content-end">'+targetDiv.innerHTML+'</div>');
        l++;
      });

      $('body').on('click', '.jamb_minus', function(){
        if(l>1){
          let childDiv = $('body').find('.jambs_content .row').last();
          childDiv.remove();
          l--;
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