@extends('layouts.app')

@section('content')
<div class="container-fluid" style="height: 100vh; background: url('https://source.unsplash.com/random/1600x900') no-repeat center center fixed; background-size: cover;">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <div class="row justify-content-center align-items-center" style="height: 100%;">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header text-white text-center" style="background-color: #ED2B24;">
                    <h3>{{ __('Login') }}</h3>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label for="password">{{ __('Password') }}</label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group mt-4 mb-0 text-center">
                            <button type="submit" class="btn" style="background-color: #ED2B24; color: white; width: 50%;">
                                {{ __('Login') }}
                            </button>
                        </div>

                        <div class="form-group mt-3 text-center">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center">
                    @if (Route::has('register'))
                        <a class="btn btn-link" href="{{ route('register') }}" style="background-color: #ED2B24; color: white;">
                            {{ __('Register') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener("DOMContentLoaded", function () {
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function (e) {
            // Toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

            // Toggle the eye / eye slash icon
            this.querySelector('i').classList.toggle("fa-eye");
            this.querySelector('i').classList.toggle("fa-eye-slash");
        });
    });
</script>
@endsection
