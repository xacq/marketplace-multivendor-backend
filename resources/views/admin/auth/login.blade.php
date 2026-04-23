@include('admin.header')

<style>
    body {
        background: linear-gradient(135deg, #1a4a52 0%, #1f6f78 50%, #28c3c8 100%) !important;
        min-height: 100vh;
    }
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 15px;
    }
    .login-container {
        width: 100%;
        max-width: 420px;
    }
    .login-logo {
        text-align: center;
        margin-bottom: 28px;
    }
    .login-logo img {
        max-height: 70px;
        max-width: 220px;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }
    .login-logo .brand-text {
        color: #ffffff;
        font-size: 26px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-top: 8px;
    }
    .login-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        padding: 36px 40px;
        border: none;
    }
    .login-card h4 {
        color: #1a4a52;
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 6px;
    }
    .login-card .login-subtitle {
        color: #98a6ad;
        font-size: 13px;
        margin-bottom: 28px;
    }
    .login-card .form-group label {
        color: #34395e;
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 6px;
    }
    .login-card .form-control {
        border: 1.5px solid #e4e6fc;
        border-radius: 6px;
        height: 44px;
        font-size: 14px;
        transition: border-color .2s;
    }
    .login-card .form-control:focus {
        border-color: #28c3c8;
        box-shadow: 0 0 0 3px rgba(40, 195, 200, 0.15);
    }
    .login-card .btn-login {
        background: linear-gradient(135deg, #28c3c8, #1f6f78);
        border: none;
        border-radius: 6px;
        height: 46px;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: .5px;
        color: #fff;
        width: 100%;
        transition: opacity .2s, transform .1s;
    }
    .login-card .btn-login:hover {
        opacity: 0.92;
        transform: translateY(-1px);
    }
    .login-card .btn-login:active {
        transform: translateY(0);
    }
    .login-footer {
        text-align: center;
        margin-top: 22px;
        color: rgba(255,255,255,0.65);
        font-size: 13px;
    }
    .alert {
        border-radius: 6px;
        font-size: 13px;
    }
</style>

<div id="app">
    <div class="login-wrapper">
        <div class="login-container">

            {{-- Logo --}}
            <div class="login-logo">
                @if(isset($setting) && $setting->logo)
                    <img src="{{ asset($setting->logo) }}" alt="{{ $setting->site_name ?? 'Logo' }}">
                @else
                    <div class="brand-text">FULFILLEC</div>
                @endif
            </div>

            {{-- Card de login --}}
            <div class="login-card">
                <h4>{{ __('admin.Login') }}</h4>
                <p class="login-subtitle">Ingresa tus credenciales para continuar</p>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form class="needs-validation" novalidate="" action="{{ route('admin.login') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="email">{{ __('admin.Email') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" tabindex="1" autofocus value="{{ old('email') }}"
                               placeholder="admin@ejemplo.com">
                    </div>

                    <div class="form-group">
                        <label for="password">{{ __('admin.Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" tabindex="2" placeholder="••••••••">
                    </div>

                    <div class="form-group d-flex align-items-center justify-content-between mb-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember" class="custom-control-input"
                                   tabindex="3" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="remember" style="font-size:13px;color:#6c757d;">
                                {{ __('admin.Remember Me') }}
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login" tabindex="4">
                        {{ __('admin.Login') }}
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <div class="login-footer">
                {{ $setting->copyright ?? '&copy; ' . date('Y') . ' Fulfillec' }}
            </div>

        </div>
    </div>
</div>

@include('admin.footer')
