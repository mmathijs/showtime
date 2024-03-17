<?php

namespace App\Http\Controllers;

use App\Models\Act;
use Inertia\Inertia;

class PageController extends Controller
{
    public function stream()
    {
        if (!auth()->user()->hasPermissionTo('stream')) {
            return redirect()->route('welcome');
        }

        return Inertia::render('StreamPage', [
            'currentAct' =>  [
                'title' => 'Act 1',
            ]
        ]);
    }

    public function dashboard()
    {
        if (!auth()->user()->hasPermissionTo('dashboard')) {
            return redirect()->route('welcome');
        }

        return Inertia::render('Dashboard', [
            'acts' => Act::all(),
            'currentAct' =>  Act::query()->where('current', true)->first(),
        ]);
    }
}
