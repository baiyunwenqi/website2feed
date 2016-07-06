<?php

namespace Level14\Website2Feed\Model\Parsers;

/**
 * Loads the appropriate parsers for the feeds.
 */
class ParserLoader {
    /**
     * Loads the appropriate parser for the feed.
     * 
     * @param string $url
     * @return ParserInterface
     */
    public static function getParser($url) {
        if (strpos($url, 'https://www.fanfiction.net') === 0) {
            return FanfictionNetParser::createFromUrl($url);
        }
        else {
            throw new \Exception('Could not find parser for feed: ' . $url);
        }
        // TODO: If this list grows too big, replace with an appropriate autoloading mechanism
    }
}
