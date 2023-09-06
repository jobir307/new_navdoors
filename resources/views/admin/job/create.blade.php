@extends('layouts.admin')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('dashboard') }}" class="fw-light">Asosiy / </a> <a href="{{ route('jobs.index') }}" class="fw-light">Lavozimlar / </a>Yangi lavozim yaratish</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">Yangi lavozim yaratish</div>
          <div class="card-body">
            <form method="POST"  action="{{ route('jobs.store') }}">
              @csrf
              <div class="row">
                <div class="mb-3 col-md-8">
                  <label for="name" class="form-label">Lavozim nomi</label>
                  <input class="form-control" type="text" id="name" name="name" autofocus autocomplete="off" />
                </div>
                <div class="mb-3 col-md-4">
                  <ol style="list-style-type: none;">
                    <label for="name" class="form-label">Eshik atributlari</label>
                    @foreach($door_attributes as $key => $value)
                      <li draggable="true" id="{{ $key }}" class="dropzone" style="padding:15px; border:1px solid #696CFF; margin-bottom:3px;">
                        <input class="form-check-input" type="checkbox" name="door_attributes[]" id="door_attributes{{ $value->id }}" value="{{ $value->en_name }}"/>
                        <label for="door_attributes{{ $value->id }}" class="form-check-label" style="margin-left: 10px; ">{{ $value->name }}</label>
                      </li>
                    @endforeach
                  </ol>
                </div>
              </div>
              <div class="row d-flex">
                <div class="col-md-12 door_content">
                  <div class="text-center text-primary display-6">Eshik tayyorlash uchun maosh tayinlash</div>
                  <div class="row">
                    <div class="col-md-10"></div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-sm btn-outline-success mt-4 door_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                      <button type="button" class="btn btn-sm btn-outline-warning mt-4 door_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                    </div>
                  </div>
                  <div class="row door_div">
                    <div class="col-md-6">
                      <label class="form-label">Vazifa</label>
                      <input type="text" name="door_job[]" class="form-control form-input" autocomplete="off">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Maosh</label>
                      <input type="text" name="door_salary[]" class="form-control form-input" autocomplete="off">
                    </div>
                  </div>
                </div>
              </div>

              <div class="row d-flex mt-4">
                <div class="col-md-12 jamb_content">
                  <div class="text-center text-primary display-6">Nalichnik tayyorlash uchun maosh tayinlash</div>
                  <div class="row">
                    <div class="col-md-10"></div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-sm btn-outline-success mt-4 jamb_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                      <button type="button" class="btn btn-sm btn-outline-warning mt-4 jamb_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                    </div>
                  </div>
                  <div class="row jamb_div">
                    <div class="col-md-6">
                      <label class="form-label">Vazifa</label>
                      <input type="text" name="jamb_job[]" class="form-control form-input" autocomplete="off">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Maosh</label>
                      <input type="text" name="jamb_salary[]" class="form-control form-input" autocomplete="off">
                    </div>
                  </div>
                </div>
              </div>

              <div class="row d-flex mt-4">
                <div class="col-md-12 transom_content">
                  <div class="text-center text-primary display-6">Dobor tayyorlash uchun maosh tayinlash</div>
                  <div class="row">
                    <div class="col-md-10"></div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-sm btn-outline-success mt-4 transom_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                      <button type="button" class="btn btn-sm btn-outline-warning mt-4 transom_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                    </div>
                  </div>
                  <div class="row transom_div">
                    <div class="col-md-6">
                      <label class="form-label">Vazifa</label>
                      <input type="text" name="transom_job[]" class="form-control form-input" autocomplete="off">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Maosh</label>
                      <input type="text" name="transom_salary[]" class="form-control form-input" autocomplete="off">
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-4">
                <button type="submit" class="btn btn-primary me-2">Saqlash</button>
              </div>
            </form>
          </div>

          <div class="row door_div_without_labels" style="display: none;">
            <div class="col-md-6">
              <input type="text" name="job[]" class="form-control form-input" autocomplete="off">
            </div>
            <div class="col-md-6">
              <input type="text" name="salary[]" class="form-control form-input" autocomplete="off">
            </div>
          </div>

          <div class="row jamb_div_without_labels" style="display: none;">
            <div class="col-md-6">
              <input type="text" name="jamb_job[]" class="form-control form-input" autocomplete="off">
            </div>
            <div class="col-md-6">
              <input type="text" name="jamb_salary[]" class="form-control form-input" autocomplete="off">
            </div>
          </div>

          <div class="row transom_div_without_labels" style="display: none;">
            <div class="col-md-6">
              <input type="text" name="transom_job[]" class="form-control form-input" autocomplete="off">
            </div>
            <div class="col-md-6">
              <input type="text" name="transom_salary[]" class="form-control form-input" autocomplete="off">
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
        console.log(index, indexDrop);
        if (index > indexDrop) {
          target.before(dragged);
        } else {
          target.after(dragged);
        }
      }
    });
    
    $(document).ready(function(){
      let i = 1;
      $('body').on('click', '.door_plus', function(){
        let targetDiv = document.getElementsByClassName("door_div_without_labels")[0];
        $(".door_content").last().append('<div class="row door_div mt-3">'+targetDiv.innerHTML+'</div>');
        i++;
      });
      $('body').on('click', '.door_minus', function(){
        if(i>1){
          let childDiv = $('body').find('.door_div').last();
          childDiv.remove();
          i--;
        }
      });

      let j = 1;
      $('body').on('click', '.jamb_plus', function(){
        let targetDiv = document.getElementsByClassName("jamb_div_without_labels")[0];
        $(".jamb_content").last().append('<div class="row jamb_div mt-3">'+targetDiv.innerHTML+'</div>');
        j++;
      });
      $('body').on('click', '.jamb_minus', function(){
        if(j>1){
          let childDiv = $('body').find('.jamb_div').last();
          childDiv.remove();
          j--;
        }
      });

      let k = 1;
      $('body').on('click', '.transom_plus', function(){
        let targetDiv = document.getElementsByClassName("transom_div_without_labels")[0];
        $(".transom_content").last().append('<div class="row transom_div mt-3">'+targetDiv.innerHTML+'</div>');
        k++;
      });
      $('body').on('click', '.transom_minus', function(){
        if(k>1){
          let childDiv = $('body').find('.transom_div').last();
          childDiv.remove();
          k--;
        }
      });
    });
  </script>
@endsection