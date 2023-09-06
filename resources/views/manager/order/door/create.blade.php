@extends('layouts.manager')
<style type="text/css">
  .jamb_canvas {
    width: 500px !important;
  }
</style>
@section('content')
  <div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('orders') }}" class="fw-light">Shartnomalar / </a><span class="text-muted fw-light">Yangi shartnoma yaratish</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
            <form method="POST"  action="{{ route('order-doors.store') }}">
            @csrf
            <div class="card-body doors_content">
              <div class="row">
                <div class="mb-3 col-md-4">
                  <label for="doortype" class="form-label">Eshik turi</label><span style="color: red; font-size: 20px;">*</span>
                  <select class="form-control js-example-basic-single" id="doortype" name="doortype">
                    @foreach($doortypes as $key => $value)
                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                  </select>
                </div>
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
                <div class="mb-3 col-md-4">
                  <label for="contract_number" class="form-label">Shartnoma raqami</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="text" name="contract_number" id="contract_number" autocomplete="off" />
                </div>
              </div>
              <div class="row">
                <div class="mb-3 col-md-3">
                  <label for="ornament_model" class="form-label">Naqsh modeli</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="text" name="ornament_model" id="ornament_model" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-3">
                  <label for="door_color" class="form-label">Eshik rangi</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="text" name="door_color" id="door_color" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-2">
                  <label for="deadline" class="form-label">Topshirish muddati</label><span style="color: red; font-size: 20px;">*</span>
                  <input class="form-control" type="date" name="deadline" id="deadline" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-2">
                  <div class="form-check">
                    <label for="with_installation" class="form-check-label">Ustanovka</label>
                    <input class="form-check-input installation_check" type="checkbox" name="with_installation" id="with_installation" autocomplete="off" />
                  </div>
                </div>
                <div class="mb-3 col-md-2">
                  <div class="form-check">
                    <label for="with_courier" class="form-check-label">Dostavka</label>
                    <input class="form-check-input courier_check" type="checkbox" name="with_courier" id="with_courier" autocomplete="off" />
                  </div>
                  <input type="number" class="form-control courier_price mt-2" name="courier_price" style="display: none;" placeholder="Dostavka narxi">
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
                  <input class="form-control" type="text" name="height[]" id="height" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="width" class="form-label">Eni</label>
                  <input class="form-control" type="text" name="width[]" id="width" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="count" class="form-label">Soni</label>
                  <input class="form-control" type="number" name="count[]" id="count" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-1" style="max-width: 115px;">
                  <label for="l_p" class="form-label">L-P</label>
                  <select class="form-select" id="l_p" name="l_p[]">
                    <option value=""></option>
                    <option value="l">L</option>
                    <option value="p">P</option>
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="wall_thickness" class="form-label">Devor qalinligi</label>
                  <input class="form-control" type="text" name="wall_thickness[]" id="wall_thickness" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-1" style="max-width: 123px;">
                  <label for="box_size" class="form-label">Karobka o'lchami</label>
                  <input class="form-control" type="text" name="box_size[]" id="box_size" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-1" style="max-width: 123px;">
                  <label for="depth" class="form-label">Karobka qalinligi</label>
                  <select class="form-select" name="depth_id[]" id="depth">
                    <option value=""></option>
                    @foreach($depths as $key => $value)
                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="transom" class="form-label">Dobor</label>
                  <select class="form-select" id="transom" name="transom_side[]">
                    <option value="0"></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="layer" class="form-label">Tabaqaligi</label>
                  <select class="form-select" id="layer" name="layer_id[]">
                    <option value=""></option>
                    @foreach($layers as $key => $value)
                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="doorstep" class="form-label">Porog</label>
                  <select class="form-select" id="doorstep" name="doorstep[]">
                    <option value=""></option>
                    <option value="bez">Bez</option>
                    <option value="parogli">Parogli</option>
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label for="ornament" class="form-label">Naqsh shakli</label>
                  <select class="form-select" id="ornament" name="ornament_id[]">
                    <option value=""></option>
                    @foreach($ornamenttypes as $key => $value)
                      <option value="{{ $value->id }}">{{ $value->name }}</option>
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
                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label class="form-label" for="loop">Chaspak</label>
                  <button
                    id="loop"
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
                  <label for="jamb" class="form-label">Nalichnik</label>
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
                  <input type="hidden" id="jamb" name="jamb[]" class="form-control" data-bs-toggle="modal" data-bs-target="#modaljamb" placeholder="Nalichnikni tanlang" autocomplete="off">
                </div>
                <div class="mb-3 col-md-1" style="max-width: 121px;">
                  <label class="form-label" for="framoga_btn">Framoga</label>
                  <button class="btn btn-outline-secondary" type="button" id="framoga_btn">Tanlash</button>
                  <input type="hidden" name="hidden_framoga_type[]" class="form-control">
                  <input type="hidden" name="hidden_framoga_figure[]" class="form-control">
                </div>
              </div>
            </div>
            <div class="card-footer float-end">
              <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">Saqlash</button>
              </div>
            </div>
          </form>
        </div>

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
              <option value="0"></option>
              <option value="1">1</option>
              <option value="2">2</option>
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
            <input type="hidden" name="hidden_glasstype_id[]" class="form-control">
            <input type="hidden" name="hidden_glassfigure_id[]" class="form-control">
            <input type="hidden" name="hidden_glass_count[]" class="form-control">
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
              id="loop"
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
            <input type="hidden" id="jamb" name="jamb[]" class="form-control" data-bs-toggle="modal" data-bs-target="#modaljamb" placeholder="Nalichnikni tanlang" autocomplete="off">
          </div>
          <div class="mb-3 col-md-1" style="max-width: 121px;">
            <button class="btn btn-outline-secondary" type="button" id="framoga_btn">Tanlash</button>
            <input type="hidden" name="hidden_framoga_type[]" class="form-control">
            <input type="hidden" name="hidden_framoga_figure[]" class="form-control">
          </div>
        </div>

        <!-- Framoga -->
        <div class="modal fade" id="modalframoga" tabindex="-1" data-bs-backdrop="static">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="">Framoga tanlash</h3>
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
        <div class="offcanvas offcanvas-end"
          data-bs-scroll="true"
          data-bs-backdrop="false"
          tabindex="-1"
          id="offcanvasChaspak"
          aria-labelledby="offcanvasChaspakLabel">
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
            <h5 id="offcanvasJambLabel" class="offcanvas-title">Nalichnik tanlash</h5>
            <button
              type="button"
              class="btn-close text-reset"
              data-bs-dismiss="offcanvas"
              aria-label="Close"
            ></button>
          </div>
          <div class="offcanvas-body jamb_content">
            <div class="row mb-3">
              <div class="col-md-12">
                <button type="button" class="btn btn-sm btn-outline-success mt-4 jamb_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                <button type="button" class="btn btn-sm btn-outline-warning mt-4 jamb_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
              </div>
            </div>
            <div class="row jamb_div mb-3">
              <div class="col-md-10">
                <label class="form-label" for="jamb_type">Nalichnik turi</label>
                <select class="form-select jamb_id" id="jamb_type" name="jamb_id[]">
                  <option value=""></option>
                  @foreach($jambs as $key => $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label" for="jamb_count">Soni</label>
                <input class="form-control jamb_count" type="text" id="jamb_count" name="jamb_count[]">
              </div>
            </div>
          </div>
          <div class="offcanvas-footer" style="margin-bottom: 20px;">
            <button type="button" class="btn btn-primary mb-2 d-grid w-100 jamb_save">Saqlash</button>
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

        <!-- Jamb without labels -->
        <div class="row jamb_without_labels mb-3" style="display: none;">
          <div class="col-md-10">
            <select class="form-select jamb_id" id="jamb_type" name="jamb_id[]">
              <option value=""></option>
              @foreach($jambs as $key => $value)
                <option value="{{ $value->id }}">{{ $value->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <input class="form-control jamb_count" type="text" id="jamb_count" name="jamb_count[]">
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
    $(document).ready(function(){
      let i = 1;
      let door_parameters_div_index = 0;

      $('body').on('click', '.jamb_plus', function(){
        let targetDiv = document.getElementsByClassName("jamb_without_labels")[0];
        $(".jamb_content").last().append('<div class="row jamb_div mb-3">'+targetDiv.innerHTML+'</div>');
        i++;
      });

      $('body').on('click', '.jamb_minus', function(){
        if(i>1){
          let childDiv = $('body').find('.jamb_content .row').last();
          childDiv.remove();
          i--;
        }
      });

      let j = 1;
      $('body').on('click', '.door_parameters_plus', function(){
        let targetDiv = document.getElementsByClassName("door_parameters_without_labels")[0];
        $(".doors_content").last().append('<div class="row door_parameters_div">'+targetDiv.innerHTML+'</div>');
        $('.door_parameters_div').last().attr('data-index', j);
        j++;
      });

      $('body').on('click', '.door_parameters_minus', function(){
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

      $('body').on('click', '.jamb_save', function() {
        let jamb_parameters = [{jamb: "", jamb_count: 0}];
        let jambs = document.getElementsByClassName("jamb_id");
        let jamb_counts = document.getElementsByName('jamb_count[]');
        for (let i = 0; i < jamb_counts.length; i++) {
          jamb_parameters[i] = {jamb: jambs[i].value, jamb_count: jamb_counts[i].value};
        }
        $('.doors_content').find('input[name="jamb[]"]').eq(door_parameters_div_index).val(JSON.stringify(jamb_parameters));
        
        let divsToRemove = document.getElementsByClassName("jamb_div");
        for (var i = divsToRemove.length-1; i > 0; i--) {
          divsToRemove[i].remove();
        }

        $(".jamb_id").val("");
        $(".jamb_count").val("");
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
        let framoga_type = document.getElementsByClassName('framoga_type').item(0);
        let framoga_figure = document.getElementsByClassName("framoga_figure").item(0);
        $('.doors_content').find('input[name="hidden_framoga_type[]"]').eq(door_parameters_div_index).val(framoga_type.value);
        $('.doors_content').find('input[name="hidden_framoga_figure[]"]').eq(door_parameters_div_index).val(framoga_figure.value);
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

      $('body').on('change', '#doortype', function(){ 
        let doortype_id = $(this).val();
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{ route('reload-jamb') }}",
          method: "POST",
          data: {doortype_id: doortype_id},
          success: function(data) {
            if (data.jambs.length > 0) {
              $.each(data.jambs, function(index, jamb) {
                $('.jamb_div select, .jamb_without_labels select').html('<option></option>');
                $('.jamb_div select, .jamb_without_labels select').append($("<option></option>")
                      .attr("value", jamb.id)
                      .text(jamb.name));
              });
            } else {
              $('.jamb_div select, .jamb_without_labels select').html('<option></option>');
            }
          }
        }); 
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
        if (ornament_type == 'Derazali'){
          let index = $(this).closest(".door_parameters_div").data('index');
          door_parameters_div_index = index;
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