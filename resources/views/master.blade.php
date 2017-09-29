<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel Notes</title>

        <link rel="stylesheet" href="/css/app.css" />

        {{-- Prismjs --}}
        <link rel="stylesheet" href="/css/prism.css">
        <script src="/js/prism.js"></script>

    </head>

    <body>

        @yield('localeGreeting')

        <div class="container w3-container" style="margin-bottom: 20px;" id="app">
            @yield('content')
        </div>

        @yield('footer')

        <script src="/js/app.js"></script>

    </body>
</html>
