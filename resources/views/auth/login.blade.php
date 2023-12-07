<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Page</title>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />

    @include('metronic/css')

    <script src="{{ asset('assets/js/custom/theme-handler.js') }}"></script>
    <style>
        body {
            background-image: url('assets/media/auth/bg4.jpg');
        }

        [data-theme="dark"] body {
            background-image: url('assets/media/auth/bg4-dark.jpg');
        }
    </style>
</head>

<body id="kt_body" class="app-blank app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-column-fluid flex-lg-row">
            <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
                <div class="d-flex flex-center flex-lg-start flex-column">
                    <a href="../../demo1/dist/index.html" class="mb-7">
                        <img alt="Logo" src="assets/media/logos/custom-3.svg" />
                    </a>
                    <h2 class="text-white fw-normal m-0">Branding tools designed for your business</h2>
                </div>
            </div>
            <div class="d-flex flex-center w-lg-50 p-10">
                <div class="card rounded-3 w-md-550px">
                    <div class="card-body p-10 p-lg-20">
                        @section('form')
                            <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form"
                                action="{{ url('login') }}" method="POST">
                                @method('POST')
                                @csrf
                                <div class="text-center mb-11">
                                    <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                                    <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div>
                                </div>
                                <div class="fv-row mb-5 form-floating">
                                    <input type="text" placeholder="login" name="login" autocomplete="off"
                                        class="form-control bg-transparent" value="{{ old('login') }}" />
                                    <label for="floatingInput">Username / Email address</label>
                                </div>
                                <div class="fv-row mb-5 form-floating">
                                    <input type="password" placeholder="Password" name="password" autocomplete="off"
                                        class="form-control bg-transparent" />
                                    <label for="floatingInput">Password</label>
                                </div>
                                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                    <div class="block mt-4">
                                        <label class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="remember"
                                                id="remember_me">
                                            <span
                                                class="form-check-label fw-semibold text-gray-700 fs-base ms-1">{{ __('Remember me') }}</span>
                                        </label>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                    <a href="{{ url('forgot-password') }}" class="link-primary">Forgot Password ?</a>
                                </div>
                                <div class="d-grid mb-10">
                                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                        <span class="indicator-label">Sign In</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet?
                                    <a href="{{ url('register') }}" class="link-primary">Sign up</a>
                                </div>
                            </form>
                        @show
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('metronic/javascript')
    <script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>
    <script>
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif
    </script>
    @yield('js')
</body>

</html>
