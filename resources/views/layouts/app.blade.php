<!DOCTYPE html>
<html lang="en" data-theme="{{ $theme->preset ?? 'midnight' }}" data-density="{{ $theme->layout_density ?? 'comfortable' }}">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="csrf-token" content="{{ csrf_token() }}"><title>{{ config('panorly.name') }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
@if($theme?->background_image_path)<style>:root{--panorly-bg-image:url('{{ Storage::url($theme->background_image_path) }}')}</style>@endif
@if($theme?->accent_color)<style>:root{--panorly-accent:{{ $theme->accent_color }};--panorly-bg:{{ $theme->background_color ?? 'var(--panorly-bg)' }};--panorly-text:{{ $theme->text_color ?? 'var(--panorly-text)' }}}</style>@endif
</head>
<body><div class="panorly-shell"><nav class="panorly-nav"><a class="panorly-logo" href="{{ route('dashboard') }}">panor<b>ly</b></a>@auth <div style="display:flex;align-items:center;gap:14px"><span class="panorly-muted" style="font-size:13px">{{ auth()->user()->name }}</span><a class="panorly-muted" style="font-size:13px;text-decoration:none" href="{{ route('theme.edit') }}">Theme Studio</a><form method="POST" action="{{ route('logout') }}">@csrf<button class="panorly-btn panorly-btn-secondary" style="padding:8px 11px">Log out</button></form></div>@endauth</nav>
@if(session('status'))<div class="panorly-card" style="padding:12px 15px;margin-bottom:14px;font-size:13px">{{ session('status') }}</div>@endif
@yield('content')</div></body></html>
