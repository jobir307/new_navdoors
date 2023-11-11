@extends('layouts.manager')
<link rel="stylesheet" href="{{ asset('assets/css/managerConfirmOrderModal.css') }}">
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
        <div class="row mb-5">
          <div class="col-md-12">
            <div class="card" style="box-shadow:none; background-color:#F5F5F9; display:block;">
              <div class="card-body">
                <div class="demo-inline-spacing">
                  <div class="btn-group" style="position:absolute; right: 0;">
                    <button
                      type="button"
                      class="btn btn-primary dropdown-toggle"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      Yaratish
                    </button>
                    <ul class="dropdown-menu">
                      <li><a href="{{ route('order-doors.create') }}" class="dropdown-item">Eshik</a></li>
                      <li><a class="dropdown-item" href="{{ route('order-jambs.create') }}">Nalichnik</a></li>
                      <li><a class="dropdown-item" href="{{ route('order-nsjambs.create') }}">NS nalichnik</a></li>
                      <li><a class="dropdown-item" href="{{ route('order-transoms.create') }}">Dobor</a></li>
                      <li><a class="dropdown-item" href="{{ route('order-jambs-transoms.create') }}">Nalichnik+dobor</a></li>
                      <li><a class="dropdown-item" href="{{ route('order-ccbjs.create') }}">NKKS</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="nav-align-top">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-not-confirmed"
                        aria-controls="navs-not-confirmed"
                        aria-selected="true"
                      >
                        Tasdiqlanmagan
                      </button>
                    </li>
                    <li class="nav-item">
                      <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-confirmed"
                        aria-controls="navs-confirmed"
                        aria-selected="false"
                      >
                        Tasdiqlangan
                      </button>
                    </li>
                    <li class="nav-item">
                      <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-completed"
                        aria-controls="navs-completed"
                        aria-selected="false"
                      >
                        Yakunlangan
                      </button>
                    </li>
                    <li class="nav-item">
                      <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-all"
                        aria-controls="navs-all"
                        aria-selected="false"
                      >
                        Hammasi
                      </button>
                    </li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane fade" id="navs-not-confirmed" role="tabpanel">
                      <h5 class="text-primary">Tasdiqlanmagan shartnomalar ro'yxati</h5>
                      <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover w-100" id="notconfirmed_order_table">
                          <thead>
                            <tr>
                              <th class="text-center" style="width:20px;" rowspan=2>T/r</th>
                              <th class="text-center" rowspan=2>Buyurtmachi</th>
                              <th class="text-center" rowspan=2>Telefon raqami</th>
                              <th class="text-center" rowspan=2>Shartnoma raqami</th>
                              <th class="text-center" rowspan=2>Mahsulot</th>
                              <th class="text-center" rowspan=2>Umumiy narxi</th>
                              <th class="text-center" colspan=2>Vaqti</th>
                              @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                <th class="text-center align-middle" rowspan="2">Kim yaratdi</th>
                              @endif
                              <th style="width:110px !important;" rowspan=2></th>
                            </tr>
                            <tr>
                              <th class="text-center">Yaratilgan</th>
                              <th class="text-center">Muddati</th>
                            </tr>
                            <tr>
                              <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Umumiy narxi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Yaratilgan"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Muddati"></td>
                              @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                <td><input class="form-control form-control-sm" type="text" placeholder="Kim yaratdi"></td>
                              @endif
                              <td></td>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($not_confirmed_orders as $key => $value)
                            <?php 
                              $pdf_route = "";
                              $show_route = "";
                              $edit_route = "";
                              
                              switch ($value->product) {
                                case 'Nalichnik':
                                  $pdf_route = "pdf-order-jamb";
                                  $show_route = "order-jambs.show";
                                  $edit_route = "order-jambs.edit";
                                  break;
                                case 'NS nalichnik':
                                  $pdf_route = "pdf-order-nsjamb";
                                  $show_route = "order-nsjambs.show";
                                  $edit_route = "order-nsjambs.edit";
                                  break;
                                case 'Dobor':
                                  $pdf_route = "pdf-order-transom";
                                  $show_route = "order-transoms.show";
                                  $edit_route = "order-transoms.edit";
                                  break;
                                case 'Nalichnik+dobor':
                                  $pdf_route = "pdf-order-jamb-transom";
                                  $show_route = "order-jambs-transoms.show";
                                  $edit_route = "order-jambs-transoms.edit";
                                  break;
                                case 'Eshik':
                                  $pdf_route = "pdf-order-door";
                                  $show_route = "order-doors.show";
                                  $edit_route = "order-doors.edit";
                                  break;
                                default: 
                                  $pdf_route = "pdf-order-ccbj";
                                  $show_route = "order-ccbjs.show";
                                  $edit_route = "order-ccbjs.edit";
                                  break;
                              }
                            ?>
                              <tr id="{{ $key }}">
                                @if (Auth::user()->role_id == 1)
                                  <td onclick="setNewContractprice({{ $value->id }})" class="text-center">{{ $key + 1 }}</td>
                                @else
                                  <td class="text-center">{{ $key + 1 }}</td>
                                @endif
                                <td>{{ $value->customer }}</td>
                                <td>{{ $value->phone_number }}</td>
                                <td>{{ $value->id }}/{{ $value->contract_number }}</td>
                                <td>{{ $value->product }}</td>
                                <td>{{ number_format($value->contract_price, 2, ",", " ") }} so'm</td>
                                <td>{{ date("d.m.Y H:i", strtotime($value->when_created)) }}</td>
                                <td>{{ date("d.m.Y", strtotime($value->deadline)) }}</td>
                                @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                  <td>{{ $value->who_created_username }}</td>
                                @endif
                                <td class="text-sm-end">
                                  <a href="{{ url($pdf_route, $value->id) }}" class="btn-sm btn btn-icon btn-outline-secondary" title="Chop etish">
                                    <i class="bx bx-printer"></i>
                                  </a>
                                  <a href="{{ route($show_route, $value->id) }}" class="btn-sm btn btn-icon btn-outline-success" title="Ko'rish">
                                    <i class="bx bx-show"></i>
                                  </a>
                                  @if (Auth::user()->role_id == 1 || Auth::user()->id == $value->who_created_userid)
                                  <a href="{{ route($edit_route, $value->id) }}" class="btn-sm btn btn-icon btn-outline-primary" title="O'zgartirish">
                                    <i class="bx bx-pencil"></i>
                                  </a>
                                  @endif
                                  @if (Auth::user()->role_id == 1)
                                    <button type="button" class="btn-sm btn btn-icon btn-outline-info btn_confirm" title="Tasdiqlash" data-id="{{ $value->id }}" data-contract_price="{{ $value->contract_price }}" data-installation_price="{{ $value->installation_price }}" data-courier_price="{{ $value->courier_price }}">
                                      <i class="bx bx-check"></i>
                                    </button>
                                    <button type="button" class="btn-sm btn btn-icon btn-outline-danger btn_delete" title="Tasdiqlash" data-id="{{ $value->id }}">
                                      <i class="bx bx-trash"></i>
                                    </button>
                                  @endif  
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="navs-confirmed" role="tabpanel">
                      <h5 class="text-primary">Tasdiqlangan shartnomalar ro'yxati</h5>
                      <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover w-100" id="confirmed_order_table">
                          <thead>
                            <tr>
                              <th class="text-center align-middle" rowspan="2" style="width:20px;">T/r</th>
                              <th class="text-center align-middle" rowspan="2">Buyurtmachi</th>
                              <th class="text-center align-middle" rowspan="2">Telefon raqami</th>
                              <th class="text-center align-middle" rowspan="2">Shartnoma raqami</th>
                              <th class="text-center align-middle" rowspan="2">Mahsulot</th>
                              <th class="text-center align-middle" colspan="4">Summa</th>
                              <th class="text-center align-middle" colspan="2">Vaqti</th>
                              <th class="text-center align-middle" rowspan="2">Holati</th>
                              @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                <th class="text-center align-middle" rowspan="2">Kim yaratdi</th>
                              @endif
                              <th rowspan="2" style="width:80px !important;"></th>
                            </tr>
                            <tr>
                              <th class="text-center">Umumiy narxi</th>
                              <th class="text-center">Shartnoma narxi</th>
                              <th class="text-center">To'landi</th>
                              <th class="text-center">Qoldi</th>
                              <th class="text-center">Tasdiqlangan</th>
                              <th class="text-center">Muddati</th>
                            </tr>
                            <tr>
                              <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Umumiy narxi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma narxi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="To'landi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Qoldi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Tasdiqlangan"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Muddati"></td>
                              <td></td>
                              @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                <td><input class="form-control form-control-sm" type="text" placeholder="Kim yaratdi"></td>
                              @endif
                              <td></td>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($confirmed_orders as $key => $value)
                            <?php 
                              $pdf_route = "";
                              $show_route = "";
                              $edit_route = "";
                              
                              switch ($value->product) {
                                case 'Nalichnik':
                                  $pdf_route = "pdf-order-jamb";
                                  $show_route = "order-jambs.show";
                                  break;
                                case 'NS nalichnik':
                                  $pdf_route = "pdf-order-nsjamb";
                                  $show_route = "order-nsjambs.show";
                                  break;
                                case 'Dobor':
                                  $pdf_route = "pdf-order-transom";
                                  $show_route = "order-transoms.show";
                                  break;
                                case 'Nalichnik+dobor':
                                  $pdf_route = "pdf-order-jamb-transom";
                                  $show_route = "order-jambs-transoms.show";
                                  break;
                                case 'Eshik':
                                  $pdf_route = "pdf-order-door";
                                  $show_route = "order-doors.show";
                                  $edit_route = "order-doors.edit";
                                  break;
                                default: 
                                  $pdf_route = "pdf-order-ccbj";
                                  $show_route = "order-ccbjs.show";
                                  $edit_route = "order-ccbjs.edit";
                                  break;
                              }
                            ?>
                              <tr class="text-nowrap">
                                @if (Auth::user()->role_id == 1)
                                  <td onclick="setNewContractprice({{ $value->id }})" class="text-center">{{ $key + 1 }}</td>
                                @else
                                  <td class="text-center">{{ $key + 1 }}</td>
                                @endif
                                <td>{{ $value->customer }}</td>
                                <td>{{ $value->phone_number }}</td>
                                <td>{{ $value->id }}/{{ $value->contract_number }}</td>
                                <td>{{ $value->product }}</td>
                                <td>{{ number_format($value->contract_price, 2, ",", " ") }}</td>
                                <td>{{ number_format($value->last_contract_price, 2, ",", " ") }}</td>
                                <td>{{ number_format($value->paid, 2, ",", " ") }}</td>
                                <td>{{ number_format($value->last_contract_price-$value->paid, 2, ",", " ") }}</td>
                                <td>{{ date("d.m.Y H:i", strtotime($value->verified_time)) }}</td>
                                <td>{{ date("d.m.Y", strtotime($value->deadline)) }}</td>
                                <td>{{ $value->process }}</td>
                                @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                  <td>{{ $value->who_created_username }}</td>
                                @endif
                                <td class="text-sm-end">
                                  <a href="{{ url($pdf_route, $value->id) }}" class="btn-sm btn btn-icon btn-outline-secondary" title="Chop etish">
                                    <i class="bx bx-printer"></i>
                                  </a>
                                  <a href="{{ route($show_route, $value->id) }}" class="btn-sm btn btn-outline-success" title="Ko'rish">
                                    Ko'rish
                                  </a>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="navs-completed" role="tabpanel">
                      <h5 class="text-primary">Yakunlangan shartnomalar ro'yxati</h5>
                      <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover w-100" id="completed_order_table">
                          <thead>
                            <tr>
                              <th class="text-center align-middle" rowspan="2" style="width:20px;">T/r</th>
                              <th class="text-center align-middle" rowspan="2">Buyurtmachi</th>
                              <th class="text-center align-middle" rowspan="2">Telefon raqami</th>
                              <th class="text-center align-middle" rowspan="2">Shartnoma raqami</th>
                              <th class="text-center align-middle" rowspan="2">Mahsulot</th>
                              <th class="text-center align-middle" colspan="4">Summa</th>
                              <th class="text-center align-middle" colspan="2">Vaqti</th>
                              <th class="text-center align-middle" rowspan="2">Holati</th>
                              @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                <th class="text-center align-middle" rowspan="2">Kim yaratdi</th>
                              @endif
                              <th rowspan="2" style="width:80px !important;"></th>
                            </tr>
                            <tr>
                              <th class="text-center">Umumiy narxi</th>
                              <th class="text-center">Shartnoma narxi</th>
                              <th class="text-center">To'landi</th>
                              <th class="text-center">Qoldi</th>
                              <th class="text-center">Muddati</th>
                              <th class="text-center">Yakunlangan</th>
                            </tr>
                            <tr>
                              <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Umumiy narxi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma narxi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="To'landi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Qoldi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Muddati"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Yakunlangan"></td>
                              <td></td>
                              @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                <td><input class="form-control form-control-sm" type="text" placeholder="Kim yaratdi"></td>
                              @endif
                              <td></td>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($completed_orders as $key => $value)
                            <?php 
                              $pdf_route = "";
                              $show_route = "";
                              $edit_route = "";
                              
                              switch ($value->product) {
                                case 'Nalichnik':
                                  $pdf_route = "pdf-order-jamb";
                                  $show_route = "order-jambs.show";
                                  break;
                                case 'NS nalichnik':
                                  $pdf_route = "pdf-order-nsjamb";
                                  $show_route = "order-nsjambs.show";
                                  break;
                                case 'Dobor':
                                  $pdf_route = "pdf-order-transom";
                                  $show_route = "order-transoms.show";
                                  break;
                                case 'Nalichnik+dobor':
                                  $pdf_route = "pdf-order-jamb-transom";
                                  $show_route = "order-jambs-transoms.show";
                                  break;
                                case 'Eshik':
                                  $pdf_route = "pdf-order-door";
                                  $show_route = "order-doors.show";
                                  $edit_route = "order-doors.edit";
                                  break;
                                default: 
                                  $pdf_route = "pdf-order-ccbj";
                                  $show_route = "order-ccbjs.show";
                                  $edit_route = "order-ccbjs.edit";
                                  break;
                              }
                            ?>
                              <tr class="text-nowrap">
                                @if (Auth::user()->role_id == 1)
                                  <td onclick="setNewContractprice({{ $value->id }})" class="text-center">{{ $key + 1 }}</td>
                                @else
                                  <td class="text-center">{{ $key + 1 }}</td>
                                @endif
                                <td>{{ $value->customer }}</td>
                                <td>{{ $value->phone_number }}</td>
                                <td>{{ $value->id }}/{{ $value->contract_number }}</td>
                                <td>{{ $value->product }}</td>
                                <td>{{ number_format($value->contract_price, 2, ",", " ") }}</td>
                                <td>{{ number_format($value->last_contract_price, 2, ",", " ") }}</td>
                                <td>{{ number_format($value->paid, 2, ",", " ") }}</td>
                                <td>{{ number_format($value->last_contract_price-$value->paid, 2, ",", " ") }}</td>
                                <td>{{ date("d.m.Y", strtotime($value->deadline)) }}</td>
                                <td>{{ date("d.m.Y H:i", strtotime($value->moderator_send_time)) }}</td>
                                <td>{{ $value->job_name }}({{ date("d.m.Y H:i:s", strtotime($value->moderator_send_time)) }})</td>
                                @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                  <td>{{ $value->who_created_username }}</td>
                                @endif
                                <td class="text-sm-end">
                                  <a href="{{ url($pdf_route, $value->id) }}" class="btn-sm btn btn-icon btn-outline-secondary" title="Chop etish">
                                    <i class="bx bx-printer"></i>
                                  </a>
                                  <a href="{{ route($show_route, $value->id) }}" class="btn-sm btn btn-outline-success" title="Ko'rish">
                                    Ko'rish
                                  </a>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="navs-all" role="tabpanel">
                      <h5 class="text-primary">Hamma shartnomalar ro'yxati</h5>
                      <div class="table-responsive text-nowrap">
                        <table class="table table-bordered table-hover w-100" id="all_orders_table">
                          <thead>
                            <tr>
                              <th class="text-center align-middle" rowspan="2" style="width:20px;">T/r</th>
                              <th class="text-center align-middle" rowspan="2">Buyurtmachi</th>
                              <th class="text-center align-middle" rowspan="2">Telefon raqami</th>
                              <th class="text-center align-middle" rowspan="2">Shartnoma raqami</th>
                              <th class="text-center align-middle" rowspan="2">Mahsulot</th>
                              <th class="text-center align-middle" colspan="4">Summa</th>
                              <th class="text-center align-middle" colspan="2">Vaqti</th>
                              <th class="text-center align-middle" rowspan="2">Holati</th>
                              @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                <th class="text-center align-middle" rowspan="2">Kim yaratdi</th>
                              @endif
                              <th rowspan="2" style="width:80px !important;"></th>
                            </tr>
                            <tr>
                              <th class="text-center">Umumiy narxi</th>
                              <th class="text-center">Shartnoma narxi</th>
                              <th class="text-center">To'landi</th>
                              <th class="text-center">Qoldi</th>
                              <th class="text-center">Muddati</th>
                              <th class="text-center">Yakunlangan</th>
                            </tr>
                            <tr>
                              <td><input class="form-control form-control-sm" type="text" placeholder="T/r"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Mahsulot"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Umumiy narxi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma narxi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="To'landi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Qoldi"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Muddati"></td>
                              <td><input class="form-control form-control-sm" type="text" placeholder="Yakunlangan"></td>
                              <td></td>
                              @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                <td><input class="form-control form-control-sm" type="text" placeholder="Kim yaratdi"></td>
                              @endif
                              <td></td>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($completed_orders as $key => $value)
                            <?php 
                              $pdf_route = "";
                              $show_route = "";
                              $edit_route = "";
                              
                              switch ($value->product) {
                                case 'Nalichnik':
                                  $pdf_route = "pdf-order-jamb";
                                  $show_route = "order-jambs.show";
                                  break;
                                case 'NS nalichnik':
                                  $pdf_route = "pdf-order-nsjamb";
                                  $show_route = "order-nsjambs.show";
                                  break;
                                case 'Dobor':
                                  $pdf_route = "pdf-order-transom";
                                  $show_route = "order-transoms.show";
                                  break;
                                case 'Nalichnik+dobor':
                                  $pdf_route = "pdf-order-jamb-transom";
                                  $show_route = "order-jambs-transoms.show";
                                  break;
                                case 'Eshik':
                                  $pdf_route = "pdf-order-door";
                                  $show_route = "order-doors.show";
                                  $edit_route = "order-doors.edit";
                                  break;
                                default: 
                                  $pdf_route = "pdf-order-ccbj";
                                  $show_route = "order-ccbjs.show";
                                  $edit_route = "order-ccbjs.edit";
                                  break;
                              }
                            ?>
                              <tr class="text-nowrap">
                                @if (Auth::user()->role_id == 1)
                                  <td onclick="setNewContractprice({{ $value->id }})" class="text-center">{{ $key + 1 }}</td>
                                @else
                                  <td class="text-center">{{ $key + 1 }}</td>
                                @endif
                                <td>{{ $value->customer }}</td>
                                <td>{{ $value->phone_number }}</td>
                                <td>{{ $value->id }}/{{ $value->contract_number }}</td>
                                <td>{{ $value->product }}</td>
                                <td>{{ number_format($value->contract_price, 2, ",", " ") }}</td>
                                <td>{{ number_format($value->last_contract_price, 2, ",", " ") }}</td>
                                <td>{{ number_format($value->paid, 2, ",", " ") }}</td>
                                <td>{{ number_format($value->last_contract_price-$value->paid, 2, ",", " ") }}</td>
                                <td>{{ date("d.m.Y", strtotime($value->deadline)) }}</td>
                                <td>{{ date("d.m.Y H:i", strtotime($value->moderator_send_time)) }}</td>
                                <td>{{ $value->job_name }}({{ date("d.m.Y H:i:s", strtotime($value->moderator_send_time)) }})</td>
                                @if(in_array(Auth::user()->role_id,  array(1, 4)))
                                  <td>{{ $value->who_created_username }}</td>
                                @endif
                                <td class="text-sm-end">
                                  <a href="{{ url($pdf_route, $value->id) }}" class="btn-sm btn btn-icon btn-outline-secondary" title="Chop etish">
                                    <i class="bx bx-printer"></i>
                                  </a>
                                  <a href="{{ route($show_route, $value->id) }}" class="btn-sm btn btn-outline-success" title="Ko'rish">
                                    Ko'rish
                                  </a>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
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
                    <?php 
                      $contract_price = 0;
                      if (isset($_COOKIE['contract_price']))
                        $contract_price = $_COOKIE['contract_price'];
                    ?>
                    <div class="col-md-6 after_rebate">
                      <h4>Chegirma narxi: <span class="text-info">{{ number_format($contract_price, 2, ",", " ") }}</span> so'm</h4>
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
        
        <!-- Set New Order Price -->
        <div class="modal fade" id="order-new-contract-price-modal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
              <form action="{{ route('admin-set-new-contract_price') }}" method="POST">
                @csrf
                <div class="modal-header">
                  <h5 class="modal-title text-primary" id="modalCenterTitle">Shartnoma narxini o'zgartirish</h5>
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
                      <label for="new_contract_price" class="form-label">Yangi shartnoma narxi</label>
                      <input id="new_contract_price" class="form-control" type="number" name="last_contract_price" autocomplete="off">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <input type="hidden" name="order_id"  class="new_contratc_price_order_id" value="">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Yopish
                  </button>
                  <button type="submit" class="btn btn-primary">Saqlash</button>
                </div>
              </form>
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
  <script src="{{asset('assets/js/managerOrderRebate.js')}}" type="text/javascript"></script>

  <script type="text/javascript">
    $(document).ready(function () {
      let table_confirmed = $('#confirmed_order_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
          [25, 50, 100, -1],
          [25, 50, 100, "Hammasi"]
        ],
        "ordering": false
      });
      table_confirmed.columns().every(function() {
        let column = this;
        $( 'input', this.header() ).on('keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });

      let table_notconfirmed = $('#notconfirmed_order_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
          [25, 50, 100, -1],
          [25, 50, 100, "Hammasi"]
        ],
        "ordering": false
      });
      table_notconfirmed.columns().every( function () {
        let column = this;
        $( 'input', this.header() ).on( 'keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });

      let completed_order_table = $('#completed_order_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
          [25, 50, 100, -1],
          [25, 50, 100, "Hammasi"]
        ],
        "ordering": false
      });
      completed_order_table.columns().every( function () {
        let column = this;
        $( 'input', this.header() ).on( 'keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });

      let all_orders_table = $('#all_orders_table').DataTable({
        dom: 'Qlrtp',
        lengthMenu: [
          [25, 50, 100, -1],
          [25, 50, 100, "Hammasi"]
        ],
        "ordering": false
      });
      all_orders_table.columns().every( function () {
        let column = this;
        $( 'input', this.header() ).on( 'keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });

      $('body').on('click', '.btn_confirm', function(){
        let order_id = $(this).data("id"),
            contract_price = $(this).data('contract_price'), 
            installation_price = $(this).data('installation_price'),
            courier_price = $(this).data('courier_price');

        document.cookie = "contract_price=" + contract_price;
        document.cookie = "installation_price=" + installation_price;
        document.cookie = "courier_price=" + courier_price;

        $('.confirmed_order_id').val(order_id);
        $("#confirm-order-modal").modal("show");
      });

      $('body').on('click', '.btn_delete', function(){
        let order_id = $(this).data("id");
        $(".deleted_order_id").val(order_id);
        $("#order-delete-modal").modal("show");
      });
    });

    function setNewContractprice(order_id) {
      $(".new_contratc_price_order_id").val(order_id);
      $("#order-new-contract-price-modal").modal("show");
    }
    
    $(function(){
      $('button[data-bs-toggle="tab"]').on('click', function(){
        localStorage.setItem('activeTab', $(this).attr('data-bs-target'));
      });
      
      let activeTab = localStorage.getItem('activeTab');
      
      if(activeTab){
        $(".tab-pane .fade").removeClass("show active");
        $("div.tab-pane"+activeTab).addClass("show active");
        $('.nav-item button').removeClass('active');
        $('.nav-item button[data-bs-target="' + activeTab + '"]').addClass('active');
        $('.nav-item button[data-bs-target="' + activeTab + '"]').attr("aria-selected", "true");
      } else {
        $("div#navs-not-confirmed").addClass("show active");
        $('.nav-item button[data-bs-target="#navs-not-confirmed"]').addClass('active');
        $('.nav-item button[data-bs-target="#navs-not-confirmed"]').attr("aria-selected", "true");
      }
    });


  </script>
  
@endsection