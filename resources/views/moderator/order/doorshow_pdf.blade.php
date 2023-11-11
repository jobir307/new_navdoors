<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <style type="text/css">
		table {
			font-size: 32px !important;
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
	</style>
</head>
<body>
<table class="table table-bordered table-hover" style="font-size:48px !important;">
    <thead>
        <tr>
        <th class="text-center align-middle">Buyurtmachi</th>
        <th class="text-center align-middle">Tel.raqami</th>
        <th class="text-center align-middle">Shartnoma raqami</th>
        <th class="text-center align-middle">Eshik rangi</th>
        <th class="text-center align-middle">Naqsh modeli</th>
        <th class="text-center align-middle">Muddati</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $order[0]->customer }}</td>
            <td>{{ $order[0]->phone_number }}</td>
            <td>{{ $order[0]->id }}/{{ $order[0]->contract_number }}</td>
            <td>{{ $door->door_color }}</td>
            <td>{{ $door->ornament_model }}</td>
            <td>{{ date("d.m.Y", strtotime($order[0]->deadline)) }}</td>
        </tr>
        <tr>
            <td class="align-middle">Izoh:</td>
            <td class="align-middle" colspan=5>{{ $order[0]->comments }}</td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center" style="width:20px;">T/r</th>
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
        @foreach($door_parameters as $key => $value)
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

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center" style="width:400px">Nomi</th>
            <th class="text-center">O'lchami</th>
            <th class="text-center" style="width:100px">Soni</th>
        </tr>
    </thead>
    <tbody>
        @foreach($doortypes as $key => $value)
            @if ($value['count'] != 0)
            <tr>
                <td class="align-middle">{{ $value['name'] }}</td>
                <td class="align-middle">{{ $value['height'] }}x{{ $value['width'] }} ({{ $value['layer'] }} tabaqa)</td>
                <td class="align-middle text-center">{{ $value['count'] }}</td>
            </tr>
            @endif
        @endforeach
        @foreach($depths as $key => $value)
            @if ($value['count'] != 0)
            <tr>
                <td class="align-middle">Karobka qalinligi</td>
                <td class="align-middle">{{ $value['name'] }}</td>
                <td class="align-middle text-center">{{ $value['count'] }}</td>
            </tr>
            @endif
        @endforeach
        @foreach($ornamenttypes as $key => $value)
            @if ($value['count'] != 0)
            <tr>
                <td class="align-middle">Naqsh shakli</td>
                <td class="align-middle">{{ $value['name'] }}</td>
                <td class="align-middle text-center">{{ $value['count'] }}</td>
            </tr>
            @endif
        @endforeach
        @foreach($glasses as $key => $value)
            @if ($value['total_count'] != 0)
            <tr>
                <td rowspan="2" class="align-middle">Shisha</td>
                <td class="align-middle">{{ $value['type'] }}</td>
                <td rowspan="2" class="align-middle text-center">{{ $value['total_count'] }}</td>
            </tr>
            <tr>
                <td class="align-middle">{{ $value['figure'] }}</td>
            </tr>
            @endif
        @endforeach
        @if(!is_null($locktypes))
            @foreach($locktypes as $key => $value)
            @if ($value['count'] != 0 && $value['price'] != 0)
                <tr>
                <td class="align-middle">Qulf turi</td>
                <td class="align-middle">{{ $value['name'] }}</td>
                <td class="align-middle text-center">{{ $value['count'] }}</td>
                </tr>
            @endif
            @endforeach
        @endif
        @if(!is_null($loops))
            @foreach($loops as $key => $value)
            @if ($value['count'] != 0)
                <tr>
                <td class="align-middle">Chaspak</td>
                <td class="align-middle">{{ $value['name'] }}</td>
                <td class="align-middle text-center">{{ $value['count'] }}</td>
                </tr>
            @endif
            @endforeach
        @endif
        <?php $transom_count = 0; ?>
        @foreach($transoms as $key => $value)
            @if ($value['height_count'] != 0)
            <?php $transom_count += $value['width_count']; ?>
            <tr>
                <td rowspan="2" class="align-middle">Dobor</td>
                <td class="align-middle">{{ $value['name'] }} {{ $value['height'] }}x{{ $value['thickness'] }}</td>
                <td class="align-middle text-center">{{ $value['height_count'] }}</td>
            </tr>
            <tr>
                <td class="align-middle">{{ $value['name'] }} {{ $value['width'] }}x{{ $value['thickness'] }} </td>
                <td class="align-middle text-center">{{ $value['width_count'] }}</td>
            </tr>
            @endif
        @endforeach
        @if (!is_null($jambs))
            @foreach($jambs as $k => $v)
            @if ($v['count'] != 0)
                <tr>
                <td class="align-middle">Nalichnik</td>
                <td class="align-middle">{{ $v['name'] }}</td>
                <td class="align-middle text-center">{{ $v['count'] }}</td>
                </tr>
            @endif
            @endforeach
        @endif
        @foreach($door_parameters as $key => $value)
            @if(isset($value['framogatype_name']) && !empty($value['framogatype_name']) && isset($value['framogafigure_name']) && !empty($value['framogafigure_name']) && $value['count'] != 0)
            <tr>
                <td rowspan="2" class="align-middle">Framoga</td>
                <td class="align-middle">{{ $value['framogatype_name'] }}</td>
                <td rowspan="2" class="align-middle text-center">{{ $value['count'] }}</td>
            </tr>
            <tr>
                <td class="align-middle">{{ $value['framogafigure_name'] }}</td>
            </tr>
            @endif
        @endforeach
        @if (!is_null($crowns))
            @foreach($crowns as $k => $v)
                @if (!empty($v['name']) && $v['price'] != 0)
                <tr>
                    <td class="align-middle">Korona</td>
                    <td class="align-middle">{{ $v['name'] }}</td>
                    <td class="align-middle text-center">{{ $v['total_count'] }}</td>
                </tr>
                @endif
            @endforeach
        @endif
        @if (!is_null($cubes))
            @foreach($cubes as $k => $v)
                @if (!empty($v['name']) && $v['price'] != 0)
                <tr>
                    <td class="align-middle">Kubik</td>
                    <td class="align-middle">{{ $v['name'] }}</td>
                    <td class="align-middle text-center">{{ $v['total_count'] }}</td>
                </tr>
                @endif
            @endforeach
        @endif
        @if (!is_null($boots))
            @foreach($boots as $k => $v)
                @if (!empty($v['name']) && $v['price'] != 0)
                <tr>
                    <td class="align-middle">Sapog</td>
                    <td class="align-middle">{{ $v['name'] }}</td>
                    <td class="align-middle text-center">{{ $v['total_count'] }}</td>
                </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>
</body>
</html>