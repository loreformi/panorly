<!DOCTYPE html>
<html lang="en" data-theme="midnight">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - {{ config('panorly.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="panorly-card w-full max-w-sm p-8">
        <h1 class="text-2xl font-bold mb-6 text-center">{{ config('panorly.name') }}</h1>

        @if ($errors->any())
            <div class="text-sm text-red-400 mb-4">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <input type="email" name="email" placeholder="Email" required class="panorly-input w-full" value="{{ old('email') }}">
            <input type="password" name="password" placeholder="Password" required class="panorly-input w-full">
            <label class="flex items-center gap-2 text-sm opacity-80">
                <input type="checkbox" name="remember"> Remember me
            </label>
            <button type="submit" class="panorly-btn w-full">Log in</button>
        </form>

        @if(config('panorly.allow_registration'))
        <p class="text-sm text-center mt-6 opacity-70">
            No account yet? <a href="{{ route('register') }}" class="panorly-accent-text">Create one</a>
        </p>
        @endif
    </div>
</body>
</html>
