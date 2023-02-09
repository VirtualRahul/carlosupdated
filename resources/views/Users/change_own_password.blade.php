@include('layouts.header')
@include('layouts.head')
@include('layouts.sidebar')

@section('title', 'Change Password')

@if(session()->has('change-password'))
    <div class="alert alert-success">
        {{ session()->get('change-password') }}
    </div>
@endif
<main id="main" class="main">
<div class="row">
    <div class="col-4">

        <div class="card">
            <h5 class="card-header">Change Password</h5>
            <div class="card-body">
            <form action="{{Route('user.change-password-own')}}" method="post">
                {{ csrf_field() }}
               
                <div class="input-group mb-3">
                   
                    <input type="password" name="current_password" class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                           placeholder="Current Password" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @if ($errors->has('current_password'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('current_password') }}</strong>
                        </span>
                    @endif
                    
                </div>
               

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="{{ __('adminlte::adminlte.password') }}" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                           placeholder="{{ __('adminlte::adminlte.retype_password') }}" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
              
              
                <button type="submit" class="btn btn-primary btn-block btn-flat">
                    Update
            </form>

            </div>
        </div>
    </div>
</div>


</main>



@include('layouts.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.2.1/tinymce.min.js" integrity="sha256-6Q5EaYOf1K2LsiwJmuGtmWHoT1X/kuXKnuZeGudWFB4=" crossorigin="anonymous"></script>


