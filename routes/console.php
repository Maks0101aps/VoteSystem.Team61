<?php

use Illuminate\Support\Facades\Schedule;

// Reset demo environment daily at midnight
Schedule::command('migrate:fresh --seed --force')->dailyAt('00:00');

// Check petition statuses every minute
Schedule::command('petitions:check-status')->everyMinute();
