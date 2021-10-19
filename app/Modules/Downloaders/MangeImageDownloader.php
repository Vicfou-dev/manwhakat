<?php
namespace App\Modules\Downloaders;
use GuzzleHttp\Client;

class MangeImageDownloader {

    private $client;

    public function __construct() {
        $this->client = app(client::class, ['headers' => ['Referer' => 'mangakakalot.com']]);

        $this->image = null;
    }


    public function downloadAsPng($url) {
        $response = $this->client->get($url);
        $body = $response->getBody()->getContents();
        $this->image = $this->bodyToPng($body);
        return $this->image;
    }

    private function bodyToPng($body) {
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