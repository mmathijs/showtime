<?php

namespace App\Http\Controllers;

use App\Events\Countdown;
use App\Events\UpdateAct;
use App\Events\UpdateAllActs;
use App\Models\Act;
use App\Models\Day;
use Illuminate\Support\Facades\Log;

class ActController extends Controller
{
    public function launch()
    {
        Log::debug('Act has been updated');
        Log::debug(auth()->user()->getAllPermissions());
        Log::debug(auth()->user()->hasPermissionTo('dashboard') ? 'true' : 'false');
        if (!auth()->user()->hasPermissionTo('dashboard')) {
            // return unauthorized
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $postData = request()->validate([
            'act_id' => 'required|integer',
        ]);


        $act = Act::query()->where('id', $postData['act_id'])->first();

        if (!$act) {
            return response()->json(['message' => 'Act not found'], 404);
        }

        Act::query()->where('current', true)->update(['current' => false]);

        $act->current = true;
        $act->save();

        $day = $act->day;

        if (Day::query()->where('id', $day)->where('current', true)->count() === 0) {
            Day::query()->where('current', true)->update(['current' => false]);

            $day = Day::query()->where('id', $day)->first();

            if (!$day) {
                return response()->json(['message' => 'Day not found'], 404);
            }

            $day->current = true;
            $day->save();

            $this->updateAll();
        }

        event(new UpdateAct($act));

        return $act;
    }

    public function start()
    {
        if (!auth()->user()->hasPermissionTo('dashboard')) {
            // return unauthorized
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $postData = request()->validate([
            'act_id' => 'required|integer',
        ]);

        $act = Act::query()->where('id', $postData['act_id'])->first();

        if (!$act) {
            return response()->json(['message' => 'Act not found'], 404);
        }

        $act->current = true;
        $act->save();

        $this->timer();

        return $act;
    }

    public function timer()
    {
        $timer = 5;
        $previousTimer = $timer;

        event(new Countdown(5, true));

        $startTime = time() - 0.25;

        $count = 1;

        while ($timer > 0) {
            if ($previousTimer !== $timer) {
                event(new Countdown($timer, true));
                $previousTimer = $timer;
            }
            if (time() - $startTime > $count) {
                $timer--;
                $count++;
            }
        }

        event(new Countdown(0, true));

        sleep(4);

        event(new Countdown(0, false));
    }

    public function updateAll()
    {
        if (!auth()->user()->hasPermissionTo('dashboard')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $args = new UpdateAllActs(
            Act::query()->where('current', true)->first(),
            Act::all(),
            Day::query()->where('current', true)->first(),
            Day::all()
        );

        event($args);

        return response()->json(['message' => 'All acts updated'], 200);
    }
}
