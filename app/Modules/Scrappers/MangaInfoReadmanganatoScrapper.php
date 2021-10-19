<?php
namespace App\Modules\Scrappers;

use PhPHtmlParser\Dom\Node\TextNode;
class MangaInfoReadmanganatoScrapper extends Scrapper
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

        $contents = $dom->find('.table-value');

        $data = [];
        $columns = array('','authors','status','categories');

        foreach($contents as $index => $content) 
        {

            if($index == 0) {
                continue;
            }

            $row = $index % 2 == 0 ? $content->firstChild()->innerHtml : $content->getChildren();
            
            $data[$columns[$index]] = $row;
        }

        $contents = $dom->find('.stre-value');

        $columns = array('last_update');
        
        foreach($contents as $index => $content) 
        {
            if($index != 0) {
                continue;
            }
            
            $container = $content->firstChild();
            $row = $container->innerHtml;
            
            $data[$columns[$index]] = $row;
        }

        $data = $this->parse($data);

        $contents = $dom->find('.panel-story-info-description');
        foreach($contents as $content) 
        {
            $description_container = $content->firstChild()->nextSibling()->nextSibling();
            $description = $description_container->innerHtml;
            $data['description'] = strip_tags($description);
        }


        return $data;

    }
    
    private function parse($data) : array
    {   
        $authors_node = $data['authors'];
        $authors = [];
        foreach($authors_node as $author) {
            if($author instanceof TextNode) {
                continue;
            }

            $authors[] = $author->innerHtml;
        }

        $status = $data['status'];

        $last_update = $data['last_update'];
        $last_update = trim(preg_replace('/(PM|AM)/', '', $last_update));
        $last_update_date = explode('-', $last_update);
        $last_update_date[0] = str_replace(',', ' ', trim($last_update_date[0]));
        $last_update_date[0] = str_replace(' ', '-', $last_update_date[0]);
        $last_update = $last_update_date[0] . " " . trim($last_update_date[1]);

        $categories_node = $data['categories'];
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