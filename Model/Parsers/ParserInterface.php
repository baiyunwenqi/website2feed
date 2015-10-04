<?php

namespace Level14\Website2Feed\Model\Parsers;

/**
 * All parsers must implement this for documentation purposes
 */
interface ParserInterface {
    /**
     * @return \Level14\Website2Feed\Model\Item[] The feed items
     */
    function parseItems();
}
