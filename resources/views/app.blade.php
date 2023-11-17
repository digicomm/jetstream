<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any"/>
        <link rel="icon" href="/favicon.svg" type="image/svg+xml"/>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-1.13.6/b-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/sc-2.2.0/sl-1.7.0/sr-1.3.0/datatables.min.js" type="application/javascript"></script>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="subpixel-antialiased">
        @inertia
    </body>
</html>
