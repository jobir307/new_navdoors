@extends('layouts.admin')
<style>
  .dataTables_paginate {
    float: right !important;
    margin-top: 10px;
  }
</style>
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('dashboard') }}" class="fw-light">Asosiy /</a><span class="text-muted fw-light">Eshik sozlamalari /</span> Nalichniklar</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">Yangi mahsulot yaratish</div>
          <div class="card-body">
            @if (isset($jamb))
              <form method="POST"  action="{{ route('jambs.update', $jamb->id) }}">
              @method('PUT')
            @else
              <form method="POST"  action="{{ route('jambs.store') }}">
            @endif
              @csrf
              <div class="row">
                <div class="mb-3 col-md-3">
                  <label for="name" class="form-label">Mahsulot nomi</label>
                  <input class="form-control" type="text" id="name" name="name" autofocus autocomplete="off" value="{{ $jamb->name ?? ''  }}" />
                </div>
                <div class="mb-3 col-md-2">
                  <label for="doortype_id" class="form-label">Eshik turi</label>
                  <select class="form-select" id="doortype_id" name="doortype_id">
                    @foreach($doortypes as $key => $value)
                      @if(isset($jamb) && $jamb->doortype_id == $value->id)
                        <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                      @else
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endif
                    @endforeach 
                  </select>
                </div>
                <div class="mb-3 col-md-2">
                  <label for="dealer_price" class="form-label">Narxi(diler uchun)</label>
                  <input class="form-control" type="number" name="dealer_price" id="dealer_price" autocomplete="off" value="{{ $jamb->dealer_price ?? ''  }}" />
                </div>
                <div class="mb-3 col-md-2">
                  <label for="retail_price" class="form-label">Narxi(xaridor uchun)</label>
                  <input class="form-control" type="number" name="retail_price" id="retail_price" autocomplete="off" value="{{ $jamb->retail_price ?? ''  }}" />
                </div>
                <div class="mb-3 col-md-3">
                  <ol style="list-style-type: none;">
                    <label class="form-label">Lavozimlar</label>
                    @foreach($jobs as $key => $value)
                      @if (isset($jamb_jobs) && in_array($value->id, $jamb_jobs))
                        <li draggable="true" id="{{ $key }}" class="dropzone" style="padding:15px; border:1px solid #696CFF; margin-bottom:3px;">
                          <input class="form-check-input" type="checkbox" name="jobs[]" id="jobs{{ $value->id }}" value="{{ $value->id }}" checked />
                          <label for="jobs{{ $value->id }}" class="form-check-label" style="margin-left: 10px">{{ $value->name }}</label>
                        </li>
                      @else
                        <li draggable="true" id="{{ $key }}" class="dropzone" style="padding:15px; border:1px solid #696CFF; margin-bottom:3px;">
                          <input id="{{ $value->name . $key }}" class="form-check-input" type="checkbox" name="jobs[]" id="jobs{{ $value->id }}" value="{{ $value->id }}" />
                          <label for="{{ $value->name . $key }}" class="form-check-label" style="margin-left: 10px;">{{ $value->name }}</label>
                        </li>
                      @endif
                    @endforeach
                  </ol>
                </div>
              </div>
              <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">Saqlash</button>
              </div>
            </form>
          </div>
        </div>
        @if (isset($jambs))
          <div class="card">
            <h5 class="card-header">Mahsulotlar ro'yxati</h5>
            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped" id="jamb_table">
                  <thead>
                    <tr>
                      <th style="width: 20px;" class="text-center">T/r</th>
                      <th class="text-center">Nomi</th>
                      <th class="text-center">Eshik turi</th>
                      <th class="text-center">Narxi(diler uchun)</th>
                      <th class="text-center">Narxi(xaridor uchun)</th>
                      <th style="width: 140px;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($jambs as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->doortype }}</td>
                        <td>{{ $value->dealer_price }} so'm</td>
                        <td>{{ $value->retail_price }} so'm</td>
                        <td class="text-sm-end">
                          <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn_product_delete" data-id="{{ $value->id }}" data-bs-toggle="modal" data-bs-target="#modalCenter">
                            <i class="bx bx-trash-alt"></i>
                          </button>
                          <a href="{{ route('jambs.edit', $value->id) }}" class="btn-sm btn btn-icon btn-outline-primary">
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
    let dragged;
    let id;
    let index;
    let indexDrop;
    let list;

    document.addEventListener("dragstart", ({ target }) => {
      dragged = target;
      id = target.id;
      list = target.parentNode.children;
      for (let i = 0; i < list.length; i++) {
        if (list[i] === dragged) {
          index = i;
        }
      }
    });

    document.addEventListener("dragover", (event) => {
      event.preventDefault();
    });

    document.addEventListener("drop", ({ target }) => {
      if (target.className == "dropzone" && target.id !== id) {
        dragged.remove(dragged);
        for (let i = 0; i < list.length; i++) {
          if (list[i] === target) {
            indexDrop = i;
          }
        }
        if (index > indexDrop) {
          target.before(dragged);
        } else {
          target.after(dragged);
        }
      }
    });
    $(document).ready(function() {
      $('body').on('click', '.btn_product_delete', function() {
        let id = $(this).data("id");
        $(".delete_form").attr("action", "jambs/" + id);
      });

      $("#jamb_table").DataTable({
        "dom": 'rtp',
        "ordering": false
      });
    });
  </script>
@endsection
