<?php
namespace App\Modules\Scrappers;


class MangaListScrapper extends Scrapper
{
    public function start(int $pageNumber = 1) 
    {
        $data = [];
        for($i = 1; $i <= $pageNumber; $i++) {
            $param = array('page' => $i);
            $url = $this->getUrl() . "?" . http_build_query($param) ;
            $result = $this->scrapping($url);
            $data = array_merge($data, $result);
        }

        return $data;

    }

    private function scrapping(string $url) : ?array
    {
        $response = $this->client->get($url);
        $htmlString = (string) $response->getBody();
        $dom = $this->createDomFromString($htmlString);
        $contents = $dom->find('.list-truyen-item-wrap');

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
        $title = $manga->getAttribute('title');
        $list = $manga->getAttribute('href');
        $image = $manga->firstChild()->nextSibling()->getAttribute('src');

        $chapter = $manga->nextSibling()->nextSibling()->nextSibling()->nextSibling();
        $info = $chapter->getAttribute('title');
        $link = $chapter->getAttribute('href');

        $manga   = array('title' => $title,'image' => $image, 'list'  => $list);
        $chapter = array('info' => $info, 'link' => $link);

        return array('manga' => $manga, 'chapter' => $chapter );
    }
}