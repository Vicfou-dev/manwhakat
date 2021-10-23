<?php
namespace App\Modules\Scrappers;


class MangaSearchScrapper extends Scrapper
{
    
    public function start() 
    {
        $data = $this->scrapping($this->getUrl());

        return $data;

    }

    private function scrapping(string $url) : ?array
    {
        $response = $this->client->get($url);
        $htmlString = (string) $response->getBody();
        $dom = $this->createDomFromString($htmlString);
        $contents = $dom->find('.story_item');

        $data = [];
        foreach($contents as $content) 
        {
            $manga = $this->parse($content);
            $data[] = $manga;
        }

        return $data;

    }
    
    private function parse($content) : array
    {
        $manga = $content->firstChild()->nextSibling();
        $list = $manga->getAttribute('href');
        $title = $manga->firstChild()->nextSibling()->getAttribute('alt');
        $image = $manga->firstChild()->nextSibling()->getAttribute('src');


        $manga   = array('title' => $title,'image' => $image, 'list'  => $list);

        return array('manga' => $manga);
    }
}