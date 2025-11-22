<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule as FacadesSchedule;

Artisan::command('app:daily-email-of-low-stock', function (){})->daily()->at('12:00');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

FacadesSchedule::call(function (){
    
})->dailyAt('12:00');