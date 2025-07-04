<!-- BEGIN theme-panel -->
<div class="app-theme-panel">
	<div class="app-theme-panel-container">
		{{-- <a href="#" data-toggle="theme-panel-expand" class="app-theme-toggle-btn"><iconify-icon icon="ph:gear-duotone"></iconify-icon></a> --}}
		<div class="app-theme-panel-content">
			<div class="fs-10px fw-semibold text-white mt-2">
				COLOR DE TEMA
			</div>
			<div class="fs-9px lh-sm mb-2 text-white text-opacity-75">
				Cambie el color de su tema favorito
			</div>
			<!-- BEGIN theme-list -->
			<div class="app-theme-list">
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-pink" data-theme-class="theme-pink" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Pink">&nbsp;</a></div>
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-red" data-theme-class="theme-red" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Red">&nbsp;</a></div>
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-warning" data-theme-class="theme-warning" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Orange">&nbsp;</a></div>
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-yellow" data-theme-class="theme-yellow" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Yellow">&nbsp;</a></div>
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-lime" data-theme-class="theme-lime" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Lime">&nbsp;</a></div>
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-green" data-theme-class="theme-green" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Green">&nbsp;</a></div>
				<div class="app-theme-list-item active"><a href="#" class="app-theme-list-link bg-teal" data-theme-class="" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Default">&nbsp;</a></div>
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-info" data-theme-class="theme-info"  data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Cyan">&nbsp;</a></div>
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-primary" data-theme-class="theme-primary"  data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Blue">&nbsp;</a></div>
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-indigo" data-theme-class="theme-indigo" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Indigo">&nbsp;</a></div>
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-purple" data-theme-class="theme-purple" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Purple">&nbsp;</a></div>
				<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-white" data-theme-class="theme-white" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="White">&nbsp;</a></div>
			</div>
			<!-- END theme-list -->
			
			<hr>
			<div class="fs-10px fw-semibold text-white">
				MENU DE NAVEGACION
			</div>

			<div class="form-check form-switch mt-2 mx-2">
				<input wire:click="$dispatch('menuSuperior')"
				@click="show = !show" :aria-expanded="show ? 'true' : 'false'" :class="{ 'active': show }" value="{{ $value }}"
				 type="checkbox" class="form-check-input" id="menuSuperior">
				<label class="form-check-label" for="menuSuperior">Menu superior</label>
			</div>

		</div>
	</div>
</div>
<!-- END theme-panel -->
