<?php

namespace App\Jobs;


class BingImageJob extends ImageJob
{

    protected function get_image_url()
    {
        $json = json_decode(file_get_contents('https://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1'), true);
        $image_url = 'https://cn.bing.com/' . $json['images'][0]['url'];
        return $image_url;
    }
}
