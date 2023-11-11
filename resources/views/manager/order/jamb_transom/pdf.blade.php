<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
	<style type="text/css">
		table {
			font-size: 24px !important;
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
    }
    #without_border_table td {
      border: none;
      font-size: 48px !important;
    }
	</style>
</head>
<body>
<h4>Shartnoma raqami: {{$order[0]->id}}/{{ $order[0]->contract_number }} </h4>
<span>{{ $order[0]->customer_type }}: {{ $order[0]->customer }} (tel: {{ $order[0]->phone_number }})</span><br>
<span>Shartnoma tuzilgan vaqti: {{ date('d.m.y H:i:s', strtotime($order[0]->created_at)) }} </span><br>
<span>Topshirish sanasi: {{ date('d.m.Y', strtotime($order[0]->deadline)) }}</span><br>
<span>Shartnoma narxi: {{ number_format($order[0]->last_contract_price, 2, ",", " ") }} so'm</span><br>
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
    @foreach($transoms as $key => $value)
      <tr>
        <td class="align-middle">{{ $value->name }} ({{ $value->height }} X {{ $value->width_top }} X {{ $value->width_bottom }})</td>
        <td class="align-middle text-center">{{ $value->count }}</td>
        <td class="align-middle">{{ number_format($value->price, 2, ",", " ")}} so'm</td>
        <td class="align-middle">{{ number_format($value->total_price, 2, ",", " ") }} so'm</td>
      </tr>
    @endforeach
    @foreach($jambs as $key => $value)
      <tr>
        <td class="align-middle">{{ $value->name }}</td>
        <td class="align-middle text-center">{{ $value->count }}</td>
        <td class="align-middle">{{ number_format($value->price, 2, ",", " ")}} so'm</td>
        <td class="align-middle">{{ number_format($value->total_price, 2, ",", " ") }} so'm</td>
      </tr>
    @endforeach
    @if ($order[0]->installation_price != 0)
      <tr>
        <td class="align-middle">Ustanovka</td>
        <td class="align-middle"></td>
        <td class="align-middle">{{ number_format($order[0]->installation_price, 2, ",", " ") }} so'm</td>
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
    @if ($order[0]->rebate_percent != 0)
      <tr>
        <td class="align-middle">Chegirma</td>
        <td class="align-middle"></td>
        <td class="align-middle"></td>
        <td class="align-middle">{{ $order[0]->rebate_percent }}% ({{ number_format($order[0]->contract_price - $order[0]->last_contract_price, 2, ",", " ") }} so'm)</td>
      </tr>
    @endif
    <tr>
      <td class="align-middle fw-bold">Oxirgi summa:</td>
      <td class="align-middle"></td>
      <td class="align-middle"></td>
      <td class="align-middle fw-bold">{{ number_format($order[0]->last_contract_price, 2, ",", " ") }} so'm</td>
    </tr>
  </tbody>
</table>
<i>Eslatma: Mahsulotni yetkazib berish muddati ba'zi texnologik jarayonlar sababli 3 ish kunigacha o'zgarishi mumkin.</i>
<table id="without_border_table" style="margin-top:30px">
  <tr>
    <td>{{ $order[0]->customer_type }}: {{ $order[0]->customer }}</td>
    <td>Imzo:_______________</td>
  </tr>
</table>
<table id="without_border_table" style="margin-top:30px">
  <tr>
    <td style="width:20%">Murojaat uchun:</td>
    <td style="width:80%">(99) 414 33 31</td>
  </tr>
  <tr>
    <td></td>
    <td style="width:80%">(99) 414 33 37</td>
  </tr>
  <tr>
    <td></td>
    <td style="width:80%">(99) 731 00 03</td>
  </tr>
</table>
</body>
</html>