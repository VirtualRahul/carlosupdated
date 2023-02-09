@include('layouts.header')
@include('layouts.head')
@include('layouts.sidebar')



@if(isset($user))
<main id="main" class="main">
<div class="row">
    <div class="col-12">

        <div class="card">
            <h5 class="card-header">Edit User</h5>
            <div class="card-body">
            <form action="{{Route('user.update-user')}}" method="post">
                {{ csrf_field() }}

                <div class="input-group mb-3">
                <input type="hidden" name="user_id" value="{{$user->id}}">
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{$user->name}}"
                           placeholder="{{ $user->name }}"  autofocus autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>

                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" value="{{$user->email}}" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}"
                            autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="bxs-envelope"></span>
                        </div>
                    </div>
                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
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

@else
<main id="main" class="main">
<div class="row">
    <div class="col-12">

        <div class="card">
            <h5 class="card-header">Add User</h5>
            <div class="card-body">
            <form action="{{Route('user.insert-user')}}" method="post">
                {{ csrf_field() }}

                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}"
                           placeholder="Name" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="bi bi-person"></span>
                        </div>
                    </div>

                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}"
                           placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                    </div>
                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
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
                           placeholder="Confirm Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-flat">
                   Register
                </button>
            </form>

            </div>
        </div>
    </div>
</div>

</main >
@endif





@include('layouts.footer')




