<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
	<style type="text/css">
		table {
			font-size: 10px !important;
      border-width: 0.25em !important;
      border-color: #000 !important;
		}
    table th, table td {
      border-width: 0.15em !important;
      border-color: #000 !important;
      padding: 0.2rem !important;
    }
		table thead th {
			border-bottom: none !important;
		}
    * {
      /*font-family: Helvetica, sans-serif;*/
      font-family: "DejaVu Sans", sans-serif;
    }
    #without_border_table {
      border:none;
      width: 100%;
      font-size: 18px !important;
      margin-top: 20px !important;
    }
    #without_border_table td {
      border: none;
    }
	</style>
</head>
<body>
<h4>Shartnoma raqami: {{ $order[0]->id }} </h4>
<span>{{ $order[0]->customer_type }}: {{ $order[0]->customer }} (tel: {{ $order[0]->phone_number }})</span><br>
<span>Shartnoma tuzilgan vaqti: {{ date('d.m.y H:i:s', strtotime($order[0]->created_at)) }} </span><br>
<span>Topshirish sanasi: {{ date('d.m.Y', strtotime($order[0]->deadline)) }}</span><br>
<span>Shartnoma narxi: {{ number_format($order[0]->last_contract_price, 2, ",", " ") }} so'm</span><br>
<span>Eshik turi: {{ $doortype }}</span><br>
<span>Eshik rangi: {{ $door_color }}</span><br><br>
<table class="table table-bordered w-100">
    <thead>
      <tr>
        <th class="text-center align-middle" style="width:15px;">T/r</th>
        <th class="text-center align-middle" style="width:15px;">Bo'yi</th>
        <th class="text-center align-middle" style="width:15px;">Eni</th>
        <th class="text-center align-middle" style="width:15px;">Soni</th>
        <th class="text-center align-middle" style="width:25px;">L-P</th>
        <th class="text-center align-middle">Devor qalinligi</th>
        <th class="text-center align-middle" style="width:50px">Karobka o'lchami</th>
        <th class="text-center align-middle" style="width:40px">Karobka qalinligi</th>
        <th class="text-center align-middle" style="width:30px;">Tabaqaligi</th>
        <th class="text-center align-middle">Porog</th>
        <th class="text-center align-middle">Naqsh shakli</th>
        <th class="text-center align-middle" style="width: 200px;">Qulf turi</th>
      </tr>
    </thead>
    <tbody>
      <?php $total_count = 0; ?>
      @foreach($door_parameters as $key => $value)  
      <?php $total_count += $value['count']; ?>
        <tr>
          <td class="text-center align-middle">{{ $key + 1 }}</td>
          <td class="text-center align-middle">{{ $value['height'] }}</td>
          <td class="text-center align-middle">{{ $value['width'] }}</td>
          <td class="text-center align-middle">{{ $value['count'] }}</td>
          <td class="text-center align-middle">{{ $value['l_p'] }}</td>
          <td class="text-center align-middle">{{ $value['wall_thickness'] }}</td>
          <td class="text-center align-middle">{{ $value['box_size'] }}</td>
          <td class="text-center align-middle">{{ $value['depth'] }}</td>
          <td class="text-center align-middle">{{ $value['layer'] }}</td>
          <td class="align-middle">{{ $value['doorstep']  ?? '' }}</td>
          <td class="align-middle">{{ $value['ornamenttype'] }}</td>
          <td class="align-middle">{{ substr($value['locktype'], 0, 30) }}</td>
        </tr>
      @endforeach
    </tbody>
</table>

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
        <td class="align-middle">{{ $value['height'] }}x{{ $value['width'] }}({{ $value['layer'] }} tabaqa)</td>
        <td class="align-middle text-center">{{ $value['count'] }}</td>
        <td class="align-middle">{{ number_format($value['price'], 2, ",", " ")}} so'm</td>
        <td class="align-middle">{{ number_format($value['total_price'], 2, ",", " ") }} so'm</td>
      </tr>
    @endforeach
    @foreach($layers as $key => $value)
      <tr>
        <td class="align-middle">Tabaqaligi</td>
        <td class="align-middle">{{ $value['name'] }}</td>
        <td class="align-middle text-center">{{ $value['count'] }}</td>
        <td class="align-middle">{{ number_format($value['price'], 2, ",", " ") }} so'm</td>
        <td class="align-middle">{{ number_format($value['total_price'], 2, ",", " ") }} so'm</td>
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
      @if ($value['type'] != "")
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
      @if (!empty($value['name']) && $value['name'] != "")
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
      @if(!empty($v['name']))
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
        <td class="align-middle text-center">{{ $total_count }}</td>
        <td class="align-middle">{{ number_format(($order[0]->installation_price - $transom_installation_price * $transom_count) / $total_count , 2, ",", " ") }} so'm</td>
        <td class="align-middle">{{ number_format($order[0]->installation_price - $transom_installation_price * $transom_count, 2, ",", " ") }} so'm</td>
      </tr>
    @endif
    @if ($transom_installation_price != 0)
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
    @if ($order[0]->rebate_percent != 0)
      <tr>
        <td class="align-middle">Chegirma</td>
        <td class="align-middle"></td>
        <td class="align-middle"></td>
        <td class="align-middle"></td>
        <td class="align-middle">{{ $order[0]->rebate_percent }}% ({{ number_format($order[0]->contract_price - $order[0]->last_contract_price, 2, ",", " ") }} so'm)</td>
      </tr>
    @endif
    <tr>
      <td class="align-middle fw-bold">Oxirgi summa:</td>
      <td class="align-middle"></td>
      <td class="align-middle"></td>
      <td class="align-middle"></td>
      <td class="align-middle fw-bold">{{ number_format($order[0]->last_contract_price, 2, ",", " ") }} so'm</td>
    </tr>
  </tbody>
</table>
<i>Eslatma: Mahsulotni yetkazib berish muddati ba'zi texnologik jarayonlar sababli 3 ish kunigacha o'zgarishi mumkin.</i>
<table id="without_border_table">
  <tr>
    <td>{{ $order[0]->customer_type }}: {{ $order[0]->customer }}</td>
    <td>Imzo:_______________</td>
  </tr>
</table>
</body>
</html>