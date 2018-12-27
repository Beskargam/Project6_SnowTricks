<?php

namespace App\Services;


class VideoHandler
{
    public function handleVideos($videos)
    {
        foreach ($videos as $video) {
            $video->handle();
        }
    }
}