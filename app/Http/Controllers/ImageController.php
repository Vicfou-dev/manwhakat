<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Downloaders\MangaImageDownloader;
use Exception;

class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getExternLink(Request $request) 
    {
        $url = $request->get('url');
        if($url == null) {
            throw new Exception();
        }

        $downloader = app(MangaImageDownloader::class);
        $image = $downloader->downloadToJpg($url);

        header('Content-type: image/jpg');
        echo imagejpeg($image);
    }
}   