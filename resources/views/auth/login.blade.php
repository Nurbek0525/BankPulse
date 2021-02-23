@extends('layouts.app')

@section('content')

<div class="all-wrapper menu-side with-pattern">
    <div class="auth-box-w">
        <div class="logo-w">
            <img src="{{ URL::asset('assets/img/login-page.png') }}" alt="" style="width: 140px" />
        </div>
        <h4 class="auth-header">{{ trans('app.Tizimga kirish') }}</h4>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input id="email" type="email" placeholder='{{ trans('app.email') }}' class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                <div class="pre-icon os-icon os-icon-user-male-circle"></div>
            </div>
            <div class="form-group mt-3">
                <input id="password" type="password" placeholder="{{ trans('app.password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="pre-icon os-icon os-icon-fingerprint"></div>
            </div>
            <div class="buttons-w">
                <button class="btn btn-primary">{{ trans('app.log in') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
