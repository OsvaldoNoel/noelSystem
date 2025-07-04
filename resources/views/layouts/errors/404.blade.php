@extends('layout.default', [
	'bodyClass' => 'pace-top',
	'appHeaderHide' => true,
	'appSidebarHide' => true,
	'appContentHide' => true,
	'appClass' => 'app-full-height app-without-header'
])

@section('title', 'Error Page')

@section('content')
	<!-- BEGIN error -->
	<div class="error-page">
		<!-- BEGIN error-page-content -->
		<div class="error-page-content">
			<div class="error-code">404</div>
			
			<h1 class="mb-3">Oops!</h1> 
			<h3 class="mb-2">We can't seem to find the page you're looking for</h3>
			<p class="mb-2">
				Here are some helpful links instead:
			</p>
			<p class="mb-5">
				<a href="/" class="text-decoration-none">Home</a>
				<span class="link-divider"></span>
				<a href="/page/search-results" class="text-decoration-none">Search</a>
				<span class="link-divider"></span>
				<a href="/email/inbox" class="text-decoration-none">Email</a>
				<span class="link-divider"></span>
				<a href="/calendar" class="text-decoration-none">Calendar</a>
				<span class="link-divider"></span>
				<a href="/settings" class="text-decoration-none">Settings</a>
				<span class="link-divider"></span>
				<a href="/helper" class="text-decoration-none">Helper</a>
			</p>
			<a href="/" class="btn btn-theme w-130px text-uppercase btn-sm py-2 fs-11px">Go to Homepage</a>
		</div>
		<!-- END error-page-content -->
	</div>
	<!-- END error -->
@endsection
