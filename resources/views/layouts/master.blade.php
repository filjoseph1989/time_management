<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Time Management | Dashboard</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">

        @include('layouts.part.navigation')

        @include('layouts.part.sidebar-menu')

        @yield('content')

        <aside class="control-sidebar control-sidebar-dark">
        </aside>

        <footer class="main-footer">
            <div class="float-right d-sm-none d-md-block">
                {{-- Anything you want --}}
            </div>
            <strong>Copyright &copy; 2019 <a href="#">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
    </div>

    <script src="{{ mix('js/app.js') }}" charset="utf-8"></script>
</body>
</html>
