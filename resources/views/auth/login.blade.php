@extends('layouts.auth')

@section('content')
  <form method="POST" action="{{ route('login') }}">
    @csrf
  
    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email Address') }}</label>
        <div>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus >
        
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
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
        
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
  
    <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input primary" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            
                <label class="form-check-label text-dark" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
            
            @if (Route::has('password.request'))
                <a class="btn btn-link fw-bold" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
              
    </div>
  
    <div class="row mb-0">
            <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
                {{ __('Login') }}
            </button>
    </div>
    {{-- <div class="d-flex align-items-center justify-content-center">
      @if (Route::has('register'))
        <p class="fs-4 mb-0 fw-bold">New Member?</p>
        <a class="nav-link text-primary fw-bold ms-2" href="{{ route('register') }}">{{ __('Create an account') }}</a>
      @endif
    </div> --}}
  
  
  </form>
@endsection