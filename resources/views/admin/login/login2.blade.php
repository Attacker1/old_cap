<html lang="en">
	<!--begin::Head-->
	<head><base href="../../../../">
		<meta charset="utf-8" />
		<title>{{ __('Login') }}</title>
		<meta name="description" content="Login page example" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<link rel="canonical" href="https://keenthemes.com/metronic" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Custom Styles(used by this page)-->
		<link href="app-assets/css/pages/login/classic/login-4.css" rel="stylesheet" type="text/css" />
		<!--end::Page Custom Styles-->
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="app-assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="app-assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
		<link href="app-assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<link href="app-assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
		<link href="app-assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
		<link href="app-assets/css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css" />
		<link href="app-assets/css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css" />
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="app-assets/media/logos/black_logo.png" />
	</head>
@section('content')
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
				<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-color:#e1f0ff">
					<div class="login-form text-center p-7 position-relative overflow-hidden">
						<!--begin::Login Header-->
						<div class="d-flex flex-center mb-15">
							<a href="#">
								<img src="app-assets/media/logos/black_logo.png" class="max-h-75px" alt="" />
							</a>
						</div>
						<!--end::Login Header-->
						<!--begin::Login Sign in form-->
						<div class="login-signin">
							<div class="mb-20">
								<h3>Привет, стилист)</h3>
								<div class="text-muted font-weight-bold">Введи свой логин и пароль от аккаунта:</div>
							</div>
							       @error('email')
                                <div class="alert alert-custom alert-primary" role="alert">
										<div class="alert-icon">
											<i class="flaticon-warning"></i>
										</div>
									<div class="alert-text">{{ $message }}</div>
								</div>
                                  @enderror

                                  @error('password')
                                <div class="alert alert-custom alert-primary" role="alert">
										<div class="alert-icon">
											<i class="flaticon-warning"></i>
										</div>
									<div class="alert-text">{{ $message }}</div>
								</div>
                                  @enderror
							<form class="form" method="POST" action="{{ route('admin.login') }}">
							    @csrf
								<div class="form-group mb-5">
                                <input class="form-control h-auto form-control-solid py-4 px-8 bg-white" placeholder="Логин"  id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
								</div>
								<div class="form-group mb-5">
                                <input id="password" type="password" class="form-control h-auto form-control-solid py-4 px-8 bg-white" placeholder="Пароль" name="password" required autocomplete="current-password">
								</div>
								<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
									<div class="checkbox-inline text-light">
										<label class="checkbox m-0 text-muted">
										<input class="form-check" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} checked />
										<span></span> {{ __('Запомни меня') }}</label>
									</div>
								</div>
                                <button type="submit" class="btn text-white bg-dark">
                                    {{ __('Войти') }}
                                </button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!--end::Login-->
		</div>
	</body>
