<?php

namespace App\Http\Controllers;

use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AppController extends Controller
{
    private function rules(bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return [
            'title' => [$required, 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'url' => [$required, 'url', 'max:2048'],
            'icon_type' => ['nullable', Rule::in(['initial', 'upload', 'url'])],
            'icon_url' => ['nullable', 'url', 'max:2048'],
            'icon_file' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
            'color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'gradient_from' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'gradient_to' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'size' => ['nullable', Rule::in(['small', 'medium', 'wide', 'large'])],
            'open_in_new_tab' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data = $this->prepareData($request, $data);
        $data['user_id'] = $request->user()->id;
        $data['sort_order'] = ((int) $request->user()->apps()->max('sort_order')) + 1;
        App::create($data);
        return back()->with('status', 'App added.');
    }

    public function update(Request $request, App $app)
    {
        $this->authorizeApp($request, $app);
        $data = $request->validate($this->rules(true));
        $data = $this->prepareData($request, $data, $app);
        $app->update($data);
        return back()->with('status', 'App updated.');
    }

    public function duplicate(Request $request, App $app)
    {
        $this->authorizeApp($request, $app);
        $copy = $app->replicate(['id']);
        $copy->title = $app->title.' copy';
        $copy->sort_order = ((int) $request->user()->apps()->max('sort_order')) + 1;
        $copy->save();
        return back()->with('status', 'App duplicated.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate(['order' => ['required', 'array', 'max:250'], 'order.*' => ['integer']]);
        $owned = $request->user()->apps()->pluck('id')->flip();
        foreach ($data['order'] as $index => $appId) {
            if ($owned->has($appId)) App::whereKey($appId)->update(['sort_order' => $index]);
        }
        return response()->json(['status' => 'ok']);
    }

    public function destroy(Request $request, App $app)
    {
        $this->authorizeApp($request, $app);
        if ($app->icon_type === 'upload' && $app->icon_path) Storage::disk('public')->delete($app->icon_path);
        $app->delete();
        return back()->with('status', 'App deleted.');
    }

    private function authorizeApp(Request $request, App $app): void
    {
        abort_unless($app->user_id === $request->user()->id, 403);
    }

    private function prepareData(Request $request, array $data, ?App $app = null): array
    {
        $type = $data['icon_type'] ?? $app?->icon_type ?? 'initial';
        $data['icon_type'] = $type;
        unset($data['icon_file'], $data['icon_url']);
        if ($type === 'upload' && $request->hasFile('icon_file')) {
            if ($app?->icon_type === 'upload' && $app->icon_path) Storage::disk('public')->delete($app->icon_path);
            $data['icon_path'] = $request->file('icon_file')->store('icons', 'public');
            $data['icon'] = null;
        } elseif ($type === 'url' && $request->filled('icon_url')) {
            $data['icon_path'] = $request->input('icon_url');
            $data['icon'] = null;
        } elseif ($type === 'initial') {
            $data['icon_path'] = null;
        }
        return $data;
    }
}
