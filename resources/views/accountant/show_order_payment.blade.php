@extends('layouts.accountant')
@section('content')
  <div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('accountant') }}" class="fw-light">Shartnomalar / </a><span class="text-muted fw-light">To'lovlar tarixi</span></h4>
    <div class="row">
      <div class="col-md-12">
        @if (isset($orders) && !empty($orders))
          <div class="card">
            <h5 class="card-header">Shartnoma raqami №{{ $orders[0]->contract_number }} bo'yicha to'lovlar tarixi</h5>
            <div class="card-body">
                <div class="table-responsive text-nowrap m-3">
                    <table class="table table-bordered table-striped" >
                        <thead>
                        <tr>
                            <th class="text-center align-middle" style="width: 20px;" rowspan="3">T/r</th>
                            <th class="text-center align-middle" rowspan="3">Xaridor</th>
                            <th class="text-center align-middle" colspan="5">Summa</th>
                            <th class="text-center align-middle" rowspan="3" style="width: 140px !important;">Sana</th>
                        </tr>
                        <tr>
                            <th class="text-center align-middle" rowspan="2">Umumiy</th>
                            <th class="text-center align-middle" colspan="3">To'landi</th>
                            <th class="text-center align-middle" rowspan="2">Qoldi</th>
                        </tr>
                        <tr>
                            <th class="text-center">Raqamlarda</th>
                            <th class="text-center">So'z bilan</th>
                            <th class="text-center align-middle" rowspan="3">To'lov turi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $key => $value)
                            <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $value->customer }}</td>
                            <td>{{ number_format($value->contract_price, 2, ",", " ") }} сум</td>
                            <td>{{ is_null($value->payed) ? 0 : number_format($value->payed, 2, ",", " ") }} сум</td>
                            <td>{{ $value->in_words }}</td>
                            <td>{{ $value->payment_type }}</td>
                            <td>{{ is_null($value->debt) ? 0 : number_format($value->debt, 2, ",", " ") }} сум</td>
                            <td>{{ date('d.m.Y H:i:s', strtotime($value->created_at)) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>
@endsection
