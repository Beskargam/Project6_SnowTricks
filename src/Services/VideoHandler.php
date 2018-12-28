<?php

namespace App\Services;


class VideoHandler
{
    public function handleVideos($videos, $trick)
    {
        foreach ($videos as $video) {
            $trick->addVideo($video);
        }
    }
}