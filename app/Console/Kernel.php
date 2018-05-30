<?php

namespace App\Console;

use App\Jobs\ImageJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        try {
            $header_image_provider = get_config('header_image_provider', 'none');
            if ($header_image_provider != 'none') {
                $header_image_update_rate = get_config('header_image_update_rate', 'every_day');
                $imageJob = ImageJob::get_job($header_image_provider);
                $method = 'daily';
                if ($header_image_update_rate == 'every_minute') {
                    $method = 'everyMinute';
                } elseif ($header_image_update_rate == 'every_hour') {
                    $method = 'hourly';
                } elseif ($header_image_update_rate == 'every_day') {
                    $method = 'daily';
                } elseif ($header_image_update_rate == 'every_week') {
                    $method = 'weekly';
                }
                $schedule->job(app($imageJob))->$method();
            }
        } catch (\Exception $e) {
            //ignore. schedule will be called when installing, which db is not configured.
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
