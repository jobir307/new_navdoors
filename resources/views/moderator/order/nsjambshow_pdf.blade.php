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
            <th class="text-center align-middle">NS nalichnik rangi</th>
            <th class="text-center align-middle">Muddati</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $order[0]->customer }}</td>
            <td>{{ $order[0]->phone_number }}</td>
            <td>{{ $order[0]->id }}/{{ $order[0]->contract_number }}</td>
            <td>{{ $nsjamb_results[0]->nsjamb_color }}</td>
            <td>{{ date("d.m.Y", strtotime($order[0]->deadline)) }}</td>
        </tr>
        <tr>
            <td class="align-middle">Izoh:</td>
            <td class="align-middle" colspan=4>{{ $order[0]->comments }}</td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="text-center">Nomi</th>
            <th class="text-center" style="width:100px">Soni</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nsjamb_results as $key => $value)
        <tr>
            <td class="align-middle">{{ $value->nsjamb_name }}({{ $value->height }}x{{ $value->width_top }}x{{ $value->width_bottom }})</td>
            <td class="align-middle text-center">{{ $value->count }}</td>
        </tr>
        @endforeach
    </tbody>
    </table>
</body>
</html>