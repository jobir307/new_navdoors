@extends('layouts.moderator')
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('moderator') }}" class="fw-light">Naryadlar / </a><span class="fw-light">Naryad ma'lumotlarini ko'rish(nalichnik, korona, kubik va sapog)</h4>
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
                        <th class="text-center align-middle" rowspan=2>Buyurtmachi</th>
                        <th class="text-center align-middle" rowspan=2>Tel.raqami</th>
                        <th class="text-center align-middle" rowspan=2>Shartnoma raqami</th>
                        <th class="text-center align-middle" colspan=4>Rangi</th>
                        <th class="text-center align-middle" rowspan=2>Muddati</th>
                      </tr>
                      <tr>
                        <th class="text-center">Nalichnik</th>
                        <th class="text-center">Korona</th>
                        <th class="text-center">Kubik</th>
                        <th class="text-center">Sapog</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr>
                          <td>{{ $order[0]->customer }}</td>
                          <td>{{ $order[0]->phone_number }}</td>
                          <td>{{ $order[0]->id }}/{{ $order[0]->contract_number }}</td>
                          <td>{{ $jamb_results[0]->jamb_color ?? "" }}</td>
                          <td>{{ $crown_results[0]->crown_color ?? "" }}</td>
                          <td>{{ $cube_results[0]->cube_color ?? "" }}</td>
                          <td>{{ $boot_results[0]->boot_color ?? "" }}</td>
                          <td>{{ date("d.m.Y", strtotime($order[0]->deadline)) }}</td>
                        </tr>
                        <tr>
                          <td class="align-middle">Izoh:</td>
                          <td class="align-middle" colspan=7>{{ $order[0]->comments }}</td>
                        </tr>
                    </tbody>
                  </table>
                </div>
            </div>
          </div>
          <h3 class="text-center text-primary mt-4">Nalichnik, korona, kubik va sapog ma'lumotlari</h3>
          <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                  <div class="table-responsive text-nowrap m-3">
                      <table class="table table-bordered table-hover">
                          <thead>
                            <tr>
                                <th class="text-center">Nomi</th>
                                <th class="text-center">Soni</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (!empty($jamb_results))
                              @foreach($jamb_results as $key => $value)
                              <tr>
                                  <td class="align-middle">{{ $value->name }}</td>
                                  <td class="align-middle text-center">{{ $value->count }}</td>
                              </tr>
                              @endforeach
                            @endif
                            @if (!empty($crown_results))
                              @foreach($crown_results as $key => $value)
                              <tr>
                                  <td class="align-middle">{{ $value->crown_name }}</td>
                                  <td class="align-middle text-center">{{ $value->count }}</td>
                              </tr>
                              @endforeach
                            @endif
                            @if (!empty($cube_results))
                              @foreach($cube_results as $key => $value)
                              <tr>
                                  <td class="align-middle">{{ $value->cube_name }}</td>
                                  <td class="align-middle text-center">{{ $value->count }}</td>
                              </tr>
                              @endforeach
                            @endif
                            @if (!empty($boot_results))
                              @foreach($boot_results as $key => $value)
                              <tr>
                                  <td class="align-middle">{{ $value->boot_name }}</td>
                                  <td class="align-middle text-center">{{ $value->count }}</td>
                              </tr>
                              @endforeach
                            @endif
                          </tbody>
                      </table>
                  </div>
              </div>
              <div class="col-md-3"></div>
          </div>
          <div class="row mb-3">
            <div class="col-md-12">
              <form action="{{ route('crown-boot-cube-show-pdf') }}" method="POST">
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