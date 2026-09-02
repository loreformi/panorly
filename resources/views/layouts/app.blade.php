<!DOCTYPE html>
<html lang="en" data-theme="{{ $theme->preset ?? 'midnight' }}" data-density="{{ $theme->layout_density ?? 'comfortable' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('panorly.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(($theme->background_image_path ?? null))
        <style>:root { --panorly-bg-image: url('{{ Storage::url($theme->background_image_path) }}'); }</style>
    @endif
</head>
<body class="min-h-screen">
    <nav class="flex items-center justify-between px-6 py-4">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold panorly-accent-text">{{ config('panorly.name') }}</a>
        @auth
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('theme.edit') }}" class="opacity-80 hover:opacity-100">Settings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="opacity-80 hover:opacity-100">Logout</button>
            </form>
        </div>
        @endauth
    </nav>

    <main class="px-6 pb-12">
        @if(session('status'))
            <div class="panorly-card px-4 py-3 mb-6 text-sm">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
