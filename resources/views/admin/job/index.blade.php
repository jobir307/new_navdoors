@extends('layouts.admin')
<style>
  .dataTables_paginate {
    float: right !important;
    margin-top: 10px;
  }
</style>
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('dashboard') }}" class="fw-light">Asosiy /</a> Lavozimlar</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="mb-3 col-md-9"></div>
          <div class="mb-3 col-md-3">
            <a href="{{ route('jobs.create') }}" class="btn btn-primary" style="float: right;">Yaratish</a>
          </div>
        </div>
        <div class="card">
          <h5 class="card-header">Lavozimlar ro'yxati</h5>
          <div class="card-body">
            <div class="table-responsive text-nowrap">
              <table class="table table-bordered table-striped" id="job_table">
                <thead>
                  <tr>
                    <th style="width: 20px;">T/r</th>
                    <th>Nomi</th>
                    <th style="width: 140px;"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($jobs as $key => $value)
                    <tr>
                      <td class="text-center">{{ $key + 1 }}</td>
                      <td>{{ $value->name }}</td>
                      <td class="text-sm-end">
                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn_product_delete" data-id="{{ $value->id }}" data-bs-toggle="modal" data-bs-target="#modalCenter">
                          <i class="bx bx-trash-alt"></i>
                        </button>
                        <a href="{{ route('jobs.edit', $value->id) }}" class="btn-sm btn btn-icon btn-outline-primary">
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
      $('body').on('click', '.btn_product_delete', function() {
        let id = $(this).data("id");
        $(".delete_form").attr("action", "jobs/" + id);
      });
      
      $('#job_table').DataTable({
        "dom": 'rtp',
        "ordering": false
      });
    });
  </script>

@endsection
