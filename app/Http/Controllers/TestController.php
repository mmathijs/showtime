<?php

namespace App\Http\Controllers;

use App\Events\Countdown;
use App\Events\UpdateAct;
use App\Events\UpdateAllActs;
use App\Models\Act;
use App\Models\Day;

class TestController extends Controller
{
    public function index()
    {
        $timer = 5;
        $previousTimer = $timer;

        event(new Countdown($timer, true));

        $startTime = time();

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

        sleep(2);

        event(new Countdown(0, false));

        return 'test';
    }
}
