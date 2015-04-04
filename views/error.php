<?php
/* @var $this \Level14\Website2Feed\SimpleView */
/* @var $errorMessage string */

$this->app->contentType('text/html');
$this->enableLayout();
$this->setHtmlTitle('Error');

?>
<p><?=$errorMessage?></p>
<p>If you have a question, need the source, or think my feed crawling bot misbehaved, <a href='https://github.com/hgabor'>contact me on github</a>.
