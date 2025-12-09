<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- CSS PERSONALE --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>   
    @include('navBar')
    @yield('navBar')
    
    <div>
        
        @yield('content')

    </div>

    @stack('scripts')
</body>
</html>