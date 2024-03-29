<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Models\Day;
use Illuminate\Support\Facades\Log;
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
            'showDescription' => request()->showDescription ?? false,
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

    public function home()
    {
        // get IP address of client
        $ip = request()->ip();

        $allowedIps = config('app.allowed_ips');

        Log::debug('Allowed IPs: ' . $allowedIps);
        Log::debug('IP address: ' . $ip);

        if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $http_x_headers = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );

            Log::debug('HTTP_X_FORWARDED_FOR: ' . $_SERVER['HTTP_X_FORWARDED_FOR']);

            $_SERVER['REMOTE_ADDR'] = $http_x_headers[0];
        }

        $ip = $_SERVER['REMOTE_ADDR'];
        Log::debug('REMOTE_ADDR: ' . $_SERVER['REMOTE_ADDR']);

        if ($allowedIps !== '*' && !in_array($ip, explode(',', $allowedIps)) && !(auth()->user() && auth()->user()->hasPermissionTo('acts'))) {
            return response('Unauthorized', 401);
        }

        Log::debug('IP address: ' . $ip);

        return Inertia::render('WelcomePage', [
            'currentAct' => Act::query()->where('current', true)->first(),
            'acts' => Act::all(),
            'day' => Day::query()->where('current', true)->first(),
            'dayId' => Day::query()->where('current', true)->first()['id'],
            'permissionTo' => auth()->user() ? auth()->user()->hasPermissionTo('acts') : false,
            'title' => env('APP_TITLE') ?? 'Welcome',
        ]);
    }

    public function editAct($id)
    {
        if (!auth()->user()->hasPermissionTo('dashboard')) {
            return redirect()->route('welcome');
        }

        $act = Act::query()->find($id);

        if (!$act) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('EditActPage', [
            'act' => $act,
        ]);
    }
}
