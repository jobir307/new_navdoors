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
	</style>
</head>
<body>
<h4>Shartnoma raqami: {{ $order[0]->id }}/{{ $order[0]->contract_number }} {{ $order[0]->customer_type }}: {{ $order[0]->customer }} tel:{{ $order[0]->phone_number }}({{ $job->name }})</h4>
<span>Topshirish sanasi: {{ date('d.m.Y', strtotime($order[0]->deadline)) }}</span><br>
<span>NS nalichnik rangi: {{ $job_nsjamb_results[0]->nsjamb_color }}</span><br><br>
<table class="table table-bordered w-100">
    <thead>
      <tr>
        <th class="text-center align-middle" style="width:15px;">T/r</th>
        <th class="text-center align-middle">Nomi</th>
        <th class="text-center align-middle">Bo'yi</th>
        <th class="text-center align-middle">Eni tepa</th>
        <th class="text-center align-middle">Eni past</th>
        <th class="text-center align-middle">Soni</th>
      </tr>
    </thead>
    <tbody>
    <?php 
      $total_salary = 0; 
      $nsjamb_count = 0;
    ?>
      @foreach($job_nsjamb_results as $key => $value)
        <tr style="font-size:48px !important;">
          <td class="text-center align-middle">{{ $key + 1 }}</td>
          <td class="text-center align-middle">{{ $value->nsjamb_name }}</td>
          <td class="text-center align-middle">{{ $value->height }}</td>
          <td class="text-center align-middle">{{ $value->width_top }}</td>
          <td class="text-center align-middle">{{ $value->width_bottom }}</td>
          <td class="text-center align-middle">{{ $value->count }}</td>
        </tr>
        <?php 
          $nsjamb_count += $value->count;
        ?>
      @endforeach
        <tr style="font-size:36px !important;">
          <td>Izoh:</td>
          <td colspan="5">{{ $order[0]->comments }}</td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <th class="text-center" style="width: 15px;">T/r</th>
      <th class="text-center">Nomi</th>
      <th class="text-center">Maosh</th>
      <th class="text-center">Soni</th>
      <th class="text-center">Umumiy maosh</th>
    </tr>
  </thead>
  <tbody>
    @if (!empty($nsjamb_jobs))
        @foreach($nsjamb_jobs as $key => $value)
        @if ($value['job'] != "" && $nsjamb_count != 0)
            <tr>
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary'] }} so'm</td>
            <td class="text-center">{{ $nsjamb_count }}</td>
            <td>{{ $nsjamb_count * $value['salary'] }} so'm</td>
            </tr>
            <?php $total_salary += $nsjamb_count * $value['salary']; ?>
        @endif
        @endforeach 
    @endif
    <tr>
      <td></td>
      <td>Jami:</td>
      <td></td>
      <td></td>
      <td>{{ $total_salary }} so'm</td>
    </tr>
  </tbody>
</table>
</body>
</html>