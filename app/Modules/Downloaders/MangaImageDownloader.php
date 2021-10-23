<?php
namespace App\Modules\Downloaders;
use GuzzleHttp\Client;

class MangaImageDownloader {

    private $client;

    public function __construct() {
        $this->client = app(client::class, ['headers' => ['Referer' => 'mangakakalot.com']]);

        $this->image = null;
    }


    public function downloadToBase64($url) {
        $response = $this->client->get($url);
        $body = $response->getBody()->getContents();
        $this->image = $this->bodyToBase64($body);
        return $this->image;
    }

    public function downloadToJpg($url) {
        $response = $this->client->get($url);
        $body = $response->getBody()->getContents();
        $this->image = $this->bodyToJpg($body);
        return $this->image;
    }

    private function bodyToJpg($body) {
        $source = imagecreatefromstring($body);
        return $source;
    }

    private function bodyToBase64($body) {
        $base64 = base64_encode($body);
        $mime = "image/jpeg";
        $img = ('data:' . $mime . ';base64,' . $base64);
        return $img;
    }

    public function imgToHtml(?string $image = '') {
        $image = $image ? $image : $this->image;
        return "<img src=$image alt='ok'>";
    }

}