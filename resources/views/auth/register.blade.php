<!DOCTYPE html>
<html lang="en" data-theme="midnight">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up - {{ config('panorly.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="panorly-card w-full max-w-sm p-8">
        <h1 class="text-2xl font-bold mb-6 text-center">Create your account</h1>

        @if ($errors->any())
            <div class="text-sm text-red-400 mb-4">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Name" required class="panorly-input w-full" value="{{ old('name') }}">
            <input type="email" name="email" placeholder="Email" required class="panorly-input w-full" value="{{ old('email') }}">
            <input type="password" name="password" placeholder="Password" required class="panorly-input w-full">
            <input type="password" name="password_confirmation" placeholder="Confirm password" required class="panorly-input w-full">
            <button type="submit" class="panorly-btn w-full">Sign up</button>
        </form>

        <p class="text-sm text-center mt-6 opacity-70">
            Already have an account? <a href="{{ route('login') }}" class="panorly-accent-text">Log in</a>
        </p>
    </div>
</body>
</html>
