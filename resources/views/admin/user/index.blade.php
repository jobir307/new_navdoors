@extends('layouts.admin')
<style>
  .dataTables_paginate {
    float: right !important;
    margin-top: 10px;
  }
</style>
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('dashboard') }}" class="fw-light">Asosiy /</a> Foydalanuvchilar</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <h5 class="card-header">Foydalanuvchi yaratish</h5>
          <div class="card-body">
            @if (isset($user))
              <form method="POST"  action="{{ route('users.update', $user->id) }}">
              @method('PUT')
            @else
              <form method="POST"  action="{{ route('users.store') }}">
            @endif
              @csrf
              <div class="row">
                <div class="mb-3 col-md-3">
                  <label for="name" class="form-label">Login</label>
                  <input class="form-control" type="text" id="name" name="name" autofocus autocomplete="off" value="{{ $user->username ?? ''  }}"/>
                </div>
                <div class="mb-3 col-md-3">
                  <label for="password" class="form-label">Parol</label>
                  <input class="form-control" type="text" name="password" id="password" autocomplete="off" value="{{ $user->_password ?? ''  }}" />
                </div>
                <div class="mb-3 col-md-3">
                  <label for="roles" class="form-label">Role</label>
                  <select id="roles" class="form-select role_select" name="role_id">
                    <option value="0"></option>
                    @foreach($roles as $key => $value)
                      @if(isset($user) && $value->id == $user->role_id)
                        <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                      @else
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                @if(isset($user) && $user->role_id == 8)
                <div class="mb-3 col-md-3 dealer_div" style="display:block;">
                  <label for="dealer" class="form-label">Diler</label>
                  <select id="dealer" class="form-select" name="dealer_id">
                    <option value="0"></option>
                    @foreach($dealers as $key => $value)
                      @if($value->id == $user->dealer_id)
                        <option value="{{ $value->id }}" selected>{{ $value->name }}</option>
                      @else
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
                @else
                <div class="mb-3 col-md-3 dealer_div" style="display:none;">
                  <label for="dealer" class="form-label">Diler</label>
                  <select id="dealer" class="form-select" name="dealer_id">
                    <option value="0"></option>
                    @foreach($dealers as $key => $value)
                      <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                  </select>
                </div>
                @endif
              </div>
              <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">Saqlash</button>
              </div>
            </form>
          </div>
        </div>
        @if (isset($users))
          <div class="card">
            <h5 class="card-header">Foydalanuvchilar ro'yxati</h5>
            <div class="card-body">
              <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped" id="user_table">
                  <thead>
                    <tr>
                      <th style="width: 20px;" class="text-center">T/r</th>
                      <th class="text-center">Login</th>
                      <th class="text-center">Parol</th>
                      <th class="text-center">Role</th>
                      <th class="text-center">Diler</th>
                      <th style="width: 140px;"></th>
                    </tr>
                  </thead>
                  <tbody class="table-border">
                    @foreach($users as $key => $value)
                      <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td><strong>{{ $value->username }}</strong></td>
                        <td>{{ $value->_password }}</td>
                        <td>{{ $value->role }}</td>
                        <td>{{ $value->dealer_name }}</td>
                        <td>
                          <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn_user_delete" data-id="{{ $value->id }}" data-bs-toggle="modal" data-bs-target="#modalCenter">
                            <i class="bx bx-trash-alt"></i>
                          </button>
                          <a href="{{ route('users.edit', $value->id) }}" class="btn-sm btn btn-icon btn-outline-primary">
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
  <script src="{{asset('assets/vendor/libs/popper/popper.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/bootstrap.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/vendor/js/menu.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/js/main.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/datatable/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>
  
  <script type="text/javascript">
    $(document).ready(function() {
      $('body').on('click', '.btn_user_delete', function() {
        let id = $(this).data("id");
        $(".delete_form").attr("action", "users/" + id);
      });

      $('#user_table').DataTable({
        "dom": 'rtp',
        "ordering": false
      });

      $('body').on("change", ".role_select", function(){
        let role = $(this).val(); // role = 8 - diler
        if (role == 8)
          $(".dealer_div").css("display", "block");
        else
          $(".dealer_div").css("display", "none");
      });
    });
  </script>
  
@endsection