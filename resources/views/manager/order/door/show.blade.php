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
    <h3 class="text-center text-primary mt-4">Eshik ma'lumotlari</h3>
    <div class="row mb-3">
      <div class="col-md-12">
        <div class="table-responsive text-nowrap m-3">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th class="text-center" style="width: 20px;">T/r</th>
                <th class="text-center">Bo'yi</th>
                <th class="text-center">Eni</th>
                <th class="text-center">Soni</th>
                <th class="text-center">L-P</th>
                <th class="text-center">Devor qalinligi</th>
                <th class="text-center">Karobka o'lchami</th>
                <th class="text-center">Karobka qalinligi</th>
                <th class="text-center">Dobor</th>
                <th class="text-center">Tabaqaligi</th>
                <th class="text-center">Porog</th>
                <th class="text-center">Naqsh shakli</th>
                <th class="text-center">Qulf turi</th>
              </tr>
            </thead>
            <tbody>
              <?php $total_doors_count = 0; ?>
              @foreach($door_parameters as $key => $value)
                <?php $total_doors_count += $value['count']; ?>
                <tr>
                  <td class="text-center">{{ $key + 1 }}</td>
                  <td class="text-center">{{ $value['height'] }}</td>
                  <td class="text-center">{{ $value['width'] }}</td>
                  <td class="text-center">{{ $value['count'] }}</td>
                  <td class="text-center">{{ $value['l_p'] }}</td>
                  <td class="text-center">{{ $value['wall_thickness'] }}</td>
                  <td class="text-center">{{ $value['box_size'] }}</td>
                  <td class="text-center">{{ $value['depth'] }}</td>
                  <td class="text-center">{{ $value['transom'] ?? '' }}</td>
                  <td class="text-center">{{ $value['layer'] }}</td>
                  <td>{{ $value['doorstep']  ?? '' }}</td>
                  <td>{{ $value['ornamenttype'] }}</td>
                  <td>{{ $value['locktype'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive text-nowrap m-3">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center">Nomi</th>
                <th class="text-center">O'lchami</th>
                <th class="text-center">Soni</th>
                <th class="text-center">Narxi</th>
                <th class="text-center">Summasi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($doortypes as $key => $value)
                <tr>
                  <td class="align-middle">{{ $value['name'] }}</td>
                  <td class="align-middle">{{ $value['height'] }}x{{ $value['width'] }} ({{ $value['layer'] }} tabaqa)</td>
                  <td class="align-middle text-center">{{ $value['count'] }}</td>
                  <td class="align-middle">{{ number_format($value['price'], 0, ",", " ")}} so'm</td>
                  <td class="align-middle">{{ number_format($value['total_price'], 0, ",", " ") }} so'm</td>
                </tr>
              @endforeach
              @foreach($layers as $key => $value)
                <tr>
                  <td class="align-middle">Tabaqaligi</td>
                  <td class="align-middle">{{ $value['name'] }}</td>
                  <td class="align-middle text-center">{{ $value['count'] }}</td>
                  <td class="align-middle">{{ number_format($value['price'], 2, ",", " ") }} so'm</td>
                  <td class="align-middle">{{ number_format(intval($value['total_price']), 2, ",", " ") }} so'm</td>
                </tr>
              @endforeach
              @foreach($depths as $key => $value)
                <tr>
                  <td class="align-middle">Karobka qalinligi</td>
                  <td class="align-middle">{{ $value['name'] }}</td>
                  <td class="align-middle text-center">{{ $value['count'] }}</td>
                  <td class="align-middle">{{ number_format($value['price'], 2, ",", " ") }} so'm</td>
                  <td class="align-middle">{{ number_format($value['total_price'], 2, ",", " ") }} so'm</td>
                </tr>
              @endforeach
              @foreach($ornamenttypes as $key => $value)
                <tr>
                  <td class="align-middle">Naqsh shakli</td>
                  <td class="align-middle">{{ $value['name'] }}</td>
                  <td class="align-middle text-center">{{ $value['count'] }}</td>
                  <td class="align-middle">{{ number_format($value['price'], 2, ",", " ") }} so'm</td>
                  <td class="align-middle">{{ number_format($value['total_price'], 2, ",", " ") }} so'm</td>
                </tr>
              @endforeach
              @foreach($glasses as $key => $value)
                @if ($value['type'] != "" && $value['figure'] != "")
                  <tr>
                    <td rowspan="2" class="align-middle">Shisha</td>
                    <td class="align-middle">{{ $value['type'] }}</td>
                    <td rowspan="2" class="align-middle text-center">{{ $value['total_count'] }}</td>
                    <td rowspan="2" class="align-middle">{{ number_format($value['total_price'] / $value['total_count'], 2, ",", " ") }} so'm</td>
                    <td rowspan="2" class="align-middle">{{ number_format($value['total_price'], 2, ",", " ") }} so'm</td>
                  </tr>
                  <tr>
                    <td class="align-middle">{{ $value['figure'] }}</td>
                  </tr>
                @endif
              @endforeach
              @foreach($locktypes as $key => $value)
                <tr>
                  <td class="align-middle">Qulf turi</td>
                  <td class="align-middle">{{ $value['name'] }}</td>
                  <td class="align-middle text-center">{{ $value['count'] }}</td>
                  <td class="align-middle">{{ number_format($value['price'], 2, ",", " ") }} so'm</td>
                  <td class="align-middle">{{ number_format($value['total_price'], 2, ",", " ") }} so'm</td>
                </tr>
              @endforeach
              @foreach($loops as $key => $value)
                <tr>
                  <td class="align-middle">Chaspak</td>
                  <td class="align-middle">{{ $value['name'] }}</td>
                  <td class="align-middle text-center">{{ $value['count'] }}</td>
                  <td class="align-middle">{{ number_format($value['price'], 2, ",", " ") }} so'm</td>
                  <td class="align-middle">{{ number_format($value['total_price'], 2, ",", " ") }} so'm</td>
                </tr>
              @endforeach
              <?php $transom_count = 0; ?>
              @foreach($transoms as $key => $value)
                @if ($value['height'] != 0)
                <?php $transom_count += $value['width_count']; ?>
                  <tr>
                    <td rowspan="2" class="align-middle">Dobor</td>
                    <td class="align-middle">{{ $value['name'] }} {{ $value['height'] }}x{{ $value['thickness'] }}</td>
                    <td class="align-middle text-center">{{ $value['height_count'] }}</td>
                    <td rowspan="2" class="align-middle">{{ number_format($value['price'], 2, ",", " ") }} so'm</td>
                    <td rowspan="2" class="align-middle">{{ number_format($value['total_price'], 2, ",", " ") }} so'm</td>
                  </tr>
                  <tr>
                    <td class="align-middle">{{ $value['name'] }} {{ $value['width'] }}x{{ $value['thickness'] }} </td>
                    <td class="align-middle text-center">{{ $value['width_count'] }}</td>
                  </tr>
                @endif
              @endforeach
              @foreach($jambs as $k => $v)
                @if (!empty($v['name']))
                  <tr>
                    <td class="align-middle">Nalichnik</td>
                    <td class="align-middle">{{ $v['name'] }}</td>
                    <td class="align-middle text-center">{{ $v['count'] }}</td>
                    <td class="align-middle">{{ number_format($v['price'], 2, ",", " ") }} so'm</td>
                    <td class="align-middle">{{ number_format($v['total_price'], 2, ",", " ") }} so'm</td>
                  </tr>
                @endif
              @endforeach
              @foreach($door_parameters as $key => $value)
                @if(isset($value['framogatype_name']) && !empty($value['framogatype_name']) && isset($value['framogafigure_name']) && !empty($value['framogafigure_name']))
                  <tr>
                    <td rowspan="2" class="align-middle">Framoga</td>
                    <td class="align-middle">{{ $value['framogatype_name'] }}</td>
                    <td rowspan="2" class="align-middle text-center">{{ $value['count'] }}</td>
                    <td rowspan="2" class="align-middle">{{ number_format($value['framogafigure_price'], 2, ",", " ") }} so'm</td>
                    <td rowspan="2" class="align-middle">{{ number_format($value['total_framogafigure_price'], 2, ",", " ") }} so'm</td>
                  </tr>
                  <tr>
                    <td class="align-middle">{{ $value['framogafigure_name'] }}</td>
                  </tr>
                @endif
              @endforeach
              @if ($order[0]->installation_price != 0)
                <tr>
                  <td class="align-middle">Ustanovka (eshik)</td>
                  <td class="align-middle"></td>
                  <td class="align-middle text-center">{{ $total_doors_count }}</td>
                  <td class="align-middle">{{ number_format(($order[0]->installation_price - $transom_installation_price * $transom_count) / $total_doors_count , 2, ",", " ") }} so'm</td>
                  <td class="align-middle">{{ number_format($order[0]->installation_price - $transom_installation_price * $transom_count, 2, ",", " ") }} so'm</td>
                </tr>
              @endif
              @if($transom_installation_price != 0)
                <tr>
                  <td class="align-middle">Ustanovka (dobor)</td>
                  <td class="align-middle"></td>
                  <td class="align-middle text-center">{{ $transom_count }}</td>
                  <td class="align-middle">{{ number_format($transom_installation_price, 2, ",", " ") }} so'm</td>
                  <td class="align-middle">{{ number_format($transom_count * $transom_installation_price, 2, ",", " ") }} so'm</td>
                </tr>
              @endif
              @if($order[0]->courier_price != 0)
                <tr>
                  <td class="align-middle">Dostavka</td>
                  <td class="align-middle"></td>
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
                  <td class="align-middle"></td>
                  <td class="align-middle">{{ $order[0]->rebate_percent }} %</td>
                </tr>
              @endif
              <tr>
                <td class="align-middle fw-bold">Oxirgi summa:</td>
                <td class="align-middle"></td>
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