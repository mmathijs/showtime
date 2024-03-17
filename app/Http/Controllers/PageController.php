<?php

namespace App\Http\Controllers;

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
}
