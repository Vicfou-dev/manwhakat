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
    $scrapper = app(MangaInfoScrapper::class);
    $scrapper->setUrl('https://readmanganato.com/manga-bf978762');
    dump($scrapper->start());
    /*
    $scrapper = app(MangaChapterImageScrapper::class);
    $scrapper->setUrl('https://mangakakalot.com/chapter/rt927298/chapter_12');
    $scrapper->start();*/
    /*
    $downloader = app(MangeImageDownloader::class);
    $image = $downloader->downloadAsPng("https://s8.mkklcdnv6temp.com/mangakakalot/r2/rt927298/chapter_12_the_first_compliment/1.jpg");

    return $downloader->imgToHtml($image);*/
});
