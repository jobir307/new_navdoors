@extends('layouts.moderator')
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('moderator') }}" class="fw-light">Naryadlar / </a><span class="fw-light">Naryad ma'lumotlarini ko'rish(eshik)</h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">№{{ $order[0]->id }}/{{ $order[0]->contract_number }} naryad ma'lumotlari</h5>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive text-nowrap m-3">
                            <table class="table table-bordered table-hover">
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
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="table-responsive text-nowrap m-3">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" style="width: 20px;" rowspan=2>T/r</th>
                                        <th class="text-center align-middle" rowspan=2>Bo'yi</th>
                                        <th class="text-center align-middle" rowspan=2>Eni</th>
                                        <th class="text-center align-middle" rowspan=2>Soni</th>
                                        <th class="text-center align-middle" rowspan=2>L-P</th>
                                        <th class="text-center align-middle" rowspan=2>Devor qalinligi</th>
                                        <th class="text-center align-middle" rowspan=2>Karobka o'lchami</th>
                                        <th class="text-center align-middle" rowspan=2>Karobka qalinligi</th>
                                        <th class="text-center align-middle" colspan=2>Dobor</th>
                                        <th class="text-center align-middle" rowspan=2>Tabaqaligi</th>
                                        <th class="text-center align-middle" rowspan=2>Porog</th>
                                        <th class="text-center align-middle" rowspan=2>Naqsh shakli</th>
                                        <th class="text-center align-middle" rowspan=2>Qulf turi</th>
                                        <th class="text-center align-middle" rowspan=2>Nalichnik</th>
                                        <th class="text-center align-middle" rowspan=2>Korona</th>
                                        <th class="text-center align-middle" rowspan=2>Kubik va sapog</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center align-middle">Nomi</th>
                                        <th class="text-center align-middle">Tomoni</th>
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
                                        <td class="text-center">{{ $value['transom_side'] ?? '' }}</td>
                                        <td class="text-center">{{ $value['layer'] }}</td>
                                        <td>{{ $value['doorstep']  ?? '' }}</td>
                                        <td>{{ $value['ornamenttype'] }}</td>
                                        <td>{{ $value['locktype'] }}</td>
                                        <td class="text-center">{{ $value['jamb_side'] ?? "" }}</td>
                                        <td class="text-center">{{ $value['crown_side'] ?? "" }}</td>
                                        <td class="text-center">{{ $value['cube_side'] ?? "" }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive text-nowrap m-3">
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
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form action="{{ route('door-show-pdf') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order[0]->id }}">
                            <button class="btn btn-secondary m-3 float-end">PDFga yuklash</button>
                        </form>
                        @if ($order[0]->moderator_receive == 0)
                        <form action="{{ route('start-process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id"  value="{{ $order[0]->id }}">
                            <button type="submit" class="btn btn-primary m-3 float-end">Boshlash</button>
                        </form>
                        <form action="{{ route('redirect-order-to-manager') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order[0]->id }}">
                            <button class="btn btn-warning m-3 float-end">Naryadni qaytarib yuborish</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
@endsection