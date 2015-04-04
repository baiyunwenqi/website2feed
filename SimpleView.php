<?php

namespace Level14\FfnetRss;

/**
 * Extends the Slim view with a few convenience methods and a simple, one level layout.
 * 
 * @internal The Slim app is injected with the setApp() method so that views can avoid using Slim::getInstance().
 */
class SimpleView extends \Slim\View {
    /**
     * @var Slim\Slim
     */
    protected $app;
    
    /**
     * Sets the current Slim app.
     * 
     * @param \Slim\Slim $app
     */
    public function setApp(\Slim\Slim $app) {
        $this->app = $app;
    }
    
    /**
     * The name of the layout file for the currently rendered view.
     * Null, if no layout is needed.
     * 
     * @var string
     */
    private $layoutFile = null;
    
    /**
     * Adds a parent template (layout) to the view;
     * 
     * @param string $file
     */
    public function enableLayout($file = 'layout.php') {
        $this->layoutFile = $file;
    }
    
    /**
     * Title of the current HTML page. Only used if layout is enabled.
     * 
     * @var string
     */
    private $htmlTitle = '';
    
    /**
     * Sets the title for HTML pages. Only used if layout is enabled.
     * 
     * @param type $title
     */
    public function setHtmlTitle($title) {
        $this->htmlTitle = $title;
    }
    
    protected function render($template, $data = null) {
        $contents = parent::render($template, $data);
        
        if (is_null($this->layoutFile)) {
            return $contents;
        }
        else {
            return parent::render('layout.php', array('contents' => $contents, 'title' => $this->htmlTitle));
        }
    }
}
