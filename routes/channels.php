<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('act-update', function () {
    return true;
});
