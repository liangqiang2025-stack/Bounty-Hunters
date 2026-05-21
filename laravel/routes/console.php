<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Scheduling\Schedule;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('logs:clear {--days=7 : Number of days to keep logs}', function () {
    $days = (int) $this->option('days');
    $logPath = storage_path('logs');
    $files = File::files($logPath);
    $cutoff = Carbon::now()->subDays($days);
    $deleted = 0;

    foreach ($files as $file) {
        if ($file->getExtension() === 'log' && $file->getMTime() < $cutoff->timestamp) {
            File::delete($file->getPathname());
            $deleted++;
        }
    }

    $this->info("Deleted {$deleted} log file(s) older than {$days} days.");
})->purpose('Clear old log files from storage/logs');
