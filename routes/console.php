<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command("aparat:clear", function () {

    $this->info("Cleared Videos Direcotry ");
    removeDir('videos');

    $this->info("Cleared Category Direcotry ");
    removeDir('categories');

    $this->info("Cleared Channels Direcotry ");
    removeDir('channels');

})->purpose('Clear All Aparat Temp Directory');
