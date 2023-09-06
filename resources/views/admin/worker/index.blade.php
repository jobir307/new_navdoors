@extends('layouts.admin')
<style type="text/css">
  select {
    width: 100%;
    min-height: 100px;
    border-radius: 3px;
    border: 1px solid #444;
    padding: 10px;
    color: #444444;
    font-size: 14px;
  }
  .select2 button {
    border: none;
    padding-right: 5px;
  }
  .dataTables_paginate {
    float: right !important;
    margin-top: 10px;
  }
</style>
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('dashboard') }}" class="fw-light">Asosiy /</a> Xodimlar</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">Yangi xodim yaratish</h5>
          <div class="card-body">
            @if (isset($worker))
              <form method="POST"  action="{{ route('workers.update', $worker->id) }}">
              @method('PUT')
            @else
              <form method="POST"  action="{{ route('workers.store') }}">
            @endif
              @csrf
              <div class="row">
                <div class="mb-3 col-md-4">
                  <label for="name" class="form-label">To'liq ismi</label>
                  <input class="form-control" type="text" id="name" name="fullname" autofocus autocomplete="off" value="{{ $worker->fullname ?? ''  }}"/>
                </div>
                <div class="mb-3 col-md-4">
                  <label for="address" class="form-label">Manzili</label>
                  <input class="form-control" type="text" name="address" id="address" autocomplete="off" value="{{ $worker->address ?? ''  }}" />
                </div>
                <div class="mb-3 col-md-4">
                  <label for="phone_number" class="form-label">Telefon raqami</label>
                  <input class="form-control" type="text" name="phone_number" id="phone_number" autocomplete="off" value="{{ $worker->phone_number ?? ''  }}" />
                </div>
                <div class="mb-3 col-md-12">
                  <label for="jobs" class="form-label">Lavozimlari</label>
                  <select id="jobs" class="form-control" name="job_id[]" multiple>
                    <option value="0"></option>
                    @foreach($jobs as $key => $value)
                      @if (isset($worker_jobs) && in_array($value->id, $worker_jobs))
                        <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                      @else
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">Saqlash</button>
              </div>
            </form>
          </div>
        </div>
        @if (isset($workers))
          <div class="card">
            <h5 class="card-header">Xodimlar ro'yxati</h5>
            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped" id="worker_table">
                  <thead>
                    <tr>
                      <th style="width: 20px;">T/r</th>
                      <th>To'liq ismi</th>
                      <th>Manzili</th>
                      <th>Telefon raqami</th>
                      <th>Lavozimi</th>
                      <th style="width: 130px;"></th>
                    </tr>
                  </thead>
                  <tbody class="table-border">
                    @foreach($workers as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->fullname }}</td>
                        <td>{{ $value->address }}</td>
                        <td>{{ $value->phone_number }}</td>
                        <td>{{ $value->jobs }}</td>
                        <td>
                          <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn_worker_delete" data-id="{{ $value->id }}" data-bs-toggle="modal" data-bs-target="#modalCenter">
                            <i class="bx bx-trash-alt"></i>
                          </button>
                          <a href="{{ route('workers.edit', $value->id) }}" class="btn-sm btn btn-icon btn-outline-primary">
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
                    <label class="">Rostdan ham bu foydalanuvchini o'chirmoqchimisiz ?</label>
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
  <script src="{{asset('assets/js/slim.min.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/js/select2.min.js')}}"></script>
  <script src="{{asset('assets/vendor/js/menu.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/js/main.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>
  
  <script type="text/javascript">
    $(document).ready(function() {
      $('body').on('click', '.btn_worker_delete', function() {
        let id = $(this).data("id");
        $(".delete_form").attr("action", "workers/" + id);
      });

      $("#worker_table").DataTable({
        "dom": 'rtp',
        "ordering": false
      });
    });

    $(function() {
      $('select').each(function () {
        $(this).select2({
          theme: 'bootstrap4',
          width: 'style',
          placeholder: $(this).attr('placeholder'),
          allowClear: Boolean($(this).data('allow-clear')),
        });
      });
    });
  </script>
@endsection