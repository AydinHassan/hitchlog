<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">

    @vite(['resources/assets/css/app.css', 'resources/assets/js/app.js'])
</head>
<body class="min-h-screen">
    <div id="app">
        <nav class="bg-white shadow-sm">
            <div class="mx-auto flex h-14 max-w-6xl items-center justify-between px-4">
                <a class="text-lg font-semibold text-slate-900" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                @guest
                    <a class="text-sm font-semibold text-slate-600 hover:text-slate-900" href="{{ route('login') }}">{{ __('Login') }}</a>
                @else
                    <user-menu name="{{ Auth::user()->name }}">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full cursor-pointer px-4 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </user-menu>
                @endguest
            </div>
        </nav>

        <main class="mx-auto max-w-6xl px-4 py-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
