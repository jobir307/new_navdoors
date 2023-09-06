@extends('layouts.admin')
<style>
  .dataTables_paginate {
    float: right !important;
    margin-top: 10px;
  }
</style>
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('dashboard') }}" class="fw-light">Asosiy /</a><span class="text-muted fw-light">Eshik sozlamalari /</span> Naqsh shakllari</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">Yangi mahsulot yaratish</div>
          <div class="card-body">
            @if (isset($ornamenttype))
              <form method="POST"  action="{{ route('ornamenttypes.update', $ornamenttype->id) }}">
              @method('PUT')
            @else
              <form method="POST"  action="{{ route('ornamenttypes.store') }}">
            @endif
              @csrf
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="name" class="form-label">Mahsulot nomi</label>
                  <input class="form-control" type="text" id="name" name="name" autofocus autocomplete="off" value="{{ $ornamenttype->name ?? ''  }}" />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="price" class="form-label">Narxi</label>
                  <input class="form-control" type="number" name="price" id="price" autocomplete="off" value="{{ $ornamenttype->price ?? ''  }}" />
                </div>
              </div>
              <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">Saqlash</button>
              </div>
            </form>
          </div>
        </div>
        @if (isset($ornamenttypes))
          <div class="card">
            <h5 class="card-header">Mahsulotlar ro'yxati</h5>
            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped" id="ornamenttype_table">
                  <thead>
                    <tr>
                      <th style="width: 20px;">T/r</th>
                      <th>Nomi</th>
                      <th>Narxi</th>
                      <th style="width: 140px;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($ornamenttypes as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->price }} so'm</td>
                        <td class="text-sm-end">
                          <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn_product_delete" data-id="{{ $value->id }}" data-bs-toggle="modal" data-bs-target="#modalCenter">
                            <i class="bx bx-trash-alt"></i>
                          </button>
                          <a href="{{ route('ornamenttypes.edit', $value->id) }}" class="btn-sm btn btn-icon btn-outline-primary">
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
                    <label class="">Rostdan ham bu mahsulotni o'chirmoqchimisiz ?</label>
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
      $('body').on('click', '.btn_product_delete', function() {
        let id = $(this).data("id");
        $(".delete_form").attr("action", "ornamenttypes/" + id);
      });

      $("#ornamenttype_table").DataTable({
        "dom": 'rtp',
        "ordering": false
      });
    });
  </script>
@endsection
