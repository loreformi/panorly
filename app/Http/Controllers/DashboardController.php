<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $apps = $request->user()->apps;
        $theme = $request->user()->theme;

        return view('dashboard', [
            'apps' => $apps,
            'theme' => $theme,
        ]);
    }
}
