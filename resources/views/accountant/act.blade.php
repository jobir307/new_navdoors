@extends('layouts.accountant')
<style type="text/css">
    .nav-pills .nav-link.active, .nav-pills .nav-link.active:hover, .nav-pills .nav-link.active:focus {
        color: #fff !important;
    }
</style>
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills mb-3" role="tablist">
                        <?php 
                            $aria_selected = false;
                            $active = "";
                            if ((isset($request) && $request->customer_type=="Yuridik") || !isset($request)){
                                $aria_selected = true;
                                $active = "active";
                            }
                        ?>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link {{$active}}"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-legal-entity"
                                aria-controls="navs-pills-legal-entity"
                                aria-selected="{{ $aria_selected }}"
                            >
                                Yuridik
                            </button>
                        </li>
                        <?php 
                            $aria_selected = false;
                            $active = "";
                            if (isset($request) && $request->customer_type=="Xaridor"){
                                $aria_selected = true;
                                $active = "active";
                            }
                        ?>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link {{ $active }}"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-customer"
                                aria-controls="navs-pills-customer"
                                aria-selected="{{ $aria_selected }}"
                            >
                                Jismoniy
                            </button>
                        </li>
                        <?php 
                            $aria_selected = false;
                            $active = "";
                            if (isset($request) && $request->customer_type=="Diler"){
                                $aria_selected = true;
                                $active = "active";
                            }
                        ?>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link {{ $active }}"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-dealer"
                                aria-controls="navs-pills-dealer"
                                aria-selected="false"
                            >
                                Diler
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <?php 
                            $active = ""; 
                            $show = "";
                            $date_from = "";
                            $date_to = "";
                            if (isset($request)) {
                                if ($request->customer_type == "Yuridik") {
                                    $active = "active"; 
                                    $show = "show";
                                    $date_from = $request->date_from;
                                    $date_to = $request->date_to;
                                }
                            } else {
                                $active = "active"; 
                                $show = "show";
                            }
                        ?>
                        <div class="tab-pane fade {{ $show }} {{ $active }}" id="navs-pills-legal-entity" role="tabpanel">
                            <form action="{{ route('customer-reconciliation-act') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="inn" class="form-label">INN</label>
                                        <input type="text" class="form-control" autocomplete="off" name="inn" value="{{ $request->inn ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inn" class="form-label">Sanadan</label>
                                        <input type="date" class="form-control" autocomplete="off" name="date_from" value="{{ $date_from }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inn" class="form-label">Sanagacha</label>
                                        <input type="date" class="form-control" autocomplete="off" name="date_to" value="{{ $date_to }}">
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <input type="hidden" name="customer_type" value="Yuridik">
                                        <button class="btn btn-outline-primary">Topish</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php 
                            $active = ""; 
                            $show = "";
                            $date_from = "";
                            $date_to = "";
                            if ((isset($request) && $request->customer_type == "Xaridor")) {
                                $active = "active";
                                $show = "show";
                                $date_from = $request->date_from;
                                $date_to = $request->date_to;
                            }
                        ?>
                        <div class="tab-pane fade {{ $show }} {{ $active }}" id="navs-pills-customer" role="tabpanel">
                            <form action="{{ route('customer-reconciliation-act') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="inn" class="form-label">Naryad raqami</label>
                                        <input type="text" class="form-control" autocomplete="off" name="contract_number" value="{{ $request->contract_number ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inn" class="form-label">Sanadan</label>
                                        <input type="date" class="form-control" autocomplete="off" name="date_from" value="{{ $date_from }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inn" class="form-label">Sanagacha</label>
                                        <input type="date" class="form-control" autocomplete="off" name="date_to" value="{{ $date_to }}">
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <input type="hidden" name="customer_type" value="Xaridor">
                                        <button class="btn btn-outline-primary">Topish</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php 
                            $active = ""; 
                            $show = "";
                            $date_from = "";
                            $date_to = "";
                            if ((isset($request) && $request->customer_type == "Diler")) {
                                $active = "active";
                                $show = "show";
                                $date_from = $request->date_from;
                                $date_to = $request->date_to;
                            }
                        ?>
                        <div class="tab-pane fade {{ $show }} {{ $active }}" id="navs-pills-dealer" role="tabpanel">
                            <form action="{{ route('customer-reconciliation-act') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="inn" class="form-label">Diler nomi</label>
                                        <input type="text" class="form-control" autocomplete="off" name="dealer_name" value="{{ $request->dealer_name ?? ''}}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inn" class="form-label">Sanadan</label>
                                        <input type="date" class="form-control" autocomplete="off" name="date_from" value="{{ $date_from }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inn" class="form-label">Sanagacha</label>
                                        <input type="date" class="form-control" autocomplete="off" name="date_to" value="{{ $date_to }}">
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <input type="hidden" name="customer_type" value="Diler">
                                        <button class="btn btn-outline-primary">Topish</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($act_histories))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            @if ($customer_type == "Yuridik")
                            <table class="table table-bordered table-hover w-100" id="act_histories_table">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" rowspan="2" style="width:20px;">T/r</th>
                                        <th class="text-center align-middle" rowspan="2">Buyurtmachi</th>
                                        <th class="text-center align-middle" rowspan="2">INN</th>
                                        <th class="text-center align-middle" rowspan="2" style="max-width: 120px; width: 120px;">Telefon raqami</th>
                                        <th class="text-center align-middle" rowspan="2">Shartnoma raqami</th>
                                        <th class="text-center align-middle" colspan="3">Summa</th>
                                        <th class="text-center align-middle" rowspan="2">Sana</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Shartnoma narxi</th>
                                        <th class="text-center">To'landi</th>
                                        <th class="text-center">Qoldi</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="INN"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma narxi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="To'landi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Qoldi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Sana"></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($act_histories as $key => $value)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $value->customer }}</td>
                                        <td>{{ $value->inn }}</td>
                                        <td>{{ $value->phone_number }}</td>
                                        <td>{{ $value->contract_number }}</td>
                                        <td>{{ number_format($value->last_contract_price, 2, ",", " ") }}</td>
                                        <td>{{ number_format($value->paid, 2, ",", " ") }}</td>
                                        <td>{{ number_format($value->last_contract_price-$value->paid, 2, ",", " ") }}</td>
                                        <td>{{ date("d.m.Y H:i:s", strtotime($value->created_at))}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @else
                            <table class="table table-bordered table-hover w-100" id="act_histories_table">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" rowspan="2" style="width:20px;">T/r</th>
                                        <th class="text-center align-middle" rowspan="2">Buyurtmachi</th>
                                        <th class="text-center align-middle" rowspan="2" style="max-width: 120px; width: 120px;">Telefon raqami</th>
                                        <th class="text-center align-middle" rowspan="2">Shartnoma raqami</th>
                                        <th class="text-center align-middle" colspan="3">Summa</th>
                                        <th class="text-center align-middle" rowspan="2">Sana</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Shartnoma narxi</th>
                                        <th class="text-center">To'landi</th>
                                        <th class="text-center">Qoldi</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Buyurtmachi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Telefon raqami"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma raqami"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Shartnoma narxi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="To'landi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Qoldi"></td>
                                        <td><input class="form-control form-control-sm" type="text" placeholder="Sana"></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($act_histories as $key => $value)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>{{ $value->customer }}</td>
                                            <td>{{ $value->phone_number }}</td>
                                            <td>{{ $value->contract_number }}</td>
                                            <td>{{ number_format($value->last_contract_price, 2, ",", " ") }}</td>
                                            <td>{{ number_format($value->paid, 2, ",", " ") }}</td>
                                            <td>{{ number_format($value->last_contract_price-$value->paid, 2, ",", " ") }}</td>
                                            <td>{{ date("d.m.Y H:i:s", strtotime($value->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

@endsection

@section('scripts')
    <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
    <script src="{{asset('assets/js/main.js')}}"></script>
@endsection