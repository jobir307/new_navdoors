@extends('layouts.admin')
<style>
  .dataTables_paginate {
    float: right !important;
    margin-top: 10px;
  }
</style>
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('dashboard') }}" class="fw-light">Asosiy / </a>Buyurtmachilar </h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">Yangi buyurtmachi yaratish</div>
          <div class="card-body">
            @if (isset($customer))
              <form method="POST"  action="{{ route('customers.update', $customer->id) }}">
              @method('PUT')
            @else
              <form method="POST"  action="{{ route('customers.store') }}">
            @endif
              @csrf
              <div class="row">
                <div class="mb-3 col-md-3">
                  <label for="name" class="form-label">F.I.O</label>
                  <input class="form-control" type="text" id="name" name="name" autofocus autocomplete="off" value="{{ $customer->name ?? ''  }}" />
                </div>
                <div class="mb-3 col-md-2">
                  <label for="type" class="form-label">Turi</label>
                  <?php  
                    $types = ['Xaridor', 'Diler', "Yuridik"]; 
                  ?>
                  <select class="form-select customer_type" name="type" id="type">
                    @foreach($types as $value)
                      @if (isset($customer) && $customer->type == $value)
                        <option value="{{ $value }}" selected>{{ $value }}</option>
                      @else
                        <option value="{{ $value }}">{{ $value }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                <div class="mb-3 col-md-2">
                  <label for="phone_number" class="form-label">Tel.raqami</label>
                  <input class="form-control" type="text" name="phone_number" id="phone_number" autocomplete="off" value="{{ $customer->phone_number ?? ''  }}" />
                </div>
                <div class="mb-3 col-md-3">
                  <label for="address" class="form-label">Manzili</label>
                  <input class="form-control" type="text" name="address" id="address" autocomplete="off" value="{{ $customer->address ?? ''  }}" />
                </div>
                <div class="mb-3 col-md-2 inn_div" style="display:none;">
                  <label for="inn" class="form-label">INN</label>
                  <input class="form-control" type="text" name="inn" id="inn" autocomplete="off" value="{{ $customer->inn ?? ''  }}" />
                </div>
              </div>
              <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">Saqlash</button>
              </div>
            </form>
          </div>
        </div>
        @if (isset($customers))
          <div class="card">
            <h5 class="card-header">Buyurtmachilar ro'yxati</h5>
            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped" id="customer_table">
                  <thead>
                    <tr>
                      <th style="width: 20px;">T/r</th>
                      <th>F.I.O</th>
                      <th>Turi</th>
                      <th>INN</th>
                      <th>Telefon raqami</th>
                      <th>Manzili</th>
                      <th style="width: 140px;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($customers as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->type }}</td>
                        <td>{{ $value->inn }}</td>
                        <td>{{ $value->phone_number }}</td>
                        <td>{{ $value->address }}</td>
                        <td class="text-sm-end">
                          <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn_customer_delete" data-id="{{ $value->id }}" data-bs-toggle="modal" data-bs-target="#modalCenter" title="O'chirish">
                            <i class="bx bx-trash-alt"></i>
                          </button>
                          <a href="{{ route('customers.edit', $value->id) }}" class="btn-sm btn btn-icon btn-outline-primary" title="O'zgartirish">
                            <i class="bx bx-pencil"></i>
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @endif

        <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col mb-3">
                    <label class="">Rostdan ham bu ma'lumotni o'chirmoqchimisiz ?</label>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Yo'q
                </button>
                <form action="" method="POST" class="delete_form">
                  @csrf
                  @method("DELETE")
                  <button type="submit" class="btn btn-primary">Ha</button>
                </form>
              </div>
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
  <script src="{{asset('assets/vendor/js/menu.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/js/main.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>
  
  <script type="text/javascript">
    $(document).ready(function() {
      $('body').on('click', '.btn_customer_delete', function() {
        let id = $(this).data("id");
        $(".delete_form").attr("action", "customers/" + id);
      });

      $("#customer_table").DataTable({
        "dom": 'rtp',
        "ordering": false
      });

      $('body').on('change', ".customer_type", function(){
        let val = $(this).val();
        if (val == "Yuridik")
          $("div.inn_div").css("display", "block");
        else
          $("div.inn_div").css("display", "none");
      });
    });
  </script>

@endsection
