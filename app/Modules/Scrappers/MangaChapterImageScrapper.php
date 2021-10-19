<?php
namespace App\Modules\Scrappers;
use Carbon\Carbon;
use PhPHtmlParser\Dom\Node\TextNode;

class MangaChapterImageScrapper extends Scrapper
{
    public function start() 
    {
        $data = $this->scrapping($this->getUrl());

        return $data;
    }

    private function scrapping(string $url) : ?array
    {
        $dom = $this->getDom();
        if($dom == null)
        {
            $response = $this->client->get($url);
            $htmlString = (string) $response->getBody();
            $dom = $this->createDomFromString($htmlString);
        }
        $contents = $dom->find('.container-chapter-reader');

        $data = [];
        foreach($contents as $content) 
        {
            $manga = $this->parse($content);
            $data[] = $manga;
        }

        $data = array_shift($data);

        return $data;

    }
    
    private function parse($content) : array
    {

        $images_node = $content->getChildren();
        $images = [];
        foreach($images_node as $image) {
            if($image instanceof TextNode) {
                continue;
            }

            $src = $image->getAttribute('src');
            if($src == null) {
                continue;
            }

            $alt = $image->getAttribute('alt');
            $alt = trim(str_replace('Mangakakalot.com', '', $alt));

            $title = $image->getAttribute('title');
            $title = trim(str_replace('Mangakakalot.com', '', $title));

            $images[] = array('src' => $src, 'alt' => $alt, 'title' => $title);
        }
        
        return $images;
    }
}