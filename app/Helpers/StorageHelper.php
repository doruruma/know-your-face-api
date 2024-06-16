<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    public static function putFileAs($path, $file, $name): bool
    {
        try {
            Storage::disk('public')->putFileAs($path, $file, $name);
            return true;
        } catch (\Throwable $th) {
            Log::channel('storage')->error($th->getMessage());
            return false;
        }
    }

    public static function deleteFile($path): bool
    {
        try {
            Storage::disk('public')->delete($path);
            return true;
        } catch (\Throwable $th) {
            Log::channel('storage')->error($th->getMessage());
            return false;
        }
    }
}
