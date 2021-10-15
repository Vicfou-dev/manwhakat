<?php
use PHPHtmlParser\Dom;

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

    $httpClient = new \GuzzleHttp\Client(['verify' => 'C:\wamp64\cacert.pem']);
    $page = 1;
    $data = [];
    for($i = 1; $i <= $page; $i++) {
        $response = $httpClient->get('https://mangakakalot.com/manga_list');
        $htmlString = (string) $response->getBody();
        $dom = new Dom;
        $dom->loadStr($htmlString);
        $contents = $dom->find('.list-truyen-item-wrap');
        /*
        .then( $ => $('.list-truyen-item-wrap').toArray().map(element => {
        const manga = $(element).children().first();
        const title = manga.attr('title');
        const image = manga.children().first().attr('src');
        const chapter = manga.next().next();
        const info = chapter.attr('title');
        const link = chapter.attr('href');
        return { manga : { title, image }, chapter : { info, link } }
        */
        foreach($contents as $content) {
            $manga = $content->firstChild();
            $title = $manga->getAttribute('title');
            dump($content->innerHtml);
            $image = $manga->firstChild()->getAttribute('src');
        }
    }

    return $router->app->version();
});
