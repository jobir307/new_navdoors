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
	</style>
</head>
<body>
<h4>Naryad raqami: {{ $order[0]->id }} {{ $order[0]->customer_type }}: {{ $order[0]->customer }} tel: {{ $order[0]->phone_number }} ({{ $job->name }})</h4>
<span>Topshirish sanasi: {{ date('d.m.Y', strtotime($order[0]->deadline)) }}</span><br>
<span>Eshik turi: {{ $door[0]->doortype }}</span><br>
<span>Eshik rangi: {{ $door[0]->door_color }}</span><br><br>
<table class="table table-bordered w-100">
    <thead>
      <tr>
        <th class="text-center align-middle" style="width:15px;">T/r</th>
        @for($i = 0; $i < count($table_headers); $i++)
          <th class="text-center align-middle">{{ $table_headers[$i]->name }}</th>
        @endfor
      </tr>
    </thead>
    <tbody>
      @foreach($job_door_results as $key => $value)
        <tr>
          <td class="text-center align-middle">{{ $key + 1 }}</td>
          @for($i = 0; $i < count($table_headers); $i++)  
            <td class="text-center align-middle">{{ $value->{$table_headers[$i]->en_name} }}</td>
          @endfor
        </tr>
      @endforeach
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
    <?php $total_salary = 0; 
      $tr = 1;
    ?>
    @foreach($door_jobs as $key => $value)
      @if ($value['job'] != "")
        <tr>
          <td class="text-center">{{ $tr }}</td>
          <td>{{ $value['job'] }}</td>
          <td>{{ $value['salary']}} so'm</td>
          <td class="text-center">{{ $door_count }} </td>
          <td>{{ $door_count * $value['salary'] }} so'm</td>
        </tr>
        <?php $total_salary += $door_count * $value['salary']; $tr++; ?>
      @endif
    @endforeach 
    @if(isset($transom_jobs) && !is_null($transom_jobs))
      @foreach($transom_jobs as $key => $value)
        @if ($value['job'] != "")
          <tr>
            <td class="text-center">{{ $tr }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary']}} so'm</td>
            <td class="text-center">{{ $transom_count }} </td>
            <td>{{ $transom_count * $value['salary'] }} so'm</td>
          </tr>
          <?php $total_salary += $transom_count * $value['salary']; $tr++; ?>
        @endif
      @endforeach 
    @endif
    @if(isset($jamb_jobs) && !is_null($jamb_jobs))
      @foreach($jamb_jobs as $key => $value)
        @if ($value['job'] != "")
          <tr>
            <td class="text-center">{{ $tr }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary']}} so'm</td>
            <td class="text-center">{{ $jamb_count }} </td>
            <td>{{ $jamb_count * $value['salary'] }} so'm</td>
          </tr>
          <?php $total_salary += $jamb_count * $value['salary']; $tr++; ?>
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