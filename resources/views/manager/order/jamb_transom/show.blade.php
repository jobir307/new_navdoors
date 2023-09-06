@extends('layouts.manager')
@section('content')
  <div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('orders') }}" class="fw-light">Shartnomalar / </a><span class="text-muted fw-light">Shartnoma ma'lumotlarini ko'rish</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <h5 class="card-header">№{{ $order[0]->contract_number }} shartnoma ma'lumotlari</h5>
          <div class="table-responsive text-nowrap m-3">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th class="text-center align-middle" rowspan=2>Buyurtmachi</th>
                  <th class="text-center align-middle" rowspan=2>Tel.raqami</th>
                  <th class="text-center align-middle" rowspan=2>Shartnoma raqami</th>
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
                    <td>{{ number_format($order[0]->contract_price, 0, ",", " ") }} so'm</td>
                    <td>{{ number_format($order[0]->last_contract_price, 0, ",", " ") }} so'm</td>
                    <td>{{ number_format($order[0]->paid, 0, ",", " ") }} so'm</td>
                    <td>{{ number_format($order[0]->last_contract_price-$order[0]->paid, 0, ",", " ") }} so'm</td>
                    <td>{{ date("d.m.Y", strtotime($order[0]->deadline)) }}</td>
                    <td>{{ $order[0]->process }}</td>
                  </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <h3 class="text-center text-primary mt-4">Dobor va nalichnik ma'lumotlari</h3>
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
                        @foreach($transom_results as $key => $value)
                        <tr>
                            <td class="align-middle">{{ $value->name }} ({{ $value->height }} X {{ $value->width_top }} X {{ $value->width_bottom }})</td>
                            <td class="align-middle text-center">{{ $value->count }}</td>
                            <td class="align-middle">{{ number_format($value->price, 0, ",", " ")}} so'm</td>
                            <td class="align-middle">{{ number_format($value->total_price, 0, ",", " ") }} so'm</td>
                        </tr>
                        @endforeach
                        @foreach($jamb_results as $key => $value)
                        <tr>
                            <td class="align-middle">{{ $value->name }}</td>
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
    </div>
@endsection

@section('scripts')
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
@endsection