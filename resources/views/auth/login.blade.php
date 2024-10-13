<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DREBBA POS - Login</title>

    @include('backend.layouts.assets.css')
    @yield('css')
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/helper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/css/main.css')}}">
    @stack('css')
</head>

<body class="bg-gradient-primary">

<div class="container mt-5">

    <div class="row align-items-center">

        <div class="col-md-4 mx-auto">
            <div class="auth-box">
                <div class="auth-box-content">

                            <div class="p-4">
                                <div class="text-center">
                                    <h1 class="auth-title"> {{__('pages.login')}}</h1>
                                </div>
                                <form method="POST" action="{{ route('login') }}" class="user">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <label class="custom-label">{{__('pages.email')}} </label>
                                        <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Email Address">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="custom-label"> {{__('pages.password')}} </label>
                                        <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required placeholder="Email Address">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                        @enderror
                                    </div>


                                    <div class="form-check my-4">
                                        <input class="form-check-input" type="checkbox" value="" id="rememberMe" checked>
                                        <label class="form-check-label text-brand-dark" for="rememberMe">
                                            {{__('pages.remember_me')}}

                                        </label>
                                    </div>

                                    <div>
                                        <button type="submit" class="btn btn-brand-primary btn-brand btn-user w-100">
                                            {{__('pages.login')}}
                                        </button>
                                    </div>
                                </form>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap core JavaScript-->
<script src="{{asset('backend/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Core plugin JavaScript-->
<script src="{{asset('backend/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

<!-- Custom scripts for all pages-->
<script src="{{asset('backend/js/script.js')}}"></script>
@include('backend.layouts.assets.js')
</body>

</html>

