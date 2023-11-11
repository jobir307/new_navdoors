@extends('layouts.moderator')
@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="margin-bottom: 150px;">
                    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('moderator') }}" class="fw-light">Naryadlar / </a><a href="{{ route('form-outfit', $waybill[0]->order_id) }}" class="fw-light">Shartnoma ma'lumotlarini boshqarish / </a><span class="fw-light">№{{ $waybill[0]->id }} yuk xati ma'lumotlari</h4>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" rowspan=2>Qayerdan</th>
                                        <th class="text-center align-middle" rowspan=2>Qayerga</th>
                                        <th class="text-center align-middle" colspan=3>Haydovchi</th>
                                        <th class="text-center align-middle" rowspan=2>Qachon jo'natildi</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center align-middle">FIO</th>
                                        <th class="text-center align-middle">Telefon raqami</th>
                                        <th class="text-center align-middle">Mashina</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $waybill[0]->_from }}</td>
                                        <td>{{ $waybill[0]->_to }}</td>
                                        <td>{{ $waybill[0]->driver }}</td>
                                        <td>{{ $waybill[0]->phone_number }}</td>
                                        <td>{{ $waybill[0]->car_model }} {{ $waybill[0]->gov_number }}({{ $waybill[0]->driver_type }})</td>
                                        <td>{{ date("d.m.Y H:i", strtotime($waybill[0]->created_at)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                    @if (!empty($waybill[0]->sended_details))
                        <div class="row mt-3">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center align-middle" rowspan=2 style="width:15px;">T/r</th>
                                            <th class="text-center align-middle" rowspan=2>Nomi</th>
                                            <th class="text-center align-middle" rowspan=2 style="width: 100px;">Soni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(json_decode($waybill[0]->sended_details, true) as $key => $value)
                                        <tr>
                                            <td class="text-center align-middle">{{ $key + 1 }}</td>
                                            <td class="align-middle">{{ $value['name'] }}</td>
                                            <td class="text-center align-middle">{{ $value['count'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <form action="{{ route('moderator-create-waybill') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="waybill_id" value="{{ $waybill[0]->id }}">
                                    <button type="submit" class="btn btn-outline-secondary" style="float:right;">PDFga yuklash</button>
                                </form>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}" type="text/javascript"></script>
@endsection