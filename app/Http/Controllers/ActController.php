<?php

namespace App\Http\Controllers;

use App\Events\UpdateAct;
use App\Models\Act;
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

        event(new UpdateAct($act));

        return $act;
    }
}
