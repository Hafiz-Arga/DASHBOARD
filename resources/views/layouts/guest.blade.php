<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Superstore EIS')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png?v=2') }}">
    {{-- FONT PLUS JAKARTA SANS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* Design token disamakan dengan layouts/app.blade.php */
        :root {
            --primary: #3976C5;
            --primary-dark: #285EA8;
            --primary-light: #EAF2FC;

            --navy: #172B4D;
            --background: #F6F8FC;
            --surface: #FFFFFF;

            --text-primary: #172B4D;
            --text-secondary: #475569;
            --text-muted: #64748B;

            --border: #E2E8F0;
            --danger: #D64545;
            --danger-bg: #FDEEEE;

            --radius: 12px;
            --shadow: 0 2px 8px rgba(23, 43, 77, 0.04);
            --font-main: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            background: var(--background);
            color: var(--text-primary);
            font-size: 16px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        button,
        input {
            font-family: inherit;
        }

        a {
            color: inherit;
        }
    </style>

    @stack('styles')
</head>

<body>
    @yield('content')
</body>

</html>
