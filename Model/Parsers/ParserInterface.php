<?php

namespace Level14\Website2Feed\Model\Parsers;

use Level14\Website2Feed\Model\Feed;

/**
 * All parsers must implement this for documentation purposes
 */
interface ParserInterface {
    /**
     * @return \Level14\Website2Feed\Model\Item[] The feed items
     */
    function parseItems();

    /**
     * @return Feed
     */
    function parseMetadata();
}
