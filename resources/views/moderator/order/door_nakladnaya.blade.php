<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<style type="text/css">
    table {
        font-size: 48px !important;
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
<table class="table table-bordered w-100">
    <tbody>
        <tr>
            <td>Sana:{{ date('d.m.Y', strtotime($waybill[0]->day)) }}</td>
            <td>Registratsiya raqami:{{ $waybill[0]->id }}</td>
        </tr>
        <tr>
            <td>Qayerdan:{{ $waybill[0]->_from }}</td>
            <td>Qayerga:{{ $waybill[0]->_to }}</td>
        </tr>
        <tr>
            <td>{{ $order[0]->customer_type }}:{{ $order[0]->customer }}</td>
            <td>Telefon raqami:{{ $order[0]->phone_number }}</td>
        </tr>
        <tr>
            <td>Haydovchi:{{ $waybill[0]->driver }}</td>
            <td>Shartnoma raqami: {{ $order[0]->id }}/{{ $order[0]->contract_number }}({{ $door[0]->doortype }} {{ $door[0]->door_color }} {{ $door[0]->ornament_model }})</td>
        </tr>
        <tr>
            <td colspan=2>Yuk xati yaratilgan vaqt:{{ date('d.m.Y H:i:s') }}</td>
        </tr>
    </tbody>
</table>
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
            <td class="align-middle">Eshik:{{ $value['name'] }} {{ $value['height'] }}x{{ $value['width'] }}</td>
            <td class="text-center align-middle">{{ $value['count'] }}</td>
        </tr>
        @endforeach
        @if (!empty($jambs))
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
        @endif
        @if (!empty($transoms))
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
        @endif
        @if (!empty($width_boxes))
            @foreach($width_boxes as $key => $value)
                @if (!empty($value['doortype']) && $value['doortype'] != "")
                    <?php $i++; ?>
                    <tr>
                        <td class="text-center align-middle">{{ $i }}</td>
                        <td class="align-middle">Korobka:{{ $value['doortype'] }}({{ $value['size'] }}mm)</td>
                        <td class="text-center align-middle">{{ $value['count'] }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if (!empty($height_boxes))
            @foreach($height_boxes as $key => $value)
                @if (!empty($value['doortype']) && $value['doortype'] != "")
                    <?php $i++; ?>
                    <tr>
                        <td class="text-center align-middle">{{ $i }}</td>
                        <td class="align-middle">Korobka:{{ $value['doortype'] }}({{ $value['size'] }}mm)</td>
                        <td class="text-center align-middle">{{ $value['count'] }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if (!empty($framugas))
            @foreach($framugas as $key => $value)
                @if (!empty($value['figure']) && !empty($value['type']))
                    <?php $i++; ?>
                    <tr>
                        <td class="text-center align-middle" rowspan=2>{{ $i }}</td>
                        <td class="align-middle">Framuga turi:{{ $value['type'] }}({{ $value['height'] }}x{{ $value['width'] }})</td>
                        <td class="text-center align-middle" rowspan=2>{{ $value['count'] }}</td>
                    </tr>
                    <tr>
                        <td class="align-middle">Framuga shakli:{{ $value['figure'] }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if ($burunduq_count != 0)
            <?php $i++; ?>
            <tr>
                <td class="text-center align-middle">{{ $i }}</td>
                <td class="align-middle">Burunduq</td>
                <td class="text-center align-middle">{{ $burunduq_count }}</td>
            </tr>
        @endif
        @if (!empty($crowns))
            @foreach($crowns as $key => $value)
                @if (!empty($value['name']) && $value['name'] != "")
                    <?php $i++; ?>
                    <tr>
                        <td class="text-center align-middle">{{ $i }}</td>
                        <td class="align-middle">{{ $value['name'] }}({{ $value['door_width'] }}mm)</td>
                        <td class="text-center align-middle">{{ $value['total_count'] }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if (!empty($cubes))
            @foreach($cubes as $key => $value)
                @if (!empty($value['name']) && $value['name'] != "")
                    <?php $i++; ?>
                    <tr>
                        <td class="text-center align-middle">{{ $i }}</td>
                        <td class="align-middle">{{ $value['name'] }}</td>
                        <td class="text-center align-middle">{{ $value['total_count'] }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if (!empty($boots))
            @foreach($boots as $key => $value)
                @if (!empty($value['name']) && $value['name'] != "")
                    <?php $i++; ?>
                    <tr>
                        <td class="text-center align-middle">{{ $i }}</td>
                        <td class="align-middle">{{ $value['name'] }}</td>
                        <td class="text-center align-middle">{{ $value['total_count'] }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
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