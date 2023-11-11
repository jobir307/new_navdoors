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
        <div class="card">
          @if (isset($jamb))
            <form method="POST" action="{{ route('jambs.update', $jamb->id) }}">
            @method('PUT')
          @else
            <form method="POST" action="{{ route('jambs.store') }}">
          @endif
            @csrf
            <div class="card-header">Yangi mahsulot yaratish</div>
            <div class="card-body jamb_content">
              @if (!isset($jamb))
                <div class="row">
                  <div class="col-md-9"></div>
                  <div class="col-md-3">
                    <button type="button" class="btn btn-outline-success jamb_plus" style="float: right; margin-left: 10px;">+</button>
                    <button type="button" class="btn btn-outline-warning jamb_minus" style="float: right;">-</button>
                  </div>
                  <div class="col-md-3"></div>
                </div>
              @endif
              <div class="row jamb_div mt-2">
                <div class="mb-3 col-md-4">
                  <label for="name" class="form-label">Mahsulot nomi</label>
                  <select name="name" id="name" class="form-select">
                    <option></option>
                    @foreach($jambnames as $key => $value)
                      @if (isset($jamb) && $jamb->name == $value->name)
                        <option value="{{ $value->name }}" selected>{{ $value->name }}</option>
                      @else
                        <option value="{{ $value->name }}">{{ $value->name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                @if (isset($jamb))
                  <div class="mb-3 col-md-2">
                    <label for="height" class="form-label">Bo'yi</label>
                    <input class="form-control" type="text" name="height" id="height" autocomplete="off" value="{{ $jamb->height }}" />
                  </div>
                  <div class="mb-3 col-md-2">
                    <label for="width" class="form-label">Eni</label>
                    <input class="form-control" type="text" name="width" id="width" autocomplete="off" value="{{ $jamb->width }}" />
                  </div>
                  <div class="mb-3 col-md-2">
                    <label for="dealer_price" class="form-label">Narxi(diler uchun)</label>
                    <input class="form-control" type="number" name="dealer_price" id="dealer_price" autocomplete="off" value="{{ $jamb->dealer_price }}" />
                  </div>
                  <div class="mb-3 col-md-2">
                    <label for="retail_price" class="form-label">Narxi(xaridor uchun)</label>
                    <input class="form-control" type="number" name="retail_price" id="retail_price" autocomplete="off" value="{{ $jamb->retail_price }}" />
                  </div>
                @else
                  <div class="mb-3 col-md-2">
                    <label for="height" class="form-label">Bo'yi</label>
                    <input class="form-control" type="text" name="height[]" id="height" autocomplete="off" />
                  </div>
                  <div class="mb-3 col-md-2">
                    <label for="width" class="form-label">Eni</label>
                    <input class="form-control" type="text" name="width[]" id="width" autocomplete="off" />
                  </div>
                  <div class="mb-3 col-md-2">
                    <label for="dealer_price" class="form-label">Narxi(diler uchun)</label>
                    <input class="form-control" type="number" name="dealer_price[]" id="dealer_price" autocomplete="off" />
                  </div>
                  <div class="mb-3 col-md-2">
                    <label for="retail_price" class="form-label">Narxi(xaridor uchun)</label>
                    <input class="form-control" type="number" name="retail_price[]" id="retail_price" autocomplete="off" />
                  </div>
                @endif
              </div>
            </div>
            <div class="card-footer">
              <ol style="list-style-type: none;">
                <div class="row">
                  @if (isset($jamb))
                    @foreach($in_array as $key => $value)
                    <div class="col-md-4"></div>
                    <div class="col-md-4 mt-1">
                      <li draggable="true" id="{{ $key }}" class="dropzone" style="padding:15px; border:1px solid #696CFF; margin-bottom:3px;">
                        <input class="form-check-input" type="checkbox" name="jobs[]" id="jobs{{ $value['id'] }}" value="{{ $value['id'] }}" checked />
                        <label for="jobs{{ $value['id'] }}" class="form-check-label" style="margin-left: 10px">{{ $value['name'] }}</label>
                      </li>
                    </div>
                    <div class="col-md-4"></div>
                    @endforeach
                    @foreach($diff_array as $name => $id)
                      <div class="col-md-4"></div>
                      <div class="col-md-4 mt-1">
                        <li draggable="true" id="{{ $id }}" class="dropzone" style="padding:15px; border:1px solid #696CFF; margin-bottom:3px;">
                          <input class="form-check-input" type="checkbox" name="jobs[]" id="jobs{{ $id }}" value="{{ $id }}" />
                          <label for="jobs{{ $id }}" class="form-check-label" style="margin-left: 10px;">{{ $name }}</label>
                        </li>
                      </div>
                      <div class="col-md-4"></div>
                    @endforeach
                  @else
                    @foreach($jobs as $key => $value)
                    <div class="col-md-4"></div>
                    <div class="col-md-4 mt-1">
                      <li draggable="true" id="{{ $key }}" class="dropzone" style="padding:15px; border:1px solid #696CFF; margin-bottom:3px;">
                        <input class="form-check-input" type="checkbox" name="jobs[]" id="jobs{{ $value->name }}" value="{{ $value->id }}" />
                        <label for="jobs{{ $value->name }}" class="form-check-label" style="margin-left: 10px;">{{ $value->name }}</label>
                      </li>
                    </div>
                    <div class="col-md-4"></div>
                    @endforeach
                  @endif
                </div>
              </ol>
              <button type="submit" class="btn btn-primary me-2">Saqlash</button>
            </div>
          </form>
        </div>

        <div class="row jamb_without_labels" style="display:none;">
          <div class="col-md-4"></div>
          <div class="col-md-2">
            <input class="form-control" type="text" name="height[]" id="height" autocomplete="off" />
          </div>
          <div class="col-md-2">
            <input class="form-control" type="text" name="width[]" id="width" autocomplete="off" />
          </div>
          <div class="col-md-2">
            <input class="form-control" type="number" name="dealer_price[]" id="dealer_price" autocomplete="off" />
          </div>
          <div class="col-md-2">
            <input class="form-control" type="number" name="retail_price[]" id="retail_price" autocomplete="off" />
          </div>
        </div>
        @if (isset($jambs))
          <div class="card mt-4">
            <h5 class="card-header">Mahsulotlar ro'yxati</h5>
            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped" id="jamb_table">
                  <thead>
                    <tr>
                      <th style="width: 20px;" class="text-center">T/r</th>
                      <th class="text-center">Nomi</th>
                      <th class="text-center">O'lchami</th>
                      <th class="text-center">Narxi(diler uchun)</th>
                      <th class="text-center">Narxi(xaridor uchun)</th>
                      <th style="width: 140px;"></th>
                    </tr>
                    <tr>
                      <td></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Nomi"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="O'lchami"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Narxi(diler uchun)"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Narxi(xaridor uchun)"></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($jambs as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->height }}x{{ $value->width }}</td>
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

      let table_confirmed = $('#jamb_table').DataTable({
        dom: 'Qlrtp',
        "ordering": false
      });
      table_confirmed.columns().every( function () {
        let column = this;
        $('input', this.header()).on( 'keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });
      let i = 1;
      $('body').on('click', '.jamb_plus', function(){
        let jamb_content = document.getElementsByClassName("jamb_without_labels")[0];
        $('.jamb_content').last().append('<div class="row jamb_div mb-3">'+jamb_content.innerHTML+'</div>');
        i++;
      });

      $('body').on('click', '.jamb_minus', function(){
        if(i>1){
          let childDiv = $('body').find('.jamb_content .row').last();
          childDiv.remove();
          i--;
        }
      });
    });
  </script>
@endsection
