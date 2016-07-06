<?php
require 'vendor/autoload.php';

//
// Set up
//

$config = require 'config.local.php';

$app = new \Slim\Slim(array(
    'debug' => false,
    'view' => new Level14\Website2Feed\SimpleView,
    ));
$app->view()->setApp($app);
$app->view()->setTemplatesDirectory('./views');

require 'generated-conf/config.php';

// Custom error classes
class ApiKeyException extends Exception {}
class PageNotFoundException extends Exception {}

use Level14\Website2Feed\Model\FeedQuery;
use Level14\Website2Feed\Model\Base\ItemQuery;

// Inject the URL generating function
Level14\Website2Feed\Model\Feed::setFeedUrlFunction(function($id) use ($app, $config) {
    return $_SERVER['REQUEST_SCHEME']  . '://' . $_SERVER['HTTP_HOST']
            . $app->urlFor('feed', array('id' => $id))
            . '?apikey=' . $config['apikey'];
});

//
// Endpoints, hooks
// Refactor to controller classes if they become too large
//

// API key auth is required, check before every request
$app->hook('slim.before', function() use ($app, $config) {
    $apiKey = $app->request->get('apikey');
    if ($apiKey !== $config['apikey']) {
        throw new ApiKeyException;
    }
});

// Delegate "route not found" errors to common error handler
$app->notFound(function() use ($app) {
    throw new PageNotFoundException;
});

// Error handler
$app->error(function(\Exception $e) use ($app) {
    if ($e instanceof ApiKeyException) {
        $app->render('error.php', array('errorMessage' => 'Invalid API key'), 403);
    }
    elseif ($e instanceof PageNotFoundException) {
        $app->render('error.php', array('errorMessage' => 'Invalid URL'), 404);
    }
    else {
        $app->render('error.php', array('errorMessage' => 'An unexpected error occured.'), 500);
    }
});

// Main endpoint for the feed readers
$app->get('/feeds/:id', function($id) use ($app) {
    $feed = FeedQuery::create()->findPk($id);
    if ($feed == null) {
        throw new PageNotFoundException();
    }
    
    $items = ItemQuery::create()
            ->filterByFeed($feed)
            ->orderByPublishedDate(\Propel\Runtime\ActiveQuery\Criteria::DESC)
            ->limit(20)
            ->find();
    
    $app->render('atom.php', array('feed' => $feed, 'items' => $items));
})->name('feed');

$app->post('/feeds', function() use ($app) {
    $rootUrl = $app->urlFor('root') . '?apikey=' . $GLOBALS['config']['apikey'];

    $url = $app->request->post('url');
    print 'Submitted url ' . $url;
    if (empty($url)) {
        return;
    }

    $parser = \Level14\Website2Feed\Model\Parsers\ParserLoader::getParser($url);
    $feed = $parser->parseMetadata();

    $feed->save();

    $app->redirect($rootUrl);

})->name('newfeed');

// Main endpoint for users, list feeds
$app->get('/', function() use ($app) {
    $feeds = FeedQuery::create()->find();
    
    $app->render('listFeeds.php', array('feeds' => $feeds));
})->name('root');

$app->run();

