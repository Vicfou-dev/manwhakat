<?php
namespace App\Modules\Scrappers;
use Carbon\Carbon;

class MangaChapterScrapper extends Scrapper
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
        $contents = $dom->find('.chapter-list .row');

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
        $chapter = $content->firstChild()->nextSibling()->firstChild();
        $link = $chapter->getAttribute('href');
        $title = $chapter->getAttribute('title');

        $upload = $content->getChildren()[5];
        $time = $upload->getAttribute('title');
        if(strpos($time, 'ago') !== false) {
            $time = trim(str_replace('ago','', $time));
            $now = date_create();
            date_sub(date_create(), date_interval_create_from_date_string($time));
            $time = date('M-d-Y h:m', $now->getTimestamp());
        }

        return array('title' => $title, 'link' => $link, 'time' => $time);
    }
}