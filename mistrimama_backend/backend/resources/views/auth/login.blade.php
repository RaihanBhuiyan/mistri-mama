@extends('layouts.auth')
@section('body')
<div class="page vertical-align">
    <div class="page-content vertical-align-middle" style="width: 100%;">
        <div class="panel" style="margin:0 auto; ">
            <div class="panel-body">
                <div class="brand">
                    <h2 class="brand-text font-size-18">{{ config('app.name', 'Mistrimama') }}</h2>
                </div>
                <form method="post" action="{{ route('login') }}" autocomplete="off">
                    @csrf
                    <div class="form-group form-material floating">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                        <label class="floating-label">Email</label>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group form-material floating">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required />
                        <label class="floating-label">Password</label>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group clearfix">
                        <div class="checkbox-custom checkbox-inline checkbox-primary checkbox-lg float-left">
                            <input type="checkbox" id="inputCheckbox" name="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label for="inputCheckbox">Remember me</label>
                        </div>
                        {{-- <a class="float-right" href="{{ route('password.request') }}">Forgot password?</a> --}}
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg mt-40">Sign in</button>
                </form>
                {{-- <p>Still no account? Please go to <a href="register-v3.html">Sign up</a></p> --}}
            </div>
        </div>
    </div>
</div>
@endsection