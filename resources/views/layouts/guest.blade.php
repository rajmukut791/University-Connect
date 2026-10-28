<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark" style="color-scheme: dark; background-color: #020617;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'University Connect') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Figtree', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #020617;
        }

        ::selection {
            background: rgba(168, 85, 247, .45);
            color: white;
        }

        input,
        select,
        textarea {
            outline: none;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: white;
            -webkit-box-shadow: 0 0 0px 1000px rgba(15, 23, 42, .92) inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        @keyframes ucFadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .uc-auth-enter {
            animation: ucFadeUp .7s ease both;
        }
    </style>
</head>

<body class="antialiased">
    <div class="uc-auth-enter">
        {{ $slot }}
    </div>
</body>
</html>