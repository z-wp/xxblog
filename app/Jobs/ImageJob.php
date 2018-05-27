<?php

namespace App\Jobs;

use App\Http\Repositories\MapRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class ImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mapRepository;

    /**
     * Create a new job instance.
     *
     * @param MapRepository $mapRepository
     */
    public function __construct(MapRepository $mapRepository)
    {
        $this->mapRepository = $mapRepository;
    }

    public static function get_job($name)
    {
        $jobs = [
            'bing' => BingImageJob::class,
            'picsum' => PicsumImageJob::class,
        ];
        return $jobs[$name];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image_url = $this->get_image_url();
        $this->mapRepository->saveSetting('dynamic_header_bg_image', $image_url);
    }

    /**
     * @return string
     */
    protected abstract function get_image_url();
}
