<?php
namespace App\Modules\Scrappers;

use PhPHtmlParser\Dom\Node\TextNode;
class MangaInfoScrapper extends Scrapper
{
    public function start() 
    {
        $param = parse_url($this->getUrl());
        $host = $param['host'];

        switch($host) {
            case 'readmanganato.com' : 
                $service = app(MangaInfoReadmanganatoScrapper::class);
                break;
            case 'mangakakalot.com' : 
                $service = app(MangaInfoMangakakalotScrapper::class);
                break;
        }

        $service->setUrl($this->getUrl());
        $data = $service->start($this->getUrl());

        return $data;
    }

}