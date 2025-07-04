		<!-- BEGIN #top-nav -->
		<div id="top-nav" class="app-top-nav">
			<!-- BEGIN menu -->
			<div class="menu">

				@include("layouts.tenant.partial.menus.dashboard") 
				@include("layouts.tenant.partial.menus.ventas")

				@include("layouts.tenant.partial.menus.stock")
				@include("layouts.tenant.partial.menus.clientes")
				@include("layouts.tenant.partial.menus.proveedores")
				@include("layouts.tenant.partial.menus.finanzas")
				@include("layouts.tenant.partial.menus.config")
				@include("layouts.tenant.partial.menus.reportes")

				<div class="menu-item menu-control menu-control-start">
					<a href="javascript:;" class="menu-link" data-toggle="app-top-nav-prev"><i class="fa fa-chevron-left"></i></a>
				</div>
				
				<div class="menu-item menu-control menu-control-end">
					<a href="javascript:;" class="menu-link" data-toggle="app-top-nav-next"><i class="fa fa-chevron-right"></i></a>
				</div>

			</div>
			<!-- END menu -->
		</div>
		<!-- END #top-nav -->
