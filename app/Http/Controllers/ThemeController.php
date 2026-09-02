<?php

namespace App\Http\Controllers;

use App\Models\UserTheme;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    public function edit(Request $request)
    {
        $theme = $request->user()->theme ?? new UserTheme();

        return view('theme.edit', ['theme' => $theme]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'preset' => 'nullable|string|in:midnight,daylight,forest,sunset,custom',
            'accent_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'text_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'layout_density' => 'nullable|string|in:compact,comfortable,spacious',
        ]);

        $request->user()->theme()->updateOrCreate([], $data);

        return back()->with('status', 'theme-updated');
    }

    public function uploadBackground(Request $request)
    {
        $request->validate([
            'background' => 'required|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('background')->store('backgrounds', 'public');

        $request->user()->theme()->updateOrCreate([], [
            'background_image_path' => $path,
        ]);

        return back()->with('status', 'background-updated');
    }

    public function export(Request $request): Response
    {
        $theme = $request->user()->theme;
        $json = json_encode($theme?->toExportArray() ?? [], JSON_PRETTY_PRINT);

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="panorly-theme.json"',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'theme_file' => 'required|file|mimes:json|max:1024',
        ]);

        $content = file_get_contents($request->file('theme_file')->getRealPath());
        $decoded = json_decode($content, true);

        if (! is_array($decoded) || ($decoded['format_version'] ?? null) !== 1) {
            return back()->withErrors(['theme_file' => 'Invalid or unsupported theme file.']);
        }

        $data = collect($decoded)->only([
            'preset', 'accent_color', 'background_color', 'text_color', 'layout_density', 'extra',
        ])->toArray();

        $request->user()->theme()->updateOrCreate([], $data);

        return back()->with('status', 'theme-imported');
    }
}
