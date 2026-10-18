<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('store:reconcile --fail-on-difference')
    ->dailyAt('22:30')
    ->timezone(config('store.timezone'))
    ->withoutOverlapping()
    ->onOneServer();

Schedule::command('model:prune')
    ->dailyAt('23:10')
    ->timezone(config('store.timezone'))
    ->withoutOverlapping()
    ->onOneServer();
