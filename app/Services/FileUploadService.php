<?php
namespace App\Services;

use Log;

class FileUploadService
{
    public static function upload($imageName, $image)
    {
        try {
            $image->move(public_path('/profile_images'), $imageName);
        } catch (\Exception $e) {
            Log::error('upload file error: '.$e->getMessage());
        }
    }
}
