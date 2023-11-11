@extends('layouts.manager')
<link rel="stylesheet" href="{{ asset('assets/css/managerConfirmOrderModal.css') }}">
@section('content')
  <div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('orders') }}" class="fw-light">Shartnomalar / </a><span class="fw-light">Shartnoma ma'lumotlarini ko'rish(nostandart nalichnik)</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <h5 class="card-header">№{{$order[0]->id}}/{{ $order[0]->contract_number }} shartnoma ma'lumotlari</h5>
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive text-nowrap m-3">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th class="text-center align-middle" rowspan=2>Buyurtmachi</th>
                      <th class="text-center align-middle" rowspan=2>Tel.raqami</th>
                      <th class="text-center align-middle" rowspan=2>Shartnoma raqami</th>
                      <th class="text-center align-middle" rowspan=2>Nalichnik rangi</th>
                      <th class="text-center align-middle" colspan=4>Summa</th>
                      <th class="text-center align-middle" rowspan=2>Muddati</th>
                      <th class="text-center align-middle" rowspan=2>Holati</th>
                    </tr>
                    <tr>
                      <th class="text-center">Umumiy narxi</th>
                      <th class="text-center">Shartnoma narxi</th>
                      <th class="text-center">To'landi</th>
                      <th class="text-center">Qoldi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>{{ $order[0]->customer }}</td>
                      <td>{{ $order[0]->phone_number }}</td>
                      <td>{{ $order[0]->contract_number }}</td>
                      <td>{{ $nsjamb_results[0]->nsjamb_color }}</td>
                      <td>{{ number_format($order[0]->contract_price, 0, ",", " ") }} so'm</td>
                      <td>{{ number_format($order[0]->last_contract_price, 0, ",", " ") }} so'm</td>
                      <td>{{ number_format($order[0]->paid, 0, ",", " ") }} so'm</td>
                      <td>{{ number_format($order[0]->last_contract_price-$order[0]->paid, 0, ",", " ") }} so'm</td>
                      <td>{{ date("d.m.Y", strtotime($order[0]->deadline)) }}</td>
                      <td>{{ $order[0]->process }}</td>
                    </tr>
                    <tr>
                      <td class="align-middle">Izoh:</td>
                      <td class="align-middle" colspan=9>{{ $order[0]->comments }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <h3 class="text-center text-primary mt-4">Nostandart nalichnik ma'lumotlari</h3>
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive text-nowrap m-3">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th class="text-center">Nomi</th>
                      <th class="text-center">Soni</th>
                      <th class="text-center">Narxi</th>
                      <th class="text-center">Summasi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($nsjamb_results as $key => $value)
                    <tr>
                      <td class="align-middle">{{ $value->nsjamb_name }}({{ $value->height }}x{{ $value->width_top }}x{{ $value->width_bottom }})</td>
                      <td class="align-middle text-center">{{ $value->count }}</td>
                      <td class="align-middle">{{ number_format($value->price, 0, ",", " ")}} so'm</td>
                      <td class="align-middle">{{ number_format($value->total_price, 0, ",", " ") }} so'm</td>
                    </tr>
                    @endforeach
                    @if ($order[0]->installation_price != 0)
                    <tr>
                      <td class="align-middle">Ustanovka</td>
                      <td class="align-middle"></td>
                      <td class="align-middle"></td>
                      <td class="align-middle">{{ number_format($order[0]->installation_price, 2, ",", " ") }} so'm</td>
                    </tr>
                    @endif
                    @if($order[0]->courier_price != 0)
                    <tr>
                      <td class="align-middle">Dostavka</td>
                      <td class="align-middle"></td>
                      <td class="align-middle"></td>
                      <td class="align-middle">{{ number_format($order[0]->courier_price, 2, ",", " ") }} so'm</td>
                    </tr>
                    @endif
                    @if($order[0]->rebate_percent != 0)
                    <tr>
                      <td class="align-middle">Chegirma</td>
                      <td class="align-middle"></td>
                      <td class="align-middle"></td>
                      <td class="align-middle">{{ $order[0]->rebate_percent }} %</td>
                    </tr>
                    @endif
                    <tr>
                      <td class="align-middle fw-bold">Oxirgi summa:</td>
                      <td class="align-middle"></td>
                      <td class="align-middle"></td>
                      <td class="align-middle fw-bold">{{ number_format($order[0]->last_contract_price, 0, ",", " ") }} so'm</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 m-3">
              @if (Auth::user()->role_id == 1 && $order[0]->status == 0)
                <button data-id="{{ $order[0]->id }}" class="btn btn-danger btn_delete" title="O'chirish">
                <i class="bx bx-trash"></i>O'chirish
                </button>
                <button data-id="{{ $order[0]->id }}" data-contract_price="{{ $order[0]->last_contract_price }}" data-courier_price="{{ $order[0]->courier_price }}" data-installation_price="{{ $order[0]->installation_price }}" class="btn btn-outline-info btn_check" title="Tasdiqlash">
                  <i class="bx bx-check"></i>Tasdiqlash
                </button>
              @endif
              <a href="{{ url('pdf-order-nsjamb', $order[0]->id) }}" class="btn btn-outline-secondary" title="Chop etish">
                <i class="bx bx-printer"></i>PDFga yuklash
              </a>
              @if ((Auth::user()->role_id == 1 || Auth::user()->id == $order[0]->who_created_userid) && $order[0]->status == 0)
                <a href="{{ route('order-jambs-transoms.edit', $order[0]->id) }}" class="btn btn-outline-primary" title="O'zgartirish">
                  <i class="bx bx-pencil"></i>Tahrirlash
                </a>
              @endif
            </div>
          </div>
        </div>

        <!-- Delete Order -->
        <div class="modal fade" id="order-delete-modal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
              <form action="{{ route('admin-delete-order') }}" method="POST">
                @csrf
                <div class="modal-header">
                  <h5 class="modal-title text-primary" id="modalCenterTitle">Shartnomani o'chirish</h5>
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
                      <h4 class="text-center text-danger">Siz haqiqatdan ham bu shartnomani o'chirmoqchimisiz?</h4>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <input type="hidden" name="order_id"  class="deleted_order_id" value="">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Yopish
                  </button>
                  <button type="submit" class="btn btn-danger">O'chirish</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Confirm Order -->
        <div class="modal fade" id="confirm-order-modal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
              <form action="{{ route('confirm-invoice') }}" method="POST">
                @csrf
                <div class="modal-header">
                  <h5 class="modal-title text-primary" id="modalCenterTitle">Tasdiqlash oynasi</h5>
                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                  ></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12 mb-3">
                      <h5>Shartnoma ma'lumotlari to'g'ri ekanligini tasdiqlayman.</h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <h3 class="text-center text-primary">Chegirmalar</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4 d-flex">
                      <label class="labl">
                          <input type="radio" name="rebate_percent" value="0" onclick="rebate(0)" />
                          <div>0%</div>
                      </label>
                      <label class="labl">
                          <input type="radio" name="rebate_percent" value="1" onclick="rebate(1)" />
                          <div>1%</div>
                      </label>
                      <label class="labl">
                          <input type="radio" name="rebate_percent" value="2" onclick="rebate(2)" />
                          <div>2%</div>
                      </label>
                      <label class="labl">
                          <input type="radio" name="rebate_percent" value="3" onclick="rebate(3)" />
                          <div>3%</div>
                      </label>
                    </div>
                    <div class="col-md-4"></div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-md-6 rebate">
                      <h4>Chegirma: <span class="text-primary">0</span> so'm</h4>
                    </div>
                    <div class="col-md-6 after_rebate">
                      <h4>Chegirma narxi: <span class="text-info">{{ number_format($order[0]->last_contract_price, 2, ",", " ") }}</span> so'm</h4>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <input type="hidden" name="order_id"  class="confirmed_order_id" value="">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Yopish
                  </button>
                  <button type="submit" class="btn btn-primary">Tasdiqlash</button>
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
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/js/managerOrderRebate.js')}}" type="text/javascript"></script>

  <script>
    $(document).ready(function(){
      $('body').on('click', '.btn_delete', function(){
        let order_id = $(this).data('id');
        $(".deleted_order_id").val(order_id);
        $("#order-delete-modal").modal("show");
      });

      $('body').on('click', '.btn_check', function(){
        let order_id = $(this).data('id'),
            contract_price = $(this).data('contract_price'),
            installation_price = $(this).data('installation_price'),
            courier_price = $(this).data('courier_price');

        document.cookie = "contract_price=" + contract_price;
        document.cookie = "installation_price=" + installation_price;
        document.cookie = "courier_price=" + courier_price;

        $('.confirmed_order_id').val(order_id);
        $('#confirm-order-modal').modal('show');
      });
    });
  </script>
@endsection