@extends('layouts.app')
@section('content')
<style>
    .col-xl-10 {
        flex: 0 0 auto;
        width: 74%;
    }

    .alert-warning i {
        font-size: 20px;
    }

    .alert-warning h5 {
        font-size: 15px;
        margin-top: 10px;
        letter-spacing: 1px;
    }

</style>
<link rel="stylesheet" href="{{ my_asset('/assets/css/custom-auth.css') }}" />
<div class="custom-login">
        <!--<div class="bg-login bg-primary"></div>-->
        <div class="custom-login-inner">


            <main class="custom-wrapper">
                <div class="custom-row">
               
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="card">
                            <div class="card-body">

                                 <div class="text-center m-4">
                                    <!--<h2 class="mb-3 f-w-600">{{ __('User') }} <span class="text-primary">{{ __('to login!') }}</span></h2>-->
                                    <a href="#">
                                        <img src="{{ my_asset('assets/images/logo.png') }}" alt="{{ config('app.name', 'TicketGo Saas') }}" alt="logo" loading="lazy" class="logo" />
                                    </a>                                    
                                </div>
                                <div class="text-center mb-3 mt-3">
                                    <h2 class="f-w-600">{{ __('Forgot Your Password ?') }}</h2>
                                </div>
                                <form method="POST" action="{{ route('forget.password.post') }}" id="form_data">
                                    @csrf
                                    @if (Session::has('message'))
                                    <div class="alert alert-success" role="alert">
                                        {{ Session::get('message') }}
                                    </div>
                                    @elseif (Session::has('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ Session::get('error') }}
                                    </div>
                                    @endif                                  

                                    <div class="custom-login-form">
                                        <div class="">
                                            <div class="form-group mb-3">
                                                <label for="email" class="form-label d-flex">{{ __('Email') }}</label>
                                                <input type="email"
                                                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                    id="email" name="email" placeholder="{{ __('Email address') }}"
                                                    required="" value="{{ old('email') }}">
                                                <div class="invalid-feedback d-block">
                                                    {{ $errors->first('email') }}
                                                </div>
                                            </div>
                                            <div class="d-grid">
                                                <button class="btn btn-primary btn-block mt-2"
                                                    id="login_button">{{ __('Reset Password') }}</button>
                                            </div>
                                        </div>

                                        <p class="my-4 text-center d-flex">
                                            <a href="{{ url('login') }}" tabindex="0">{{ __('Back to Login ?') }}</a>
                                        </p>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>
    </div>
    
@endsection

@section('jscontent')
<script>

</script>
@endsection