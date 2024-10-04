<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
</head>
<body>
    @include('partials.header')

    <div class="container">
        @yield('content')
    </div>

    @include('partials.footer')
</body>
</html>


<style>

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

header {
    flex: 0 0 auto;
}

footer {
    flex: 0 0 auto;
}

.container {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

</style>
