<?php
namespace App\Modules\Scrappers;

use PHPHtmlParser\Dom;
use GuzzleHttp\Client;

class Scrapper 
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $dom;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setUrl(string $url) 
    {
        $this->url = $url;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    public function setDom(Dom $dom)
    {
        $this->dom = $dom;
    }

    public function getDom() : ?Dom
    {
        return $this->dom;
    }

    protected function createDomFromString(string $string) : Dom
    {
        $dom = new Dom;
        $dom->loadStr($string);
        return $dom;
    }
}