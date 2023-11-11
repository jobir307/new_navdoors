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
<h4>Shartnoma raqami: {{ $order[0]->id }}/{{ $order[0]->contract_number }} {{ $order[0]->customer_type }}: {{ $order[0]->customer }} tel: {{ $order[0]->phone_number }} ({{ $job->name }})</h4>
<span>Topshirish sanasi: {{ date('d.m.Y', strtotime($order[0]->deadline)) }}</span><br>
@if (!empty($jamb_job_results))
    <span>Nalichnik rangi: {{ $jamb_job_results[0]->crown_color }}</span><br><br>
@endif
@if (!empty($crown_job_results))
    <span>Korona rangi: {{ $crown_job_results[0]->crown_color }}</span><br><br>
@endif
@if (!empty($boot_job_results))
    <span>Sapog rangi: {{ $boot_job_results[0]->boot_color }}</span><br><br>
@endif
@if (!empty($cube_job_results))
    <span>Kubik rangi: {{ $cube_job_results[0]->cube_color }}</span><br><br>
@endif
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <table class="table table-bordered w-100">
        <thead>
          <tr>
            <th class="text-center align-middle" style="width:15px;">T/r</th>
            <th class="text-center align-middle">Nomi</th>
            <th class="text-center align-middle" style="width:75px;">Soni</th>
          </tr>
        </thead>
        <tbody>
        <?php 
          $total_salary = 0; 
          $jamb_count = 0;
          $crown_count = 0;
          $boot_count = 0;
          $cube_count = 0;
        ?>
          @if (!empty($jamb_job_results))
            @foreach($jamb_job_results as $key => $value)
                <tr style="font-size:48px !important;">
                <td class="text-center align-middle">{{ $key + 1 }}</td>
                <td class="align-middle">{{ $value->name }}</td>
                <td class="align-middle">{{ $value->count }}</td>
                </tr>
                <?php $jamb_count += $value->count; ?>
            @endforeach
          @endif
          @if (!empty($crown_job_results))
            @foreach($crown_job_results as $key => $value)
                <tr style="font-size:48px !important;">
                <td class="text-center align-middle">{{ $key + 1 }}</td>
                <td class="align-middle">{{ $value->crown_name }}({{ $value->door_width }})</td>
                <td class="align-middle">{{ $value->count }}</td>
                </tr>
                <?php $crown_count += $value->count; ?>
            @endforeach
          @endif
          @if (!empty($boot_job_results))
            @foreach($boot_job_results as $key => $value)
                <tr style="font-size:48px !important;">
                <td class="text-center align-middle">{{ $key + 1 }}</td>
                <td class="align-middle">{{ $value->boot_name }}</td>
                <td class="align-middle">{{ $value->count }}</td>
                </tr>
                <?php $boot_count += $value->count; ?>
            @endforeach
          @endif
          @if (!empty($cube_job_results))
            @foreach($cube_job_results as $key => $value)
                <tr style="font-size:48px !important;">
                <td class="text-center align-middle">{{ $key + 1 }}</td>
                <td class="align-middle">{{ $value->cube_name }}</td>
                <td class="align-middle">{{ $value->count }}</td>
                </tr>
                <?php $cube_count += $value->count; ?>
            @endforeach
          @endif
          <tr style="font-size:36px !important;">
            <td>Izoh:</td>
            <td colspan="2">{{ $order[0]->comments }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-md-2"></div>
  </div>
</div>

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
    @if (!empty($jamb_jobs))
        @foreach($jamb_jobs as $key => $value)
            @if ($value['job'] != "" && $jamb_count != 0)
                <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $value['job'] }}</td>
                <td>{{ $value['salary'] }} so'm</td>
                <td class="text-center">{{ $jamb_count }}</td>
                <td>{{ $jamb_count * $value['salary'] }} so'm</td>
                </tr>
                <?php $total_salary += $jamb_count * $value['salary']; ?>
            @endif
        @endforeach 
    @endif
    @if (!empty($crown_jobs))
        @foreach($crown_jobs as $key => $value)
            @if ($value['job'] != "" && $crown_count != 0)
                <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $value['job'] }}</td>
                <td>{{ $value['salary'] }} so'm</td>
                <td class="text-center">{{ $crown_count }}</td>
                <td>{{ $crown_count * $value['salary'] }} so'm</td>
                </tr>
                <?php $total_salary += $crown_count * $value['salary']; ?>
            @endif
        @endforeach 
    @endif
    @if (!empty($boot_jobs))
        @foreach($boot_jobs as $key => $value)
            @if ($value['job'] != "" && $boot_count != 0)
                <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $value['job'] }}</td>
                <td>{{ $value['salary'] }} so'm</td>
                <td class="text-center">{{ $boot_count }}</td>
                <td>{{ $boot_count * $value['salary'] }} so'm</td>
                </tr>
                <?php $total_salary += $boot_count * $value['salary']; ?>
            @endif
        @endforeach
    @endif
    @if (!empty($cube_jobs))
        @foreach($cube_jobs as $key => $value)
            @if ($value['job'] != "" && $cube_count != 0)
                <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $value['job'] }}</td>
                <td>{{ $value['salary'] }} so'm</td>
                <td class="text-center">{{ $cube_count }}</td>
                <td>{{ $cube_count * $value['salary'] }} so'm</td>
                </tr>
                <?php $total_salary += $cube_count * $value['salary']; ?>
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