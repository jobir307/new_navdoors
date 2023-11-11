@extends('layouts.cashier')
<style type="text/css">
  thead input {
      width: 100%;
      padding: 10px 3px;
      box-sizing: border-box;
      border-radius: 8px;
      border: 1px solid #F2F2F2;
  }
  thead input:focus {
    border-color: #696CFF;
    outline: none;
    font-weight: 500;
  }
  .offcanvas-end {
    width: 800px !important;
  }
</style>
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('cashier', $date=date('Y-m-d')) }}" class="fw-light">Asosiy / </a><span class="text-muted fw-light">Chiqim</span></h4>
    <div class="row">
      <div class="col-md-12">
        <div class="row mb-3">
          <div class="col-md-4">
            <select class="form-select inout_type" name="inout_type" onchange="document.location.href = '/outs/' + this.value">
              @foreach($inout_types as $key => $value)
                @if($value->id == $inout_type)
                  <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                @else
                  <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endif
              @endforeach
            </select>
          </div>
          <div class="col-md-4"></div>
          <div class="col-md-4">
            <button type="button" 
                    data-bs-target="#createinsmodal" 
                    data-bs-toggle="offcanvas"
                    aria-controls="createinsmodal"
                    class="btn btn-primary" 
                    style="float: right;"
            >
              Yaratish
            </button>
          </div>
        </div>
        @if(isset($outs) && !empty($outs))
          <div class="card">
            <h5 class="card-header">Chiqimlar ro'yxati</h5>
            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover" id="outs_table">
                  <thead>
                    <tr>
                      <th class="text-center align-middle" style="width: 20px;" rowspan="2">T/r</th>
                      <th class="text-center align-middle" rowspan="2">Oluvchi</th>
                      <th class="text-center align-middle" colspan="2">Summa</th>
                      <th class="text-center align-middle" rowspan="2">To'lov shakli</th>
                      <th class="text-center align-middle" rowspan="2">Javobgar</th>
                      <th class="text-center align-middle" rowspan="2">To'lovchi</th>
                      <th class="text-center align-middle" rowspan="2">Sana</th>
                    </tr>
                    <tr>
                      <th class="text-center align-middle">Raqamlarda</th>
                      <th class="text-center align-middle">So'z bilan</th>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Raqamlarda"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="So'z bilan"></td>
                      <td></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Javobgar"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="To'lovchi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Sana"></td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($outs as $key => $value)
                      <tr>
                        <td class="text-center">{{ $value->id }}</td>
                        <td>{{ $value->reason }}</td>
                        <td>{{ number_format($value->amount, 2, ",", " ") }} сум</td>
                        <td>{{ $value->in_words }}</td>
                        <td>{{ $value->payment_type }}</td>
                        <td>{{ $value->responsible }}</td>
                        <td>{{ $value->payer }}</td>
                        <td>{{ $value->day }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @else
          <div class="card">
            <h5 class="card-header text-secondary">Ma'lumot topilmadi!</h5>
          </div>
        @endif

        <div class="offcanvas offcanvas-end" id="createinsmodal" data-bs-scroll="true" tabindex="-1" aria-hidden="true">
          <form action="{{ route('create-stock-outs') }}" method="POST">
            @csrf
            <div class="offcanvas-header">
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close"
              ></button>
            </div>
            <div class="offcanvas-body">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="inout_type">Chiqim turi</label>
                    <select class="form-select" id="inout_type" name="inout_type">
                      @foreach($inout_types as $key => $value)
                        @if($value->id != 1)
                          <option value="{{ $value->id }}">{{ $value->name }}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="payment_type">To'lov turi</label>
                    <select class="form-select" id="payment_type" name="payment_type">
                      @foreach($payment_types as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="amount">Pul summasi</label>
                    <input type="number" name="amount" id="amount" class="form-control">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label" for="responsible">Javobgar shaxs</label>
                    <input type="text" name="responsible" id="responsible" class="form-control" autocomplete="off">
                  </div>
                  <div class="col-md-12 mb-3">
                    <label class="form-label" for="payer">To'lovchi</label>
                    <input type="text" name="payer" id="payer" class="form-control" autocomplete="off">
                  </div>
                  <div class="col-md-12">
                    <label class="form-label" for="reason">Sabab</label>
                    <textarea class="form-control" name="reason" id="reason" rows="4"></textarea>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Saqlash</button>
            </div>
          </form>
        </div>
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

  <script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
  <script src="{{asset('assets/vendor/js/menu.js')}}"></script>
  
  <script src="../assets/js/main.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      let table = $('#outs_table').DataTable({
        dom: 'Qrltp',
        lengthMenu: [
            [25, 50, 100],
            [25, 50, 100]
        ],
        "ordering": false
      });

      table.columns().every( function () {
        var column = this;
     
        $( 'input', this.header() ).on( 'keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });
    });
  </script>
@endsection
