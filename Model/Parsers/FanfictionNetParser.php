<?php

namespace Level14\Website2Feed\Model\Parsers;

use Level14\Website2Feed\Model\Feed;

class FanfictionNetParser implements ParserInterface {
    private $text = null;
    private $url;
    private function __construct($url = '') {
        $this->url = $url;
    }
    
    /**
     * Creates a new parser from an URL
     * 
     * @param type $url
     * @return \Level14\Website2Feed\Model\Parsers\FanfictionNetParser
     */
    public static function createFromUrl($url) {
        return new FanfictionNetParser($url);
    }
    
    /**
     * Returns the raw contents of the url.
     * Loads the text lazily.
     * 
     * @return string
     */
    private function getText() {
        if ($this->text === null) {
            $this->text = file_get_contents($this->url);
        }
        return $this->text;
    }
    
    public function parseItems() {
        // Global??? ugh...
        \phpQuery::newDocument($this->getText());
        
        $linkPattern = preg_replace('/\/1\//', '/%d/', $this->url, 1);
        $chDescriptionPattern = sprintf('New chapter: <a href="%s">%%s</a>', $linkPattern);
        
        //$title = pq('#profile_top b:first')->html();
        //$author = pq('#profile_top a:first')->html();
        
        $items = [];
        $chapters = pq('select#chap_select:first > option');
        foreach ($chapters as $key => $opt) {
            $item = new \Level14\Website2Feed\Model\Item();
            
            $chTitle = pq($opt)->text();
            $chNumber = pq($opt)->attr('value');
            $chLink = sprintf($linkPattern, $chNumber);

            $item->setChapter($chTitle);
            $item->setDescription(sprintf($chDescriptionPattern, $chNumber, $chTitle));
            $item->setPublishedDate(time());
            $item->setTitle($chTitle);
            $item->setUrl($chLink);
            
            $items[] = $item;
        }
        return $items;
    }

    /**
     * @return Feed
     */
    function parseMetadata()
    {
        $feed = new Feed();

        \phpQuery::newDocument($this->getText());

        $feed->setUrl($this->url);
        $feed->setTitle(pq('b.xcontrast_txt')->text());
        $feed->setAuthor(pq('a.xcontrast_txt:nth-child(5)')->text());
        $feed->setUpdatedDate(0);

        return $feed;
    }
}
