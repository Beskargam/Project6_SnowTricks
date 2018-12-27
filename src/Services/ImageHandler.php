<?php

namespace App\Services;


class ImageHandler
{
    public function handleImages($images, $path)
    {
        foreach ($images as $image) {
            $image->handle($path);
        }
    }
}