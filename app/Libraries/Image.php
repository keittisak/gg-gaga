<?php

namespace App\Libraries;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Image
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function upload($file, $path = 'images')
    {
        $image = Storage::disk('public')->put($path, $file);
        $url = Storage::url($image);
        return asset($url);
    }
}