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
<span>Eshik turi: {{ $door[0]->doortype }}</span><br>
<span>Eshik rangi: {{ $door[0]->door_color }}</span><br>
<span>Naqsh modeli: {{ $door[0]->ornament_model }}</span><br><br>

<!-- Eshik -->
@if(!empty($job_door_results))
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
            @if($table_headers[$i]->en_name == 'jamb')
              <?php $jambs = json_decode($value->{$table_headers[$i]->en_name}, true); ?>
              @if (!is_null($jambs))
                <td class="align-middle">
                  @foreach($jambs as $k => $v)
                    {{ $v['name'] }} ({{ $v['count'] }} ta)
                  @endforeach
                </td>
              @endif
            @else
              <td class="align-middle">{{ $value->{$table_headers[$i]->en_name} }}</td>
            @endif
          @endfor
        </tr>
      @endforeach
        <tr style="font-size:36px !important;">
          <td>Izoh:</td>
          <td colspan="{{ count($table_headers) }}">{{ $order[0]->comments }}</td>
        </tr>
    </tbody>
  </table>
@endif

<!-- Nalichnik -->
@if (!empty($job_jamb_results))
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center align-middle" style="width:15px;">T/r</th>
              <th class="text-center align-middle">Nomi</th>
              <th class="text-center align-middle" style="width:100px;">Soni</th>
            </tr>
          </thead>
          <tbody>
            @foreach($job_jamb_results as $key => $value)
              @if (!empty($value['name']))
                  <tr style="font-size:48px !important;">
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $value['name'] }}</td>
                    <td class="text-center">{{ $value['count'] }}</td>
                  </tr>
              @endif
            @endforeach
            <tr>
              <td>Izoh:</td>
              <td colspan="2">{{ $order[0]->comments }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
@endif

<!-- Dobor -->
@if (!empty($job_transom_results))
  <table class="table table-bordered w-100">
      <thead>
        <tr>
          <th class="text-center align-middle">T/r</th>
          <th class="text-center align-middle">Nomi</th>
          <th class="text-center align-middle">Soni</th>
        </tr>
      </thead>
      <tbody>
        @foreach($job_transom_results as $key => $value)
          @if($value['count'] > 0)
            <tr style="font-size:48px !important;">
              <td class="text-center">{{ $key + 1 }}</td>
              <td>{{ $value['name'] }}({{ $value['size'] }})</td>
              <td class="text-center">{{ $value['count'] }}</td>
            </tr>
          @endif
        @endforeach
        <tr>
          <td>Izoh:</td>
          <td colspan="2">{{ $order[0]->comments }}</td>
        </tr>
      </tbody>
  </table>
@endif

<!-- Korona -->
@if (!empty($job_crown_results))
  <table class="table table-bordered w-100">
      <thead>
        <tr>
          <th class="text-center align-middle">T/r</th>
          <th class="text-center align-middle">Nomi</th>
          <th class="text-center align-middle">Soni</th>
        </tr>
      </thead>
      <tbody>
        @foreach($job_crown_results as $key => $value)
          <tr style="font-size:48px !important;">
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $value['name'] }}</td>
            <td class="text-center">{{ $value['total_count'] }}</td>
          </tr>
        @endforeach
        <tr>
          <td>Izoh:</td>
          <td colspan="2">{{ $order[0]->comments }}</td>
        </tr>
      </tbody>
  </table>
@endif

<!-- Kubik -->
@if (!empty($job_cube_results))
  <table class="table table-bordered w-100">
      <thead>
        <tr>
          <th class="text-center align-middle">T/r</th>
          <th class="text-center align-middle">Nomi</th>
          <th class="text-center align-middle">Soni</th>
        </tr>
      </thead>
      <tbody>
        @foreach($job_cube_results as $key => $value)
          <tr style="font-size:48px !important;">
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $value['name'] }}</td>
            <td class="text-center">{{ $value['total_count'] }}</td>
          </tr>
        @endforeach
        <tr>
          <td>Izoh:</td>
          <td colspan="2">{{ $order[0]->comments }}</td>
        </tr>
      </tbody>
  </table>
@endif

<!-- Sapog -->
@if (!empty($job_boot_results))
  <table class="table table-bordered w-100">
      <thead>
        <tr>
          <th class="text-center align-middle">T/r</th>
          <th class="text-center align-middle">Nomi</th>
          <th class="text-center align-middle">Soni</th>
        </tr>
      </thead>
      <tbody>
        @foreach($job_boot_results as $key => $value)
          <tr style="font-size:48px !important;">
            <td class="text-center">{{ $key + 1 }}</td>
            <td>{{ $value['name'] }}</td>
            <td class="text-center">{{ $value['total_count'] }}</td>
          </tr>
        @endforeach
        <tr>
          <td>Izoh:</td>
          <td colspan="2">{{ $order[0]->comments }}</td>
        </tr>
      </tbody>
  </table>
@endif

<table class="table table-bordered w-100">
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
    @if(isset($door_jobs) && !is_null($door_jobs))
      @foreach($door_jobs as $key => $value)
        @if ($value['job'] != "")
          @if (in_array($value['job_id'],  array(2, 7)))
            <tr>
              <td class="text-center">{{ $tr }}</td>
              <td>{{ $value['job'] }}</td>
              <td>{{ $value['salary']}} so'm</td>
              @if ($key == 1)
                <td class="text-center">{{ $value['framoga_count'] }} </td>
              @else
                <td class="text-center">{{ $value['count'] }} </td>
              @endif
                <td>{{ $value['total_salary'] }} so'm</td>
            </tr>
          @elseif ($value['job_id'] == 1)
            <tr>
              <td class="text-center">{{ $tr }}</td>
              <td>{{ $value['job'] }}</td>
              <td>{{ $value['salary']}} so'm</td>
              @if ($key == 2)
                <td class="text-center">{{ $value['framoga_count'] }} </td>
              @else
                <td class="text-center">{{ $value['count'] }} </td>
              @endif
                <td>{{ $value['total_salary'] }} so'm</td>
            </tr>
          @elseif ($value['job_id'] == 10)
          <tr>
            <td class="text-center">{{ $tr }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary']}} so'm</td>
            @if ($key == 1)
              <td class="text-center">{{ $value['framoga_count'] }} </td>
            @else
              <td class="text-center">{{ $value['glass_count'] }} </td>
            @endif
              <td>{{ $value['total_salary'] }} so'm</td>
          </tr>
          @elseif ($value['job_id'] == 4)
          <tr>
            <td class="text-center">{{ $tr }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary']}} so'm</td>
            @if ($key == 7)
              <td class="text-center">{{ $value['framoga_count'] }} </td>
            @else
              <td class="text-center">{{ $value['count'] }} </td>
            @endif
              <td>{{ $value['total_salary'] }} so'm</td>
          </tr>
          @elseif ($value['job_id'] == 3)
          <tr>
            <td class="text-center">{{ $tr }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary']}} so'm</td>
            @if ($key == 2)
              <td class="text-center">{{ $value['framoga_count'] }} </td>
            @else
              <td class="text-center">{{ $value['count'] }} </td>
            @endif
              <td>{{ $value['total_salary'] }} so'm</td>
          </tr>
          @elseif ($value['job_id'] == 5)
          <tr>
            <td class="text-center">{{ $tr }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary']}} so'm</td>
            @if ($key == 6)
              <td class="text-center">{{ $value['framoga_count'] }} </td>
            @else
              <td class="text-center">{{ $value['count'] }} </td>
            @endif
              <td>{{ $value['total_salary'] }} so'm</td>
          </tr>
          @elseif ($value['job_id'] == 6)
          <tr>
            <td class="text-center">{{ $tr }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary']}} so'm</td>
            @if ($key == 3)
              <td class="text-center">{{ $value['framoga_count'] }} </td>
            @else
              <td class="text-center">{{ $value['count'] }} </td>
            @endif
              <td>{{ $value['total_salary'] }} so'm</td>
          </tr>
          @else
            <tr>
              <td class="text-center">{{ $tr }}</td>
              <td>{{ $value['job'] }}</td>
              <td>{{ $value['salary']}} so'm</td>
              <td class="text-center">{{ $value['count'] }} </td>
              <td>{{ $value['total_salary'] }} so'm</td>
            </tr>
          @endif
          <?php $total_salary += $value['total_salary']; $tr++; ?>
        @endif
      @endforeach
    @endif
    @if(isset($transom_jobs) && !empty($transom_jobs))
      @foreach($transom_jobs as $key => $value)
        @if ($value['job'] != "" && $transom_count != 0)
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
    @if(isset($jamb_jobs) && !empty($jamb_jobs))
      @foreach($jamb_jobs as $key => $value)
        @if ($value['job'] != "" && $jamb_count != 0)
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
    @if(isset($crown_jobs) && !empty($crown_jobs))
      @foreach($crown_jobs as $key => $value)
        @if ($value['job'] != "" && $crown_count != 0)
          <tr>
            <td class="text-center">{{ $tr }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary']}} so'm</td>
            <td class="text-center">{{ $crown_count }} </td>
            <td>{{ $crown_count * $value['salary'] }} so'm</td>
          </tr>
          <?php $total_salary += $crown_count * $value['salary']; $tr++; ?>
        @endif
      @endforeach 
    @endif
    @if(isset($cube_jobs) && !empty($cube_jobs))
      @foreach($cube_jobs as $key => $value)
        @if ($value['job'] != "" && $cube_count != 0)
          <tr>
            <td class="text-center">{{ $tr }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary']}} so'm</td>
            <td class="text-center">{{ $cube_count }} </td>
            <td>{{ $cube_count * $value['salary'] }} so'm</td>
          </tr>
          <?php $total_salary += $cube_count * $value['salary']; $tr++; ?>
        @endif
      @endforeach 
    @endif
    @if(isset($boot_jobs) && !empty($boot_jobs))
      @foreach($boot_jobs as $key => $value)
        @if ($value['job'] != "" && $boot_count != 0)
          <tr>
            <td class="text-center">{{ $tr }}</td>
            <td>{{ $value['job'] }}</td>
            <td>{{ $value['salary']}} so'm</td>
            <td class="text-center">{{ $boot_count }} </td>
            <td>{{ $boot_count * $value['salary'] }} so'm</td>
          </tr>
          <?php $total_salary += $boot_count * $value['salary']; $tr++; ?>
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