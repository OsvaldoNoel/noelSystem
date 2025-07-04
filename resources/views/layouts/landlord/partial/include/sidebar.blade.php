		<!-- BEGIN #sidebar -->
		<div id="sidebar" class="app-sidebar">
			<!-- BEGIN scrollbar -->
			<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
				<!-- BEGIN menu -->
				<div class="menu">

					<div class="mt-3"></div>
					@include("layouts.landlord.partial.menus.dashboard")

					<div class="menu-header">VARIOS</div>
					@include("layouts.landlord.partial.menus.config")
 
					 
				</div>
				<!-- END menu -->
				<div class="mt-auto p-15px w-100">
					<a href="#" class="btn d-block btn-secondary btn-sm py-6px w-100">
						DOCUMENTATION
					</a>
				</div>
			</div>
			<!-- END scrollbar -->
		</div>
		<!-- END #sidebar -->
			
		<!-- BEGIN mobile-sidebar-backdrop -->
		<button class="app-sidebar-mobile-backdrop" data-toggle-target=".app" data-toggle-class="app-sidebar-mobile-toggled"></button>
		<!-- END mobile-sidebar-backdrop -->