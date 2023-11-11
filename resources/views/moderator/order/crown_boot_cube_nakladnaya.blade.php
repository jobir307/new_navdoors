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
            <td>Naryad raqami:{{ $order[0]->contract_number }}</td>
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
        @if (!empty($jambs))
            @foreach($jambs as $key => $value)
                @if (!empty($value->name) && $value->name != "")
                    <tr>
                        <td class="text-center align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $value->name }}</td>
                        <td class="text-center align-middle">{{ $value->count }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if (!empty($crowns))
            @foreach($crowns as $key => $value)
                @if (!empty($value->crown_name) && $value->crown_name != "")
                    <tr>
                        <td class="text-center align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $value->crown_name }}</td>
                        <td class="text-center align-middle">{{ $value->count }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if (!empty($boots))
            @foreach($boots as $key => $value)
                @if (!empty($value->boot_name) && $value->boot_name != "")
                    <tr>
                        <td class="text-center align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $value->boot_name }}</td>
                        <td class="text-center align-middle">{{ $value->count }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        @if (!empty($cubes))
            @foreach($cubes as $key => $value)
                @if (!empty($value->cube_name) && $value->cube_name != "")
                    <tr>
                        <td class="text-center align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $value->cube_name }}</td>
                        <td class="text-center align-middle">{{ $value->count }}</td>
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