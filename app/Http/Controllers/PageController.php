<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Day;
use Inertia\Inertia;

class PageController extends Controller
{
    public function stream()
    {
        if (!auth()->user()->hasPermissionTo('stream')) {
            return redirect()->route('welcome');
        }

        return Inertia::render('StreamPage', [
            'currentAct' => Act::query()->where('current', true)->first(),
        ]);
    }

    public function screen()
    {
        if (!auth()->user()->hasPermissionTo('acts')) {
            return redirect()->route('welcome');
        }

        return Inertia::render('ScreenPage', [
            'currentAct' => Act::query()->where('current', true)->first(),
        ]);
    }

    public function backstage()
    {
        if (!auth()->user()->hasPermissionTo('acts')) {
            return redirect()->route('welcome');
        }

        $day = Day::query()->where('current', true)->first();

        return Inertia::render('BackstagePage', [
            'currentAct' => Act::query()->where('current', true)->first(),
            'acts' => Act::all(),
            'dayId' => $day['id'],
            'day' => $day,
        ]);
    }

    public function dashboard()
    {
        if (!auth()->user()->hasPermissionTo('dashboard')) {
            return redirect()->route('welcome');
        }

        return Inertia::render('Dashboard', [
            'acts' => Act::all(),
            'currentAct' => Act::query()->where('current', true)->first(),
            'eventDays' => Day::all(),
        ]);
    }
}
