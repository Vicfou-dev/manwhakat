<?php
namespace App\Modules\Scrappers;

use PhPHtmlParser\Dom\Node\TextNode;
class MangaInfoMangakakalotScrapper extends Scrapper
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

        $contents = $dom->find('.manga-info-text');

        $data = [];

        foreach($contents as $content) 
        {
            $info = $this->parse($content);
            $data[] = $info;
        }

        $data = array_shift($data);

        if($data == null) {
            return null;
        }
        //parse_url
        $contents = $dom->find('#noidungm');

        foreach($contents as $content) 
        {
            $description_container = $content->firstChild()->nextSibling()->nextSibling();
            $description = $description_container->innerHtml;
            $data['description'] = $description;
        }

        return $data;

    }
    
    private function parse($content) : array
    {
        $tile_container = $content->firstChild()->nextSibling()->firstChild()->nextSibling();
        $title = $tile_container->innerHtml;


        $author_container = $content->getChildren()[2]->nextSibling();
        $authors_node = $author_container->getChildren();
        $authors = [];
        foreach($authors_node as $author) {
            if($author instanceof TextNode) {
                continue;
            }

            $authors[] = $author->innerHtml;
        }

        $status_container = $content->getChildren()[4]->nextSibling();
        $status = $status_container->innerHtml;
        $status = trim(str_replace('Status :', '', $status));

        $last_update_container = $content->getChildren()[6]->nextSibling();
        $last_update = $last_update_container->innerHtml;
        $last_update = trim(preg_replace('/(Last updated :|PM|AM)/', '', $last_update));

        $category_container = $content->getChildren()[12]->nextSibling();
        $categories_node = $category_container->getChildren();
        $categories = [];
        foreach($categories_node as $category) {
            if($category instanceof TextNode) {
                continue;
            }

            $categories[] = $category->innerHtml;
        }
        
        return ['categories' => $categories, 'authors' => $authors, 'status' => $status, 'last_update' => $last_update];
    }
}