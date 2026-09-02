<?php

namespace App\Http\Controllers;

use App\Models\App;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url|max:2048',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['sort_order'] = $request->user()->apps()->max('sort_order') + 1;

        App::create($data);

        return back()->with('status', 'app-added');
    }

    public function update(Request $request, App $app)
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'url' => 'sometimes|url|max:2048',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $app->update($data);

        return back()->with('status', 'app-updated');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:apps,id',
        ]);

        foreach ($data['order'] as $index => $appId) {
            App::where('id', $appId)
                ->where('user_id', $request->user()->id)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function destroy(Request $request, App $app)
    {
        abort_unless($app->user_id === $request->user()->id, 403);

        $app->delete();

        return back()->with('status', 'app-deleted');
    }
}
