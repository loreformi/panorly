@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <h1 class="text-2xl font-bold">Customize your dashboard</h1>

    <div class="panorly-card p-6">
        <h2 class="font-semibold mb-4">Theme preset</h2>
        <form method="POST" action="{{ route('theme.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-4 gap-3">
                @foreach(['midnight', 'daylight', 'forest', 'sunset'] as $preset)
                    <label class="panorly-card p-3 text-center cursor-pointer text-sm capitalize">
                        <input type="radio" name="preset" value="{{ $preset }}"
                               {{ ($theme->preset ?? 'midnight') === $preset ? 'checked' : '' }}
                               class="hidden peer">
                        <span class="peer-checked:panorly-accent-text">{{ $preset }}</span>
                    </label>
                @endforeach
            </div>

            <div class="grid grid-cols-3 gap-4">
                <label class="text-sm">Accent
                    <input type="color" name="accent_color" value="{{ $theme->accent_color ?? '#6366f1' }}" class="w-full h-10 rounded mt-1">
                </label>
                <label class="text-sm">Background
                    <input type="color" name="background_color" value="{{ $theme->background_color ?? '#0f1115' }}" class="w-full h-10 rounded mt-1">
                </label>
                <label class="text-sm">Text
                    <input type="color" name="text_color" value="{{ $theme->text_color ?? '#e7e9ee' }}" class="w-full h-10 rounded mt-1">
                </label>
            </div>

            <label class="block text-sm">Layout density
                <select name="layout_density" class="panorly-input w-full mt-1">
                    <option value="compact" {{ ($theme->layout_density ?? '') === 'compact' ? 'selected' : '' }}>Compact</option>
                    <option value="comfortable" {{ ($theme->layout_density ?? 'comfortable') === 'comfortable' ? 'selected' : '' }}>Comfortable</option>
                    <option value="spacious" {{ ($theme->layout_density ?? '') === 'spacious' ? 'selected' : '' }}>Spacious</option>
                </select>
            </label>

            <button type="submit" class="panorly-btn">Save theme</button>
        </form>
    </div>

    <div class="panorly-card p-6">
        <h2 class="font-semibold mb-4">Background image</h2>
        <form method="POST" action="{{ route('theme.background.upload') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <input type="file" name="background" accept="image/png,image/jpeg,image/webp" required class="panorly-input w-full">
            <button type="submit" class="panorly-btn">Upload background</button>
        </form>
    </div>

    <div class="panorly-card p-6">
        <h2 class="font-semibold mb-4">Portability</h2>
        <div class="flex gap-3">
            <a href="{{ route('theme.export') }}" class="panorly-btn">Export theme (JSON)</a>
        </div>
        <form method="POST" action="{{ route('theme.import') }}" enctype="multipart/form-data" class="flex gap-3 mt-3">
            @csrf
            <input type="file" name="theme_file" accept="application/json" required class="panorly-input flex-1">
            <button type="submit" class="panorly-btn">Import</button>
        </form>
    </div>
</div>
@endsection
