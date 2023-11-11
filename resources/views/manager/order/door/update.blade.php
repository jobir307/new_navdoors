@extends('layouts.manager')
<style type="text/css">
  .jamb_canvas {
    width: 500px !important;
  }
</style>
@section('content')
  <div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('orders') }}" class="fw-light">Shartnomalar / </a><span class="fw-light">Shartnoma ma'lumotlarini o'zgartirish(eshik)</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <form method="POST"  action="{{ route('order-doors.update', $order->id) }}">
            @method('PUT')
            @csrf
            <div class="card-body doors_content">
              <div class="row">
                <div class="mb-3 col-md-4">
                  <label for="doortype" class="form-label">Eshik turi</label>
                  <select class="form-control js-example-basic-single" id="doortype" name="doortype">
                    @foreach($doortypes as $key => $value)
                      @if($value->id == $door->doortype_id)
                        <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                      @else
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
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
                      <select class="form-control js-example-basic-single" id="diler" name="dealer" style="width:100%;">
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
                <div class="mb-3 col-md-4">
                  <label for="contract_number" class="form-label">Shartnoma raqami</label>
                  <input class="form-control" type="text" name="contract_number" id="contract_number" autocomplete="off" value="{{ $order->contract_number }}">
                </div>
              </div>
              <div class="row">
                <div class="mb-3 col-md-3">
                  <label for="ornament_model" class="form-label">Naqsh modeli</label>
                  <input class="form-control" type="text" name="ornament_model" id="ornament_model" autocomplete="off" value="{{ $door->ornament_model }}">
                </div>
                <div class="mb-3 col-md-3">
                  <label for="door_color" class="form-label">Eshik rangi</label>
                  <input class="form-control" type="text" name="door_color" id="door_color" autocomplete="off" value="{{ $door->door_color }}">
                </div>
                <div class="mb-3 col-md-2">
                  <label for="deadline" class="form-label">Topshirish muddati</label>
                  <input class="form-control" type="date" name="deadline" id="deadline" autocomplete="off" value="{{ $order->deadline }}">
                </div>
                <div class="mb-3 col-md-2">
                  @if($order->with_installation == 1) 
                    <div class="form-check">
                        <label for="with_installation" class="form-check-label">Ustanovka</label>
                        <input class="form-check-input installation_check" type="checkbox" name="with_installation" id="with_installation" checked>
                    </div>
                    <input type="text" class="form-control installation_price mt-2" name="door_installation_price" style="display: block;" placeholder="Ustanovka narxi" autocomplete="off" value="{{ $order->installation_price / $layer_sum }}">
                  @else
                    <div class="form-check">
                      <label for="with_installation" class="form-check-label">Ustanovka</label>
                      <input class="form-check-input installation_check" type="checkbox" name="with_installation" id="with_installation">
                    </div>
                    <input type="text" class="form-control installation_price mt-2" name="door_installation_price" style="display: none;" placeholder="Ustanovka narxi" autocomplete="off">
                  @endif
                </div>
                <div class="mb-3 col-md-2">
                  <div class="form-check">
                    <label for="with_courier" class="form-check-label">Dostavka</label>
                    <input class="form-check-input courier_check" type="checkbox" name="with_courier" id="with_courier" autocomplete="off" {{ $order->with_courier ? "checked" : "" }} />
                  </div>
                  <input type="number" class="form-control courier_price mt-2" name="courier_price" value="{{ $order->courier_price }}" style="display: none;" placeholder="Dostavka narxi">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <button type="button" class="btn btn-sm btn-outline-success mt-4 door_parameters_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                  <button type="button" class="btn btn-sm btn-outline-warning mt-4 door_parameters_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                </div>
              </div>
              <div class="row mt-4 door_parameters_div" data-index="0">
                <div class="mb-3 col-lg-1" style="max-width: 121px;">
                  <label for="height" class="form-label">Bo'yi</label>
                  <input class="form-control" type="text" name="height[]" id="height" autocomplete="off" value="{{ $door_parameters[0]['height'] }}">
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="width" class="form-label">Eni</label>
                  <input class="form-control" type="text" name="width[]" id="width" autocomplete="off" value="{{ $door_parameters[0]['width'] }}">
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="count" class="form-label">Soni</label>
                  <input class="form-control" type="number" name="count[]" id="count" autocomplete="off" value="{{ $door_parameters[0]['count'] }}">
                </div>
                <div class="mb-3 col-md-1" style="max-width: 115px;">
                  <label for="l_p" class="form-label">L-P</label>
                  <?php 
                    $lps = ['' => '', 'l' => 'L', 'p' => 'P', 'l-p' => 'L-P'];
                  ?>
                  <select class="form-select" id="l_p" name="l_p[]">
                    @foreach ($lps as $key => $value)
                      @if ($door_parameters[0]['l_p'] == $key)
                        <option value="{{ $key }}" selected>{{ $value }}</option>
                      @else
                        <option value="{{ $key }}">{{ $value }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="wall_thickness" class="form-label">Devor qalinligi</label>
                  <input class="form-control" type="text" name="wall_thickness[]" id="wall_thickness" autocomplete="off" value="{{ $door_parameters[0]['wall_thickness'] }}">
                </div>
                <div class="mb-3 col-md-1" style="max-width: 123px;">
                  <label for="box_size" class="form-label">Karobka o'lchami</label>
                  <input class="form-control" type="text" name="box_size[]" id="box_size" autocomplete="off" value="{{ $door_parameters[0]['box_size'] }}">
                </div>
                <div class="mb-3 col-md-1" style="max-width: 123px;">
                  <label for="depth" class="form-label">Karobka qalinligi</label>
                  <select class="form-select" name="depth_id[]" id="depth">
                    @foreach($depths as $key => $value)
                      @if($door_parameters[0]['depth'] == $value->name)
                        <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                      @else
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="transom" class="form-label">Dobor</label>
                  <select class="form-select" id="transom" name="transom_side[]">
                    <option></option>
                    <?php $transoms = [1, 2, '1 oldidan']; ?>
                    @foreach($transoms as $value)
                      @if($door_parameters[0]['transom_side'] == $value)
                        <option value="{{ $value }}" selected>{{ $value }}</option>
                      @else
                        <option value="{{ $value }}">{{ $value }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="layer" class="form-label">Tabaqaligi</label>
                  <select class="form-select" id="layer" name="layer_id[]">
                    @foreach($layers as $key => $value)
                      @if($door_parameters[0]['layer'] == $value->name)
                        <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                      @else
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="doorstep" class="form-label">Porog</label>
                  <?php 
                    $doorsteps = ['bez' => 'Bez', 'parogli' => 'Parogli'];
                  ?>
                  <select class="form-select" id="doorstep" name="doorstep[]">

                    @foreach($doorsteps as $key => $value)
                      @if ($key == $door_parameters[0]['doorstep'])
                        <option value="{{ $key }}" selected>{{ $value }}</option>
                      @else
                        <option value="{{ $key }}">{{ $value }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="ornament" class="form-label">Naqsh shakli</label>
                  <select class="form-select" id="ornament" name="ornament_id[]">
                    <option value=""></option>
                    @foreach($ornamenttypes as $key => $value)
                      @if ($value->name == $door_parameters[0]['ornamenttype'])
                        <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                      @else
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endif
                    @endforeach
                  </select>
                  <input type="hidden" name="hidden_glasstype_id[]" class="form-control">
                  <input type="hidden" name="hidden_glassfigure_id[]" class="form-control">
                  <input type="hidden" name="hidden_glass_count[]" class="form-control">
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="locktype" class="form-label">Qulf turi</label>
                  <select class="form-select" id="locktype" name="locktype_id[]">
                    <option value=""></option>
                    @foreach($locktypes as $key => $value)
                      @if ($value->name == $door_parameters[0]['locktype'])
                        <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                      @else
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label class="form-label" for="loop">Chaspak</label>
                  <button
                    class="btn btn-outline-info chaspak_btn"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasChaspak"
                    aria-controls="offcanvasChaspak"
                  >
                    Tanlash
                  </button>
                  <input type="hidden" name="hidden_loop_id[]" class="form-control">
                  <input type="hidden" name="hidden_loop_count[]" class="form-control">
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="jamb" class="form-label">NKKS</label>
                  <button
                    id="loop"
                    class="btn btn-outline-success jamb_btn"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasJamb"
                    aria-controls="offcanvasJamb"
                  >
                    Tanlash
                  </button>
                  <input type="hidden" name="hidden_jamb_name[]" class="form-control">
                  <input type="hidden" name="hidden_jamb_side[]" class="form-control">

                  <input type="hidden" name="hidden_crown_id[]" class="form-control">
                  <input type="hidden" name="hidden_crown_side[]" class="form-control">

                  <input type="hidden" name="hidden_cube_name[]" class="form-control">
                  <input type="hidden" name="hidden_cube_side[]" class="form-control">
                </div>
                <div class="mb-3 col-md-1" style="max-width: 125px;">
                  <label class="form-label" for="framoga_btn">Framoga</label>
                  <button class="btn btn-outline-secondary" type="button" id="framoga_btn">Tanlash</button>
                  <input type="hidden" name="hidden_framoga_type[]" class="form-control">
                  <input type="hidden" name="hidden_framoga_figure[]" class="form-control">
                </div>
              </div>

              @for($i=1; $i < count($door_parameters); $i++)
                <div class="row door_parameters_div" data-index="{{ $i }}">
                  <div class="mb-3 col-lg-1" style="max-width: 121px;">
                    <input class="form-control" type="text" name="height[]" id="height" autocomplete="off" value="{{ $door_parameters[$i]['height'] }}">
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 121px;">
                    <input class="form-control" type="text" name="width[]" id="width" autocomplete="off" value="{{ $door_parameters[$i]['width'] }}">
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 121px;">
                    <input class="form-control" type="number" name="count[]" id="count" autocomplete="off" value="{{ $door_parameters[$i]['count'] }}">
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 115px;">
                    <?php 
                      $lps = ['' => '', 'l' => 'L', 'p' => 'P', 'l-p' => 'L-P'];
                    ?>
                    <select class="form-select" id="l_p" name="l_p[]">
                      @foreach ($lps as $key => $value)
                        @if ($door_parameters[$i]['l_p'] == $key)
                          <option value="{{ $key }}" selected>{{ $value }}</option>
                        @else
                          <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 121px;">
                    <input class="form-control" type="text" name="wall_thickness[]" id="wall_thickness" autocomplete="off" value="{{ $door_parameters[$i]['wall_thickness'] }}">
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 123px;">
                    <input class="form-control" type="text" name="box_size[]" id="box_size" autocomplete="off" value="{{ $door_parameters[$i]['box_size'] }}">
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 123px;">
                    <select class="form-select" name="depth_id[]" id="depth">
                      @foreach($depths as $key => $value)
                        @if($door_parameters[$i]['depth'] == $value->name)
                          <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                        @else
                          <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 121px;">
                    <select class="form-select" id="transom" name="transom_side[]">
                      <option></option>
                      <?php $transoms = [1, 2, '1 oldidan']; ?>
                      @foreach($transoms as $value)
                        @if(isset($door_parameters[$i]['transom_side']) && $door_parameters[$i]['transom_side'] == $value)
                          <option value="{{ $value }}" selected>{{ $value }}</option>
                        @else
                          <option value="{{ $value }}">{{ $value }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 121px;">
                    <select class="form-select" id="layer" name="layer_id[]">
                      @foreach($layers as $key => $value)
                        @if($door_parameters[$i]['layer'] == $value->name)
                          <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                        @else
                          <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 121px;">
                    <?php 
                      $doorsteps = ['bez' => 'Bez', 'parogli' => 'Parogli'];
                    ?>
                    <select class="form-select" id="doorstep" name="doorstep[]">

                      @foreach($doorsteps as $key => $value)
                        @if ($key == $door_parameters[$i]['doorstep'])
                          <option value="{{ $key }}" selected>{{ $value }}</option>
                        @else
                          <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 121px;">
                    <select class="form-select" id="ornament" name="ornament_id[]">
                      <option value=""></option>
                      @foreach($ornamenttypes as $key => $value)
                        @if ($value->name == $door_parameters[$i]['ornamenttype'])
                          <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                        @else
                          <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endif
                      @endforeach
                    </select>
                    <input type="hidden" name="hidden_glasstype_id[]" class="form-control">
                    <input type="hidden" name="hidden_glassfigure_id[]" class="form-control">
                    <input type="hidden" name="hidden_glass_count[]" class="form-control">
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 121px;">
                    <select class="form-select" id="locktype" name="locktype_id[]">
                      <option value=""></option>
                      @foreach($locktypes as $key => $value)
                        @if ($value->name == $door_parameters[$i]['locktype'])
                          <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                        @else
                          <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 121px;">
                    <button
                      class="btn btn-outline-info chaspak_btn"
                      type="button"
                      data-bs-toggle="offcanvas"
                      data-bs-target="#offcanvasChaspak"
                      aria-controls="offcanvasChaspak"
                    >
                      Tanlash
                    </button>
                    <input type="hidden" name="hidden_loop_id[]" class="form-control">
                    <input type="hidden" name="hidden_loop_count[]" class="form-control">
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 121px;">
                    <button
                      id="loop"
                      class="btn btn-outline-success jamb_btn"
                      type="button"
                      data-bs-toggle="offcanvas"
                      data-bs-target="#offcanvasJamb"
                      aria-controls="offcanvasJamb"
                    >
                      Tanlash
                    </button>
                    <input type="hidden" name="hidden_jamb_name[]" class="form-control">
                    <input type="hidden" name="hidden_jamb_side[]" class="form-control">

                    <input type="hidden" name="hidden_crown_id[]" class="form-control">
                    <input type="hidden" name="hidden_crown_side[]" class="form-control">

                    <input type="hidden" name="hidden_cube_name[]" class="form-control">
                    <input type="hidden" name="hidden_cube_side[]" class="form-control">
                  </div>
                  <div class="mb-3 col-md-1" style="max-width: 125px;">
                    <button class="btn btn-outline-secondary" type="button" id="framoga_btn">Tanlash</button>
                    <input type="hidden" name="hidden_framoga_type[]" class="form-control">
                    <input type="hidden" name="hidden_framoga_figure[]" class="form-control">
                  </div>
                </div>
              @endfor
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

        <!-- Labelsiz eshik parametrlari -->
        <div class="row mt-4 door_parameters_without_labels" style="display:none;">
          <div class="mb-3 col-lg-1" style="max-width: 121px;">
            <input class="form-control" type="text" name="height[]" id="height" autocomplete="off" />
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <input class="form-control" type="text" name="width[]" id="width" autocomplete="off" />
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <input class="form-control" type="number" name="count[]" id="count" autocomplete="off" />
          </div>
          <div class="mb-3 col-md-1" style="max-width: 115px;">
            <select class="form-select" id="l_p" name="l_p[]">
              <option value=""></option>
              <option value="l">L</option>
              <option value="p">P</option>
            </select>
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <input class="form-control" type="text" name="wall_thickness[]" id="wall_thickness" autocomplete="off" />
          </div>
          <div class="mb-3 col-md-1" style="max-width: 123px;">
            <input class="form-control" type="text" name="box_size[]" id="box_size" autocomplete="off" />
          </div>
          <div class="mb-3 col-md-1" style="max-width: 123px;">
            <select class="form-select" name="depth_id[]" id="depth">
              <option value=""></option>
              @foreach($depths as $key => $value)
                <option value="{{ $value->id }}">{{ $value->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <select class="form-select" id="transom" name="transom_side[]">
              <option></option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="1 oldidan">1 oldidan</option>
            </select>
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <select class="form-select" id="layer" name="layer_id[]">
              <option value=""></option>
              @foreach($layers as $key => $value)
                <option value="{{ $value->id }}">{{ $value->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <select class="form-select" id="doorstep" name="doorstep[]">
              <option value=""></option>
              <option value="bez">Bez</option>
              <option value="parogli">Parogli</option>
            </select>
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <select class="form-select" id="ornament" name="ornament_id[]">
              <option value=""></option>
              @foreach($ornamenttypes as $key => $value)
                <option value="{{ $value->id }}">{{ $value->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <select class="form-select" id="locktype" name="locktype_id[]">
              <option value=""></option>
              @foreach($locktypes as $key => $value)
                <option value="{{ $value->id }}">{{ $value->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <button
              class="btn btn-outline-info chaspak_btn"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasChaspak"
              aria-controls="offcanvasChaspak"
            >
              Tanlash
            </button>
            <input type="hidden" name="hidden_loop_id[]" class="form-control">
            <input type="hidden" name="hidden_loop_count[]" class="form-control">
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <button
              id="loop"
              class="btn btn-outline-success jamb_btn"
              type="button"
              data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasJamb"
              aria-controls="offcanvasJamb"
            >
              Tanlash
            </button>
            <input type="hidden" name="hidden_jamb_name[]" class="form-control">
            <input type="hidden" name="hidden_jamb_side[]" class="form-control">

            <input type="hidden" name="hidden_crown_id[]" class="form-control">
            <input type="hidden" name="hidden_crown_side[]" class="form-control">

            <input type="hidden" name="hidden_cube_name[]" class="form-control">
            <input type="hidden" name="hidden_cube_side[]" class="form-control">
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <button class="btn btn-outline-secondary" type="button" id="framoga_btn">Tanlash</button>
            <input type="hidden" name="hidden_framoga_type[]" class="form-control">
            <input type="hidden" name="hidden_framoga_figure[]" class="form-control">
          </div>
        </div>

        <!-- Framoga -->
        <div class="modal fade" id="modalframoga" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="framoga_type">Framoga turi</label>
                    <select class="form-select framoga_type" id="framoga_type" name="framogatype_id">
                      <option value=""></option>
                      @foreach($framogatypes as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="framoga_figure">Framoga shakli</label>
                    <select class="form-select framoga_figure" id="framoga_figure" name="framogafigure_id">
                      <option value=""></option>
                      @foreach($framogafigures as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Yopish</button>
                <button type="button" class="btn btn-primary save_framoga">Saqlash</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Chaspak -->
        <div
          class="offcanvas offcanvas-end"
          data-bs-scroll="true"
          data-bs-backdrop="false"
          tabindex="-1"
          id="offcanvasChaspak"
          aria-labelledby="offcanvasChaspakLabel"
        >
          <div class="offcanvas-header">
            <h5 id="offcanvasChaspakLabel" class="offcanvas-title">Chaspak tanlash</h5>
            <button
              type="button"
              class="btn-close text-reset"
              data-bs-dismiss="offcanvas"
              aria-label="Close"
            ></button>
          </div>
          <div class="offcanvas-body">
            <div class="row mb-5">
              <div class="col-md-8">
                <label class="form-label" for="loop_id">Chaspak turi</label>
                <select class="form-select loop_select" id="loop_id" name="loop_id">
                  <option value=""></option>
                  @foreach($loops as $key => $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="loop_count">Soni</label>
                <select class="form-select loop_count_select" id="loop_count" name="loop_count">
                  <option value="2" selected>2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                </select>
              </div>
            </div>
          </div>
          <div class="offcanvas-footer">
            <button type="button" class="btn btn-primary mb-2 d-grid w-100 loop_save">Saqlash</button>
            <button
              type="button"
              class="btn btn-outline-secondary d-grid w-100"
              data-bs-dismiss="offcanvas"
            >
              Yopish
            </button>
          </div>
        </div>

        <!-- Nalichnik -->
        <div
          class="offcanvas offcanvas-end jamb_canvas"
          data-bs-scroll="true"
          data-bs-backdrop="false"
          tabindex="-1"
          id="offcanvasJamb"
          aria-labelledby="offcanvasJambLabel"
        >
          <div class="offcanvas-header">
            <h5 id="offcanvasJambLabel" class="offcanvas-title">NKKS tanlash</h5>
            <button
              type="button"
              class="btn-close text-reset"
              data-bs-dismiss="offcanvas"
              aria-label="Close"
            ></button>
          </div>
          <div class="offcanvas-body">
            <div class="row mt-3 mb-3">
              <div class="col-md-9">
                <label class="form-label" for="jamb_type">Nalichnik turi</label>
                <select class="form-select jamb_name" id="jamb_type" name="jamb_name[]">
                  <option></option>
                  @foreach($jambs as $key => $value)
                    <option value="{{ $value->name }}">{{ $value->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="jamb_side">Komplekt</label>
                <select name="jamb_side[]" id="jamb_side" class="form-select jamb_side">
                  <option value="0"></option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                </select>
              </div>
            </div>
            <div class="row mt-3 mb-3">
              <div class="col-md-9">
                <label class="form-label" for="crown_type">Korona</label>
                <select class="form-select crown_id" id="crown_type" name="crown_id[]">
                  <option></option>
                  @foreach($crowns as $key => $value)
                    <option value="{{ $value->id }}">{{ $value->name }}({{ $value->len }}mm)</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="crown_side">Komplekt</label>
                <select name="crown_side[]" id="crown_side" class="form-select crown_side">
                  <option value="0"></option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                </select>
              </div>
            </div>
            <div class="row mt-3 mb-3">
              <div class="col-md-9">
                <label class="form-label" for="cube_type">Kubik</label>
                <select class="form-select cube_name" id="cube_type" name="cube_name[]">
                  <option></option>
                  @foreach($cubes as $key => $value)
                    <option value="{{ $value->name }}">{{ $value->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label" for="cube_side">Komplekt</label>
                <select name="cube_side[]" id="cube_side" class="form-select cube_side">
                  <option value="0"></option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                </select>
              </div>
            </div>
          </div>
          <div class="offcanvas-footer">
            <button type="button" class="btn btn-primary mb-2 d-grid w-100 jccb_save">Saqlash</button>
            <button
              type="button"
              class="btn btn-outline-secondary d-grid w-100"
              data-bs-dismiss="offcanvas"
              id="jambCanvasClose"
            >
              Yopish
            </button>
          </div>
        </div>

        <div class="row jamb_without_labels" style="display:none;">
          <div class="col-md-9">
            <select class="form-select jamb_name" id="jamb_type" name="jamb_name[]">
              <option></option>
              @foreach($jambs as $key => $value)
                <option value="{{ $value->name }}">{{ $value->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select name="jamb_side[]" id="jamb_side" class="form-select jamb_side">
              <option value="0"></option>
              <option value="1">1</option>
              <option value="2">2</option>
            </select>
          </div>
        </div>

        <!-- Shisha parametrlari -->
        <div class="modal fade" id="glass_params_modal" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="">Shisha tanlash</h3>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-5 mb-3">
                    <label class="form-label" for="glassfigure">Shisha shakli</label>
                    <select class="form-select" id="glassfigure" name="glassfigure_id">
                      <option value="0">Tanlang</option>
                      @foreach($glass_figures as $key => $value)
                        <option data-path="{{ $value->path }}" value="{{ $value->id }}">{{ $value->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-5 mb-3">
                    <label class="form-label" for="glasstype">Shisha turi</label>
                    <select class="form-select" id="glasstype" name="glasstype_id"></select>
                  </div>
                  <div class="col-md-2 mb-3">
                    <?php $glass_counts = array(1, 2, 3, 4, 5); ?>
                    <label class="form-label" for="glasscount">Shisha soni</label>
                    <select class="form-select" id="glasscount" name="glasscount">
                      <option value="0">Tanlang</option>
                      @foreach($glass_counts as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <img src="" alt="" id="glassfigure_img" style="width: 150px; height: 400px;">
                  </div>
                  <div class="col-md-5"></div>
                  <div class="col-md-5"></div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Yopish</button>
                <button type="button" class="btn btn-primary save_glass">Saqlash</button>
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
  <script src="{{asset('assets/js/select2.min.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/menu.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/js/main.js')}}" type="text/javascript"></script>
  <script type="text/javascript">
    $(function(){
      if (document.getElementById('with_courier').checked) {
        $("input.courier_price").css("display", "block");
      } else {
        $("input.courier_price").css("display", "none");
      }
    });

    $(document).ready(function(){
      let i = 1;
      let door_parameters_div_index = 0;

      let j = $('.doors_content .door_parameters_div').length;
      $('body').on('click', '.door_parameters_plus', function(){
        let targetDiv = document.getElementsByClassName("door_parameters_without_labels")[0];
        $(".doors_content").last().append('<div class="row door_parameters_div">'+targetDiv.innerHTML+'</div>');
        $('.door_parameters_div').last().attr('data-index', j);
        j++;
      });

      $('body').on('click', '.door_parameters_minus', function() {
        if(j>1){
          let childDiv = $('body').find('.doors_content .door_parameters_div').last();
          childDiv.remove();
          j--;
        }
      });
      
      $('body').on('click', '.jamb_btn', function(){
        let index = $(this).closest(".door_parameters_div").data('index');
        door_parameters_div_index = index;
      });

      $('body').on('click', '.jccb_save', function() {
        let jamb_name = $('.jamb_name').val();
        let jamb_side = $('.jamb_side').val();

        let crown_id = $('.crown_id').val();
        let crown_side = $('.crown_side').val();

        let cube_name = $('.cube_name').val();
        let cube_side = $('.cube_side').val();

        $('.door_parameters_div').find('input[name="hidden_jamb_name[]"]').eq(door_parameters_div_index).val(jamb_name);
        $('.door_parameters_div').find('input[name="hidden_jamb_side[]"]').eq(door_parameters_div_index).val(jamb_side);

        $('.door_parameters_div').find('input[name="hidden_crown_id[]"]').eq(door_parameters_div_index).val(crown_id);
        $('.door_parameters_div').find('input[name="hidden_crown_side[]"]').eq(door_parameters_div_index).val(crown_side);

        $('.door_parameters_div').find('input[name="hidden_cube_name[]"]').eq(door_parameters_div_index).val(cube_name);
        $('.door_parameters_div').find('input[name="hidden_cube_side[]"]').eq(door_parameters_div_index).val(cube_side);

        $(".jamb_name").val("");
        $(".jamb_side").val("");

        $(".crown_id").val("");
        $(".crown_side").val("");

        $(".cube_name").val("");
        $(".cube_side").val("");

        $("#jambCanvasClose").click();
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

      $('body').on("click", "#framoga_btn", function(){
          let index = $(this).closest(".door_parameters_div").data('index');
          door_parameters_div_index = index;
          $("#modalframoga").modal("show");
      });

      $('body').on('click', '.save_framoga', function(){
        let framoga_type = $('.framoga_type').find(':selected').val();
        let framoga_figure = $('.framoga_figure').find(':selected').val();
        $('.door_parameters_div').find('input[name="hidden_framoga_type[]"]').eq(door_parameters_div_index).val(framoga_type);
        $('.door_parameters_div').find('input[name="hidden_framoga_figure[]"]').eq(door_parameters_div_index).val(framoga_figure);
        $(".framoga_type").val('');
        $(".framoga_figure").val('');
        $("#modalframoga").modal("hide");
      });

      $('body').on('click', '.chaspak_btn', function(){
        let index = $(this).closest(".door_parameters_div").data('index');
        door_parameters_div_index = index;
      });

      $('body').on('click', '.loop_save', function(){
        let loop_id = $('.loop_select').find(':selected').val();
        let loop_count = $(".loop_count_select").find(':selected').val();
        $('.door_parameters_div').find('input[name="hidden_loop_id[]"]').eq(door_parameters_div_index).val(loop_id);
        $('.door_parameters_div').find('input[name="hidden_loop_count[]"]').eq(door_parameters_div_index).val(loop_count);
        $(".loop_select").val('');
        $(".loop_count_select").val('');
        let closeCanvas = document.querySelector('[data-bs-dismiss="offcanvas"]');
        closeCanvas.click();
      });

      $('body').on('change', '.courier_check', function(){
        if ($(this).is(':checked')) {
          $("input.courier_price").css("display", "block");
        } else {
          $("input.courier_price").css("display", "none");
        }
      });

      $("body").on("change", "select#ornament", function() {
        let ornament_type = $(this).find(":selected").text();
        let index = $(this).closest(".door_parameters_div").data('index');
        door_parameters_div_index = index;
        if (ornament_type == 'Derazali'){
          $("#glass_params_modal").modal("show");
        }
      });

      $('body').on("change", "#glass_params_modal #glassfigure", function() {
        let glassfigure_id = $(this).val(), path = $(this).find(":selected").data('path');
        $("#glassfigure_img").attr("src", "{{ asset('') }}" + path);
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{ route('get-glasstypes') }}",
          method: "POST",
          data: {glassfigure_id: glassfigure_id},
          success: function(data) {
            if (data.glasstypes.length > 0) {
              let listItems = '<option selected value="0"></option>';
              for (let i = 0; i < data.glasstypes.length; i++) {
                listItems += '<option value="' + data.glasstypes[i].id + '">' + data.glasstypes[i].name + '</option>';
              }
              $("#glass_params_modal #glasstype").html(listItems);
            } else {
              $('#glass_params_modal #glasstype').html('<option value="0"></option>');
            }
          }
        }); 
      });

      $('body').on("click", ".save_glass", function() {
        let glasstype_id = $('#glass_params_modal #glasstype').find(':selected').val();
        let glassfigure_id = $('#glass_params_modal #glassfigure').find(':selected').val();
        let glass_count = $('#glass_params_modal #glasscount').find(':selected').val();
        
        $('.door_parameters_div').find('input[name="hidden_glasstype_id[]"]').eq(door_parameters_div_index).val(glasstype_id);
        $('.door_parameters_div').find('input[name="hidden_glassfigure_id[]"]').eq(door_parameters_div_index).val(glassfigure_id);
        $('.door_parameters_div').find('input[name="hidden_glass_count[]"]').eq(door_parameters_div_index).val(glass_count);
        
        $("#glass_params_modal #glasstype").val(0);
        $("#glass_params_modal #glassfigure").val(0);
        $("#glass_params_modal #glasscount").val(0);

        $("#glass_params_modal").modal("hide");
      });

      $('.js-example-basic-single').select2();
    });
  </script>
@endsection