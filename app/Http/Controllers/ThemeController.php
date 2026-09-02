<?php

namespace App\Http\Controllers;

use App\Models\UserTheme;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    public function edit(Request $request)
    {
        return view('theme.edit', ['theme' => $request->user()->theme ?? new UserTheme()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'preset' => 'nullable|string|in:midnight,daylight,forest,sunset,custom',
            'accent_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'text_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'layout_density' => 'nullable|string|in:compact,comfortable,spacious',
            'extra' => 'nullable|array',
        ]);
        $request->user()->theme()->updateOrCreate([], $data);
        return back()->with('status', 'Theme saved.');
    }

    public function uploadBackground(Request $request)
    {
        $request->validate(['background' => 'required|image|mimes:jpeg,png,webp|max:5120']);
        $theme = $request->user()->theme;
        if ($theme?->background_image_path) Storage::disk('public')->delete($theme->background_image_path);
        $path = $request->file('background')->store('backgrounds', 'public');
        $request->user()->theme()->updateOrCreate([], ['background_image_path' => $path]);
        return back()->with('status', 'Background uploaded.');
    }

    public function export(Request $request): Response
    {
        $theme = $request->user()->theme;
        return response(json_encode($theme?->toExportArray() ?? ['format_version'=>1], JSON_PRETTY_PRINT), 200, ['Content-Type'=>'application/json','Content-Disposition'=>'attachment; filename="panorly-theme.json"']);
    }

    public function import(Request $request)
    {
        $request->validate(['theme_file' => 'required|file|mimes:json|max:1024']);
        $decoded = json_decode(file_get_contents($request->file('theme_file')->getRealPath()), true);
        if (!is_array($decoded) || ($decoded['format_version'] ?? null) !== 1) return back()->withErrors(['theme_file'=>'Invalid or unsupported theme file.']);
        $data = collect($decoded)->only(['preset','accent_color','background_color','text_color','layout_density','extra'])->toArray();
        $request->user()->theme()->updateOrCreate([], $data);
        return back()->with('status', 'Theme imported.');
    }
}
