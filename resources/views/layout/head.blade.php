<!DOCTYPE html>
<html lang="id" data-bs-theme="light" data-menu-color="brand" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
<meta content="Myra Studio" name="author" /> --}}

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('home/Admin/dist') }}/assets/images/icon.png">

    <link href="{{ asset('home/Admin/dist') }}/assets/libs/ladda/ladda.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('home/Admin/dist') }}/assets/libs/ladda/ladda-themeless.min.css" rel="stylesheet" type="text/css" />

    <!-- third party css -->
    <link href="{{ asset('home/Admin/dist') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('home/Admin/dist') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('home/Admin/dist') }}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('home/Admin/dist') }}/assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <link href="{{ asset('home/Admin/dist') }}/assets/libs/morris.js/morris.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('home/Admin/dist') }}/assets/css/style.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('home/Admin/dist') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <script src="{{ asset('home/Admin/dist') }}/assets/js/config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    {{-- Leaflet.js --}}
    <link rel="stylesheet" href="{{ asset('leaflet') }}/leaflet.css" />
    <script src="{{ asset('leaflet') }}/leaflet.js"></script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/v3.10.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v3.10.0/mapbox-gl.css' rel='stylesheet' />
</head>
@if(session('info'))


        <script>
            // Menampilkan alert browser dengan pesan dari session
            alert("{{ session('info') }}");
        </script>
    @endif

