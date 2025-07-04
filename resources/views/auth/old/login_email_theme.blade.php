<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>NoelSystem | Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Noel System" />
	<meta name="author" content="Noel" />
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet" />
	<!-- ================== END core-css ================== -->
	
</head>
<body >  
	<!-- BEGIN #app -->
	<div id="app" class="app app-full-height app-without-header">
		<!-- BEGIN login -->
		<div class="login">
			<!-- BEGIN login-content -->
			<div class="login-content">


				<form method="POST" action="{{ route('login') }}">
                    @csrf

					<h1 class="text-center">Acceso</h1>
					<div class="text-body text-opacity-50 text-center mb-5">
						Por protección, favor verifique su identidad...
					</div>
					<div class="mb-4">
						<label class="form-label" for="email">Corro Electrónico</label>
						<input id="email" type="email" name="email" class="form-control form-control-lg fs-14px" required autofocus/>
					</div> 
					<div class="mb-4">
						<div class="d-flex">
							<label for="password" class="form-label" >Contraseña</label> 
						</div>
						<input id="password" name="password" type="password" class="form-control form-control-lg fs-14px" required autocomplete="current-password" /> 
					</div> 
					<div class="mb-4">
						<div class="form-check">
							<input class="form-check-input" id="remember_me" type="checkbox" name="remember" />
							<label class="form-check-label fw-500" for="remember_me">Recuerdame</label>
						</div>
					</div>

					<button type="submit" class="btn btn-theme btn-lg d-block w-100 mb-3">INGRESAR</button>
                    
					 
				</form>
			</div>
			<!-- END login-content -->
		</div>
		<!-- END login -->
		
		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade">
			<iconify-icon icon="material-symbols-light:keyboard-arrow-up"></iconify-icon>
		</a>
		<!-- END btn-scroll-top --> 
 
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
	<script src="{{ asset('assets/js/vendor.min.js') }}"></script>
	<script src="{{ asset('assets/js/app.min.js') }}"></script>
	<!-- ================== END core-js ================== -->
	
</body>
</html>
