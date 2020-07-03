<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/9e89e3dc89.js" crossorigin="anonymous"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        [v-clock] { display: none; }
    </style>

    {{-- Scripts --}}
    <script>
        window.App = {!! json_encode([
            'signedIn' => Auth::check(),
            'user' => Auth::user(),
        ]) !!}
    </script>

    @yield('header')

</head>
<body style="padding-bottom:100px;">
    <div id="app">
        
        @include ('layouts._nav')

        <main class="py-4 mt-5">
            <div class="container">
                {{ $slot }}
            </div>
           <flash message="{{ session('flash') }}"></flash>
        </main>

    </div>
    {{-- <script src="http://unpkg.com/turbolinks"></script> --}}
</body>
</html>
