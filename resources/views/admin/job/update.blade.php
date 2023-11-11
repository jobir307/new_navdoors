@extends('layouts.admin')
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><a href="{{ route('dashboard') }}" class="fw-light">Asosiy / </a><a href="{{ route('jobs.index') }}" class="fw-light">Lavozimlar / </a> Lavozimni o'zgartirish</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-4">
          <div class="card-header">Lavozimni  o'zgartirish</div>
          <div class="card-body">
            <form method="POST"  action="{{ route('jobs.update', $job->id) }}">
            @csrf
            @method('PUT')
            <div class="row" >
              <div class="mb-3 col-md-8 col-sm-6">
                <label for="name" class="form-label">Lavozim nomi</label>
                <input class="form-control" type="text" id="name" name="name" autofocus autocomplete="off" value="{{ $job->name }}" />
              </div>
              <div class="mb-3 col-md-4 col-sm-6">
                <ol style="list-style-type:none;">
                  <label for="name" class="form-label">Eshik atributlari</label>
                  @foreach($in_array as $key => $value)
                    <li draggable="true" id="{{ $key }}" class="dropzone" style="padding:15px; border:1px solid #696CFF; margin-bottom:3px;">
                      <input class="form-check-input" type="checkbox" name="door_attributes[]" id="door_attributes{{ $value['id'] }}" checked value="{{ $value['en_name'] }}"/>
                      <label for="door_attributes{{ $value['id'] }}" class="form-check-label" style="margin-left: 10px">{{ $value['name'] }}</label>
                    </li>
                  @endforeach
                  @foreach($diff_array as $name => $en_name)
                    <li draggable="true" id="{{ $en_name }}" class="dropzone" style="padding:15px; border:1px solid #696CFF; margin-bottom:3px;">
                      <input class="form-check-input" type="checkbox" name="door_attributes[]" id="door_attributes{{ $en_name }}" value="{{ $en_name }}"/>
                      <label for="door_attributes{{ $en_name }}" class="form-check-label" style="margin-left: 10px">{{ $name }}</label>
                    </li>
                  @endforeach
                </ol>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 door_content">
                <div class="text-center text-primary display-6">Eshik tayyorlash uchun maosh tayinlash</div>
                <div class="row">
                  <div class="col-md-10"></div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-success mt-4 door_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-warning mt-4 door_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                  </div>
                </div>
                <div class="row door_div mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Vazifa</label>
                    <input type="text" name="door_job[]" class="form-control form-input" autocomplete="off" value="{{ $door_jobs[0]['job'] }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Maosh</label>
                    <input type="text" name="door_salary[]" class="form-control form-input" autocomplete="off" value="{{ $door_jobs[0]['salary'] }}">
                  </div>
                </div>
                @for($i = 1; $i < count($door_jobs); $i++)
                  <div class="row door_div mb-3">
                    <div class="col-md-6">
                      <input type="text" name="door_job[]" class="form-control form-input" autocomplete="off" value="{{ $door_jobs[$i]['job'] }}">
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="door_salary[]" class="form-control form-input" autocomplete="off" value="{{ $door_jobs[$i]['salary'] }}">
                    </div>
                  </div>
                @endfor
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-12 jamb_content">
                <div class="text-center text-primary display-6">Nalichnik tayyorlash uchun maosh tayinlash</div>
                <div class="row">
                  <div class="col-md-10"></div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-success mt-4 jamb_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-warning mt-4 jamb_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                  </div>
                </div>
                <div class="row jamb_div mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Vazifa</label>
                    <input type="text" name="jamb_job[]" class="form-control form-input" autocomplete="off" value="{{ $jamb_jobs[0]['job'] ?? '' }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Maosh</label>
                    <input type="text" name="jamb_salary[]" class="form-control form-input" autocomplete="off" value="{{ $jamb_jobs[0]['salary'] ?? '' }}">
                  </div>
                </div>
                @if (!is_null($jamb_jobs) && count($jamb_jobs) >= 2)
                  @for($i = 1; $i < count($jamb_jobs); $i++)
                    <div class="row jamb_div mb-3">
                      <div class="col-md-6">
                        <input type="text" name="jamb_job[]" class="form-control form-input" autocomplete="off" value="{{ $jamb_jobs[$i]['job'] }}">
                      </div>
                      <div class="col-md-6">
                        <input type="text" name="jamb_salary[]" class="form-control form-input" autocomplete="off" value="{{ $jamb_jobs[$i]['salary'] }}">
                      </div>
                    </div>
                  @endfor
                @endif
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-12 nsjamb_content">
                <div class="text-center text-primary display-6">Nostandart nalichnik tayyorlash uchun maosh tayinlash</div>
                <div class="row">
                  <div class="col-md-10"></div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-success mt-4 nsjamb_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-warning mt-4 nsjamb_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                  </div>
                </div>
                <div class="row nsjamb_div mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Vazifa</label>
                    <input type="text" name="nsjamb_job[]" class="form-control form-input" autocomplete="off" value="{{ $nsjamb_jobs[0]['job'] ?? '' }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Maosh</label>
                    <input type="text" name="nsjamb_salary[]" class="form-control form-input" autocomplete="off" value="{{ $nsjamb_jobs[0]['salary'] ?? '' }}">
                  </div>
                </div>
                @if (!is_null($nsjamb_jobs) && count($nsjamb_jobs) >= 2)
                  @for($i = 1; $i < count($nsjamb_jobs); $i++)
                    <div class="row nsjamb_div mb-3">
                      <div class="col-md-6">
                        <input type="text" name="nsjamb_job[]" class="form-control form-input" autocomplete="off" value="{{ $nsjamb_jobs[$i]['job'] }}">
                      </div>
                      <div class="col-md-6">
                        <input type="text" name="nsjamb_salary[]" class="form-control form-input" autocomplete="off" value="{{ $nsjamb_jobs[$i]['salary'] }}">
                      </div>
                    </div>
                  @endfor
                @endif
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-12 transom_content">
                <div class="text-center text-primary display-6">Dobor tayyorlash uchun maosh tayinlash</div>
                <div class="row">
                  <div class="col-md-10"></div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-success mt-4 transom_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-warning mt-4 transom_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                  </div>
                </div>
                <div class="row transom_div mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Vazifa</label>
                    <input type="text" name="transom_job[]" class="form-control form-input" autocomplete="off" value="{{ $transom_jobs[0]['job'] ?? '' }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Maosh</label>
                    <input type="text" name="transom_salary[]" class="form-control form-input" autocomplete="off" value="{{ $transom_jobs[0]['salary'] ?? '' }}">
                  </div>
                </div>
                @if (!is_null($transom_jobs) && count($transom_jobs) >= 2)
                  @for($i = 1; $i < count($transom_jobs); $i++)
                    <div class="row transom_div mb-3">
                      <div class="col-md-6">
                        <input type="text" name="transom_job[]" class="form-control form-input" autocomplete="off" value="{{ $transom_jobs[$i]['job'] }}">
                      </div>
                      <div class="col-md-6">
                        <input type="text" name="transom_salary[]" class="form-control form-input" autocomplete="off" value="{{ $transom_jobs[$i]['salary'] }}">
                      </div>
                    </div>
                  @endfor
                @endif
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-12 crown_content">
                <div class="text-center text-primary display-6">Korona tayyorlash uchun maosh tayinlash</div>
                <div class="row">
                  <div class="col-md-10"></div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-success mt-4 crown_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-warning mt-4 crown_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                  </div>
                </div>
                <div class="row crown_div mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Vazifa</label>
                    <input type="text" name="crown_job[]" class="form-control form-input" autocomplete="off" value="{{ $crown_jobs[0]['job'] ?? '' }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Maosh</label>
                    <input type="text" name="crown_salary[]" class="form-control form-input" autocomplete="off" value="{{ $crown_jobs[0]['salary'] ?? '' }}">
                  </div>
                </div>
                @if (!is_null($crown_jobs) && count($crown_jobs) >= 2)
                  @for($i = 1; $i < count($crown_jobs); $i++)
                    <div class="row crown_div mb-3">
                      <div class="col-md-6">
                        <input type="text" name="crown_job[]" class="form-control form-input" autocomplete="off" value="{{ $crown_jobs[$i]['job'] }}">
                      </div>
                      <div class="col-md-6">
                        <input type="text" name="crown_salary[]" class="form-control form-input" autocomplete="off" value="{{ $crown_jobs[$i]['salary'] }}">
                      </div>
                    </div>
                  @endfor
                @endif
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-12 boot_content">
                <div class="text-center text-primary display-6">Sapog tayyorlash uchun maosh tayinlash</div>
                <div class="row">
                  <div class="col-md-10"></div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-success mt-4 boot_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-warning mt-4 boot_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                  </div>
                </div>
                <div class="row boot_div mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Vazifa</label>
                    <input type="text" name="boot_job[]" class="form-control form-input" autocomplete="off" value="{{ $boot_jobs[0]['job'] ?? '' }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Maosh</label>
                    <input type="text" name="boot_salary[]" class="form-control form-input" autocomplete="off" value="{{ $boot_jobs[0]['salary'] ?? '' }}">
                  </div>
                </div>
                @if (!is_null($boot_jobs) && count($boot_jobs) >= 2)
                  @for($i = 1; $i < count($boot_jobs); $i++)
                    <div class="row boot_div mb-3">
                      <div class="col-md-6">
                        <input type="text" name="boot_job[]" class="form-control form-input" autocomplete="off" value="{{ $boot_jobs[$i]['job'] }}">
                      </div>
                      <div class="col-md-6">
                        <input type="text" name="boot_salary[]" class="form-control form-input" autocomplete="off" value="{{ $boot_jobs[$i]['salary'] }}">
                      </div>
                    </div>
                  @endfor
                @endif
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-md-12 cube_content">
                <div class="text-center text-primary display-6">Kubik tayyorlash uchun maosh tayinlash</div>
                <div class="row">
                  <div class="col-md-10"></div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-outline-success mt-4 cube_plus" style="float: right;"><i class="bx bx-plus"></i></button>
                    <button type="button" class="btn btn-sm btn-outline-warning mt-4 cube_minus" style="float: right; margin-right: 8px;"><i class="bx bx-minus"></i></button>
                  </div>
                </div>
                <div class="row cube_div mb-3">
                  <div class="col-md-6">
                    <label class="form-label">Vazifa</label>
                    <input type="text" name="cube_job[]" class="form-control form-input" autocomplete="off" value="{{ $cube_jobs[0]['job'] ?? '' }}">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Maosh</label>
                    <input type="text" name="cube_salary[]" class="form-control form-input" autocomplete="off" value="{{ $cube_jobs[0]['salary'] ?? '' }}">
                  </div>
                </div>
                @if (!is_null($crown_jobs) && count($crown_jobs) >= 2)
                  @for($i = 1; $i < count($crown_jobs); $i++)
                    <div class="row cube_div mb-3">
                      <div class="col-md-6">
                        <input type="text" name="cube_job[]" class="form-control form-input" autocomplete="off" value="{{ $cube_jobs[$i]['job'] }}">
                      </div>
                      <div class="col-md-6">
                        <input type="text" name="cube_salary[]" class="form-control form-input" autocomplete="off" value="{{ $cube_jobs[$i]['salary'] }}">
                      </div>
                    </div>
                  @endfor
                @endif
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary me-2">Saqlash</button>
            </div>
            </form>
          </div>
          <div class="row door_div_without_labels" style="display: none;">
            <div class="col-md-6">
              <input type="text" name="door_job[]" class="form-control form-input" autocomplete="off">
            </div>
            <div class="col-md-6">
              <input type="text" name="door_salary[]" class="form-control form-input" autocomplete="off">
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

          <div class="row nsjamb_div_without_labels" style="display: none;">
            <div class="col-md-6">
              <input type="text" name="nsjamb_job[]" class="form-control form-input" autocomplete="off">
            </div>
            <div class="col-md-6">
              <input type="text" name="nsjamb_salary[]" class="form-control form-input" autocomplete="off">
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

          <div class="row crown_div_without_labels" style="display: none;">
            <div class="col-md-6">
              <input type="text" name="crown_job[]" class="form-control form-input" autocomplete="off">
            </div>
            <div class="col-md-6">
              <input type="text" name="crown_salary[]" class="form-control form-input" autocomplete="off">
            </div>
          </div>

          <div class="row boot_div_without_labels" style="display: none;">
            <div class="col-md-6">
              <input type="text" name="boot_job[]" class="form-control form-input" autocomplete="off">
            </div>
            <div class="col-md-6">
              <input type="text" name="boot_salary[]" class="form-control form-input" autocomplete="off">
            </div>
          </div>

          <div class="row cube_div_without_labels" style="display: none;">
            <div class="col-md-6">
              <input type="text" name="cube_job[]" class="form-control form-input" autocomplete="off">
            </div>
            <div class="col-md-6">
              <input type="text" name="cube_salary[]" class="form-control form-input" autocomplete="off">
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

        if (index > indexDrop) {
          target.before(dragged);
        } else {
          target.after(dragged);
        }
      }
    });
    
    $(document).ready(function(){
      let i = $('.door_div').length;
      $('body').on('click', '.door_plus', function(){
        let targetDiv = document.getElementsByClassName("door_div_without_labels")[0];
        $(".door_content").last().append('<div class="row door_div mb-3">'+targetDiv.innerHTML+'</div>');
        i++;
      });
      $('body').on('click', '.door_minus', function(){
        if(i>1){
          let childDiv = $('body').find('.door_div').last();
          childDiv.remove();
          i--;
        }
      });


      let j = $('.jamb_div').length;
      $('body').on('click', '.jamb_plus', function(){
        let targetDiv = document.getElementsByClassName("jamb_div_without_labels")[0];
        $(".jamb_content").last().append('<div class="row jamb_div mb-3">'+targetDiv.innerHTML+'</div>');
        j++;
      });
      $('body').on('click', '.jamb_minus', function(){
        if(j>1){
          let childDiv = $('body').find('.jamb_div').last();
          childDiv.remove();
          j--;
        }
      });

      let o = $('.nsjamb_div').length;
      $('body').on('click', '.nsjamb_plus', function(){
        let targetDiv = document.getElementsByClassName("nsjamb_div_without_labels")[0];
        $(".nsjamb_content").last().append('<div class="row nsjamb_div mb-3">'+targetDiv.innerHTML+'</div>');
        o++;
      });
      $('body').on('click', '.nsjamb_minus', function(){
        if(o>1){
          let childDiv = $('body').find('.nsjamb_div').last();
          childDiv.remove();
          o--;
        }
      });

      let k = $('.transom_div').length;
      $('body').on('click', '.transom_plus', function(){
        let targetDiv = document.getElementsByClassName("transom_div_without_labels")[0];
        $(".transom_content").last().append('<div class="row transom_div mb-3">'+targetDiv.innerHTML+'</div>');
        k++;
      });
      $('body').on('click', '.transom_minus', function(){
        if(k>1){
          let childDiv = $('body').find('.transom_div').last();
          childDiv.remove();
          k--;
        }
      });

      let l = $('.crown_div').length;
      $('body').on('click', '.crown_plus', function(){
        let targetDiv = document.getElementsByClassName("crown_div_without_labels")[0];
        $(".crown_content").last().append('<div class="row crown_div mt-3">'+targetDiv.innerHTML+'</div>');
        l++;
      });
      $('body').on('click', '.crown_minus', function(){
        if(l>1){
          let childDiv = $('body').find('.crown_div').last();
          childDiv.remove();
          l--;
        }
      });

      let m = $('.boot_div').length;
      $('body').on('click', '.boot_plus', function(){
        let targetDiv = document.getElementsByClassName("boot_div_without_labels")[0];
        $(".boot_content").last().append('<div class="row boot_div mt-3">'+targetDiv.innerHTML+'</div>');
        m++;
      });
      $('body').on('click', '.boot_minus', function(){
        if(m>1){
          let childDiv = $('body').find('.boot_div').last();
          childDiv.remove();
          m--;
        }
      });

      let n = $('.cube_div').length;
      $('body').on('click', '.cube_plus', function(){
        let targetDiv = document.getElementsByClassName("cube_div_without_labels")[0];
        $(".cube_content").last().append('<div class="row cube_div mt-3">'+targetDiv.innerHTML+'</div>');
        n++;
      });
      $('body').on('click', '.cube_minus', function(){
        if(n>1){
          let childDiv = $('body').find('.cube_div').last();
          childDiv.remove();
          n--;
        }
      });
    });
  </script>
@endsection
