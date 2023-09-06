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
    *{
        /*font-family: Helvetica, sans-serif;*/
        font-family: "DejaVu Sans", sans-serif;
    }
    .last_table {
        border:none;
    }
    .last_table tr {
        border:none;
    }
</style>
</head>
<body>
<span>Sana: {{ date('d.m.Y', strtotime($waybill[0]->day)) }}</span><br>
<span>Registratsiya raqami: {{ $waybill[0]->id }}</span><br>
<span>Qayerdan: {{ $waybill[0]->_from }} &nbsp;&nbsp;&nbsp; Qayerga: {{ $waybill[0]->_to }}</span><br>
<span>Haydovchi: {{ $waybill[0]->driver }}</span><br>
<span>Naryad raqami: {{ $order[0]->id }} ({{ $door[0]->doortype }} {{ $door[0]->door_color }} {{ $door[0]->ornament_model }})</span>
<table class="table table-bordered w-100 mt-4">
    <thead>
      <tr>
        <th class="text-center align-middle" style="width:15px;">T/r</th>
        <th class="text-center align-middle">Nomi</th>
        <th class="text-center align-middle" style="width: 100px;">Soni</th>
      </tr>
    </thead>
    <tbody>
        <?php $i = 0; ?>
        @foreach($doortypes as $key => $value)
        <?php $i++; ?>
        <tr>
            <td class="text-center align-middle">{{ $i }}</td>
            <td class="align-middle">{{ $value['name'] }} {{ $value['height'] }}x{{ $value['width'] }}</td>
            <td class="text-center align-middle">{{ $value['count'] }}</td>
        </tr>
        @endforeach
        @foreach($jambs as $key => $value)
            @if (!empty($value['name']) && $value['name'] != "")
                <?php $i++; ?>
                <tr>
                    <td class="text-center align-middle">{{ $i }}</td>
                    <td class="align-middle">{{ $value['name'] }}</td>
                    <td class="text-center align-middle">{{ $value['count'] }}</td>
                </tr>
            @endif
        @endforeach
        @foreach($transoms as $key => $value)
            @if (!empty($value['name']) && $value['name'] != "")
                <?php $i++; ?>
                <tr>
                    <td class="text-center align-middle">{{ $i }}</td>
                    <td class="align-middle">{{ $value['name'] }} {{ $value['height'] }}x{{ $value['thickness'] }}</td>
                    <td class="text-center align-middle">{{ $value['height_count'] }}</td>
                </tr>
                <?php $i++; ?>
                <tr>
                    <td class="text-center align-middle">{{ $i }}</td>
                    <td class="align-middle">{{ $value['name'] }} {{ $value['width'] }}x{{ $value['thickness'] }} </td>
                    <td class="text-center align-middle">{{ $value['width_count'] }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
<table class="last_table w-100 mt-4">
    <tr>
        <td>
            <h6>Bo'lim boshlig'i: Toshmurodov A.</h6>
        </td>
        <td>
            <h6>Jamlovchi: ______________</h6></td>
    </tr>
    <tr>
        <td>
            <h6>Menedjer: {{ Auth::user()->username }}</h6>
        </td>
        <td>
            <h6>Qabul qildi: ______________</h6>
        </td>
    </tr>
</table>
</body>
</html>