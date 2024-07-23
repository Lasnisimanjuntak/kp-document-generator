@extends('layouts.app')

@section('content')
<div class="container-fluid" style="height: 100vh; background: url('https://source.unsplash.com/random/1600x900') no-repeat center center fixed; background-size: cover;">
    <div class="row justify-content-center align-items-center" style="height: 100%;">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header text-white text-center" style="background-color: #ED2B24;">
                    <h3>{{ __('Register') }}</h3>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-block" style="background-color: #ED2B24; color: white;">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="card-footer text-center">
                    @if (Route::has('login'))
                        <a class="btn btn-link text-white" style="background-color: #ED2B24;" href="{{ route('login') }}">
                            {{ __('Already have an account? Login') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('password-confirm').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password-confirm').value;
        const submitButton = document.querySelector('button[type="submit"]');

        if (password === confirmPassword) {
            submitButton.removeAttribute('disabled');
        } else {
            submitButton.setAttribute('disabled', 'disabled');
        }
    });
</script>
@endsection
