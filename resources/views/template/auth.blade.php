<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WORK</title>
    <link rel="stylesheet" href="{{ asset('css/login-register.css') }}">
    @stack('styles')
</head>

<body>
    @yield('content')

    <script src="https://code.jquery.com/jquery-3.6.1.slim.js"></script>
    <script src="{{ asset('js/login-register.js') }}"></script>
    @stack('scripts')
</body>

</html>
