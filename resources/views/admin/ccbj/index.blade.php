@extends('layouts.admin')
<style>
  .dataTables_paginate {
    float:right !important;
    margin-top:10px;
  }
</style>
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('dashboard') }}" class="fw-light">Asosiy /</a><span class="text-muted fw-light">Eshik sozlamalari /</span> NKKS</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          @if (isset($result))
            <form method="POST" action="{{ route('ccbjs.update', $result->id) }}">
            @method('PUT')
          @else
            <form method="POST" action="{{ route('ccbjs.store') }}">
          @endif
            @csrf
            <div class="card-header">Nalichnik, korona, kubik va sapogni bir-biriga bog'lash</div>
            <div class="card-body ccbj_content">
              @if (!isset($result))
                <div class="row">
                  <div class="col-md-9"></div>
                  <div class="col-md-3">
                    <button type="button" class="btn btn-outline-success ccbj_plus" style="float: right; margin-left: 10px;">+</button>
                    <button type="button" class="btn btn-outline-warning ccbj_minus" style="float: right;">-</button>
                  </div>
                  <div class="col-md-3"></div>
                </div>
              @endif
              <div class="row ccbj_div mt-2">
                @if (isset($result))
                  <div class="mb-3 col-md-3">
                    <label for="crown" class="form-label">Korona</label>
                    <select class="form-select" name="crown_id" id="crown">
                        <option></option>
                        @foreach($crowns as $key => $value)
                            @if ($result->crown_id == $value->id)
                                <option value="{{ $value->id }}" selected>{{ $value->name }}({{ $value->len }}mm)</option>
                            @else
                                <option value="{{ $value->id }}">{{ $value->name }}({{ $value->len }}mm)</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-3">
                    <label for="cube" class="form-label">Kubik</label>
                    <select class="form-select" name="cube_id" id="cube">
                        @foreach($cubes as $key => $value)
                            @if ($result->cube_id == $value->id)
                                <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                            @else
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-3">
                    <label for="boot" class="form-label">Sapog</label>
                    <select class="form-select" name="boot_id" id="boot">
                        @foreach($boots as $key => $value)
                            @if ($result->boot_id == $value->id)
                                <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                            @else
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="mb-3 col-md-3">
                    <label for="jamb" class="form-label">Nalichnik</label>
                    <select class="form-select" name="jamb_id" id="jamb">
                        @foreach($jambs as $key => $value)
                            @if ($result->jamb_id == $value->id)
                                <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                            @else
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                @else
                    <div class="mb-3 col-md-3">
                        <label for="crown" class="form-label">Korona</label>
                        <select class="form-select" name="crown_id[]" id="crown">
                          <option></option>
                            @foreach($crowns as $key => $value)
                                <option value="{{ $value->id }}">{{ $value->name }}({{ $value->len }}mm)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label for="cube" class="form-label">Kubik</label>
                        <select class="form-select" name="cube_id[]" id="cube">
                            @foreach($cubes as $key => $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label for="boot" class="form-label">Sapog</label>
                        <select class="form-select" name="boot_id[]" id="boot">
                            @foreach($boots as $key => $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label for="jamb" class="form-label">Nalichnik</label>
                        <select class="form-select" name="jamb_id[]" id="jamb">
                            @foreach($jambs as $key => $value)
                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
              </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary">Saqlash</button>
            </div>
          </form>
        </div>

        <div class="row ccbj_without_labels" style="display:none;">
          <div class="col-md-3">
            <select class="form-select" name="crown_id[]" id="crown">
                @foreach($crowns as $key => $value)
                    <option value="{{ $value->id }}">{{ $value->name }}({{ $value->len }}mm)</option>
                @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" name="cube_id[]" id="cube">
                @foreach($cubes as $key => $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" name="boot_id[]" id="boot">
                @foreach($boots as $key => $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" name="jamb_id[]" id="jamb">
                @foreach($jambs as $key => $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-12">
        @if (isset($results))
          <div class="card">
            <h5 class="card-header">NKKS ro'yxati</h5>
            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-hover" id="ccbj_table">
                  <thead>
                    <tr>
                      <th style="width: 20px;" class="text-center">T/r</th>
                      <th class="text-center">Karona</th>
                      <th class="text-center">Kubik</th>
                      <th class="text-center">Sapog</th>
                      <th class="text-center">Nalichnik</th>
                      <th style="width: 140px;"></th>
                    </tr>
                    <tr>
                      <td></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Karona"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Kubik"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Sapog"></td>
                      <td><input class="form-control form-control-sm" type="text" placeholder="Nalichnik"></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($results as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        @if (isset($value->crown_name))
                          <td>{{ $value->crown_name }}({{ $value->crown_len }}mm)</td>
                        @else
                          <td></td>
                        @endif
                        <td>{{ $value->cube_name }}</td>
                        <td>{{ $value->boot_name }}</td>
                        <td>{{ $value->jamb_name }}</td>
                        <td class="text-sm-end">
                          <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn_product_delete" data-id="{{ $value->id }}" data-bs-toggle="modal" data-bs-target="#modalCenter">
                            <i class="bx bx-trash-alt"></i>
                          </button>
                          <a href="{{ route('ccbjs.edit', $value->id) }}" class="btn-sm btn btn-icon btn-outline-primary">
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
        $(".delete_form").attr("action", "ccbjs/" + id);
      });

      let cube_table = $('#ccbj_table').DataTable({
        dom: 'Qlrtp',
        "ordering": false
      });
      cube_table.columns().every( function () {
        let column = this;
        $('input', this.header()).on( 'keyup change', function () {
            column
                .search( this.value )
                .draw();
        });
      });
      let i = 1;
      $('body').on('click', '.ccbj_plus', function(){
        let ccbj_content = document.getElementsByClassName("ccbj_without_labels")[0];
        $('.ccbj_content').last().append('<div class="row ccbj_div mb-3">'+ccbj_content.innerHTML+'</div>');
        i++;
      });

      $('body').on('click', '.ccbj_minus', function(){
        if(i>1){
          let childDiv = $('body').find('.ccbj_content .row').last();
          childDiv.remove();
          i--;
        }
      });
    });
  </script>
@endsection
