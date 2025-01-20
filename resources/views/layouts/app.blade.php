<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <meta name="description" content="@yield('meta_description')">
    <meta name="keywords" content="@yield('meta_keyword')">
    <meta name="author" content="IT Team of MM Shop">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    {{-- Owl Carousel --}}
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">
    {{-- Exzoom - Prod Img --}}
    <link rel="stylesheet" href="{{ asset('assets/exzoom/jquery.exzoom.css') }}">


    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/alertify.min.css') }}" />
    <!-- Default theme -->
    <link rel="stylesheet" href="{{ asset('assets/css/themes/default.min.css') }}" />

    @livewireStyles
</head>
<body>
    <div id="app">

        @include('layouts.inc.frontend.navbar')

        <main class="py-4">
            @yield('content')
        </main>

        @include('layouts.inc.frontend.footer')
    </div>

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'assets/js/jquery-3.7.1.min.js']) --}}
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/alertify.min.js') }}"></script>
    
    <script >

        window.addEventListener('message', (event) => {
            alertify.set('notifier','position','top-right');
            alertify.notify(event.detail.text, event.detail.type);
        })

        
    </script>

    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/exzoom/jquery.exzoom.js') }}"></script>



    @yield('script')

    @livewireStyles

    @stack('scripts')
</body>
</html>
