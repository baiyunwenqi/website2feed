<?php

namespace Level14\Website2Feed\Model;

use Level14\Website2Feed\Model\Base\Feed as BaseFeed;

/**
 * Skeleton subclass for representing a row from the 'w2f_feeds' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Feed extends BaseFeed
{
    /**
     * Generates the feed URL from the id.
     * Signature: <code>string $function(int $feedId)</code>
     * 
     * @var callable 
     */
    private static $generateFeedUrl = null;
    
    /**
     * Injects the feed URL generating function.
     * 
     * @param callable $function A function that generates the feed URL for the feed readers.
     *                           Required signature: <code>string $function(int $feedId)</code>
     */
    public static function setFeedUrlFunction(callable $function) {
        self::$generateFeedUrl = $function;
    }

    /**
     * Initializes the static variables. Do not call this method manually.
     */
    public static function init() {
        self::$generateFeedUrl = function($id) { return 'example.com/invalid-url-function'; };
    }
    
    /**
     * Returns the full feed URL for feed readers
     * 
     * @return string
     */
    public function getFeedUrl() {
        // PHP somehow doesn't accept self::$generateFeedUrl(...)
        $temp = self::$generateFeedUrl;
        return $temp($this->getId());
    }
}

Feed::init();
