<?php

namespace App\Jobs;

/** see https://picsum.photos/
 * Class PicsumImageJob
 * @package App\Jobs
 */
class PicsumImageJob extends ImageJob
{

    protected function get_image_url()
    {
        return 'https://picsum.photos/1600/600/?' . rand();
    }
}
