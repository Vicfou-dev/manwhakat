<?php
use App\Modules\Scrappers\MangaListScrapper;
use App\Modules\Scrappers\MangaInfoScrapper;
use App\Modules\Scrappers\MangaChapterScrapper;
use App\Modules\Scrappers\MangaChapterImageScrapper;
use App\Modules\Downloaders\MangeImageDownloader;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {

    /*
    $scrapper = app(MangaListScrapper::class);
    $scrapper->setUrl('https://mangakakalot.com/manga_list');
    $scrapper->start();
    */
    /*
    $scrapper = app(MangaInfoScrapper::class);
    $scrapper->setUrl('https://readmanganato.com/manga-bf978762');
    dump($scrapper->start());*/
    
    /*
    $scrapper = app(MangaChapterImageScrapper::class);
    $scrapper->setUrl('https://mangakakalot.com/chapter/rt927298/chapter_12');
    $res = $scrapper->start();*/
    
    /*
    $scrapper = app(MangaChapterScrapper::class);
    $scrapper->setUrl('https://mangakakalot.com/read-yr4dq158524515301');
    dump($scrapper->start());*/
    
    $downloader = app(MangeImageDownloader::class);
    $image = $downloader->downloadAsPng("https://s4.mkklcdnv6tempv2.com/mangakakalot/c2/cs922901/chapter_85/1.jpg");

    return $downloader->imgToHtml($image);
});


$router->get('mangas', 'MangaController@index');
$router->get('manga/{id}', 'MangaController@show');
$router->get('chapter/{id}', 'ChapterController@show');
$router->get('categories', 'CategoryController@index');
$router->get('authors', 'AuthorController@index');
$router->get('image', 'ImageController@getExternLink');