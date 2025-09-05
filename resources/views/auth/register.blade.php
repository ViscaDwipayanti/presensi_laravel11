@extends('layouts.auth')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Name') }}</label>

        <div>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email Address') }}</label>

        <div>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">{{ __('Password') }}</label>

        <div>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>

        <div >
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
        </div>
    </div>

    <div class="row mb-0">
            <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
                {{ __('Register') }}
            </button>
    </div>

    <div class="d-flex align-items-center justify-content-center">
        @if (Route::has('login'))
            <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
            <a class="nav-link text-primary fw-bold ms-2" href="{{ route('login') }}">{{ __('Login') }}</a>                       
        @endif
    </div>
</form>
@endsection

    
    


