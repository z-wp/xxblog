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
        $header_image_provider = get_config('header_image_provider', 'none');
        if ($header_image_provider != 'none') {
            $header_image_update_rate = get_config('header_image_provider', 'every_day');
            $event = $schedule->job(ImageJob::get_job($header_image_provider));
            if ($header_image_update_rate == 'every_minute') {
                $event->everyMinute();
            } elseif ($header_image_update_rate == 'every_hour') {
                $event->hourly();
            } elseif ($header_image_update_rate == 'every_day') {
                $event->daily();
            } elseif ($header_image_update_rate == 'every_week') {
                $event->weekly();
            }
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
