<?php
/* @var $this \Level14\Website2Feed\SimpleView */
/* @var $feeds Level14\Website2Feed\Model\Feed[] */

$this->enableLayout();
$this->setHtmlTitle('Feed list');

?>

<p>Add feed:</p>
<form method="post" action="<?= $this->urlWithApikeyFor('newfeed') ?>">
    <label>URL: <input type="text" name="url"></label>
    <input type="submit" value="Add">
</form>

<ul>
    <?php foreach($feeds as $feed) : ?>
    <?php $feedUrl = $feed->getFeedUrl(); ?>
    <li><a href='<?= htmlspecialchars($feedUrl, ENT_QUOTES)?>'>
        <?= htmlspecialchars($feed->getTitle(), ENT_QUOTES)?>
    </a></li>
    <?php endforeach; ?>
</ul>
