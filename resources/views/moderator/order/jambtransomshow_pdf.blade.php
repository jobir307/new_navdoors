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
            <th class="text-center align-middle" rowspan=2>Buyurtmachi</th>
            <th class="text-center align-middle" rowspan=2>Tel.raqami</th>
            <th class="text-center align-middle" rowspan=2>Shartnoma raqami</th>
            <th class="text-center align-middle" colspan=2>Rangi</th>
            <th class="text-center align-middle" rowspan=2>Muddati</th>
        </tr>
        <tr>
            <th class="text-center">Dobor</th>
            <th class="text-center">Nalichnik</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $order[0]->customer }}</td>
            <td>{{ $order[0]->phone_number }}</td>
            <td>{{ $order[0]->id }}/{{ $order[0]->contract_number }}</td>
            <td>{{ $transom_results[0]->transom_color }}</td>
            <td>{{ $jamb_results[0]->jamb_color }}</td>
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
            <th class="text-center">Nomi</th>
            <th class="text-center">Soni</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transom_results as $key => $value)
        <tr>
            <td class="align-middle">{{ $value->name }}({{ $value->height }}x{{ $value->width_top }}x{{ $value->width_bottom }})</td>
            <td class="align-middle text-center">{{ $value->count }}</td>
        </tr>
        @endforeach
        @foreach($jamb_results as $key => $value)
        <tr>
            <td class="align-middle">{{ $value->name }}</td>
            <td class="align-middle text-center">{{ $value->count }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>