<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('schedule:run', function () {
    Artisan::call('posts:clean');
})->daily();