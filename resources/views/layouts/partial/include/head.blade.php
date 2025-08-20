<meta charset="utf-8" />
<title>NoelSystem | </title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="" />
<meta name="author" content="" />

<!-- ================== BEGIN core-css ================== -->
<link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/css/appTenant.min.css') }}" rel="stylesheet" />
@vite(['resources/css/appLayout.css', 'resources/js/appLayout.js'])
<!-- ================== END core-css ================== -->

<!-- ================== DATATABLES ================== -->
<link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
    rel="stylesheet" />
<link href="{{ asset('assets/plugins/bootstrap-table/dist/bootstrap-table.min.css') }}" rel="stylesheet" />
<!-- =============== end DATATABLES ================== -->

<link href="{{ asset('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}"
    rel="stylesheet" />
<link href="{{ asset('assets/plugins/tomSelect/tom-select.default.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css">

@if (auth()->user()->isLandlord())
    <link href="{{ asset('assets/css/customLandlord.css') }}" rel="stylesheet" type="text/css">
@endif

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_green.css">  --}}
