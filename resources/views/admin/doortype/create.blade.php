@extends('layouts.admin')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('dashboard') }}" class="fw-light">Asosiy /</a><span class="text-muted fw-light">Eshik sozlamalari /</span><a href="{{ route('doortypes.index') }}" class="fw-light">Eshik turlari /</a> Yangi eshik turini yaratish </h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">Yangi mahsulot yaratish</div>
          <div class="card-body">
            <form method="POST"  action="{{ route('doortypes.store') }}">
              @csrf
              <div class="row">
                <div class="mb-3 col-md-2">
                  <label for="name" class="form-label">Mahsulot nomi</label>
                  <input class="form-control" type="text" id="name" name="name" autofocus autocomplete="off" />
                </div>
                <div class="mb-3 col-md-2">
                  <label for="dealer_price" class="form-label">Narxi (diler uchun)</label>
                  <input class="form-control" type="number" name="dealer_price" id="dealer_price" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-2">
                  <label for="retail_price" class="form-label">Narxi (xaridor uchun)</label>
                  <input class="form-control" type="number" name="retail_price" id="retail_price" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-2">
                  <label for="installation_price" class="form-label">Ustanovka narxi</label>
                  <input class="form-control" type="number" name="installation_price" id="installation_price" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-1">
                  <label for="layer15_koeffitsient" class="form-label">1.5 tabaqa koef.</label>
                  <input class="form-control" type="text" name="layer15_koeffitsient" id="layer15_koeffitsient" autocomplete="off" />
                </div>
                <div class="mb-3 col-md-3">
                  <ol style="list-style-type: none;">
                    <label class="form-label">Lavozimlar</label>
                    @foreach($jobs as $key => $value)
                      <li draggable="true" id="{{ $key }}" class="dropzone" style="padding:15px; border:1px solid #696CFF; margin-bottom:3px;">
                        <input id="{{ $value->name . $key }}" class="form-check-input" type="checkbox" name="jobs[]" id="jobs{{ $value->id }}" value="{{ $value->id }}"/>
                        <label for="{{ $value->name . $key }}" class="form-check-label" style="margin-left: 10px">{{ $value->name }}</label>
                      </li>
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
        $(".delete_form").attr("action", "doortypes/" + id);
      });
    });
  </script>
@endsection
