<?php
/* @var $this \Level14\FfnetRss\SimpleView */
/* @var $feeds Level14\FfnetRss\Model\Feed[] */

$this->enableLayout();
$this->setHtmlTitle('Feed list');

?>
<ul>
    <?php foreach($feeds as $feed) : ?>
    <?php $feedUrl = $feed->getFeedUrl(); ?>
    <li><a href='<?= htmlspecialchars($feedUrl, ENT_QUOTES)?>'>
        <?= htmlspecialchars($feed->getTitle(), ENT_QUOTES)?>
    </a></li>
    <?php endforeach; ?>
</ul>
