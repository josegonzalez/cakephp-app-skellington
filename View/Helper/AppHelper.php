<?php
/**
 * Application Helper class
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app
 */

App::import('Vendor', 'UrlCache.url_cache_app_helper');
class AppHelper extends UrlCacheAppHelper {
    public $view = null;

    public function h2($contents, $alternate = null) {
        if ((empty($contents) || $contents == '' || $contents == ' ') && isset($alternate)) $contents = $alternate;

        $this->for_layout($contents . ' |', 'title');
        $this->for_layout($contents, 'h2');
    }

    public function for_layout($content, $name) {
        ob_start();
        if (!$this->view) {
            $this->view = ClassRegistry::getObject('view');
        }
        echo $content;
        $this->view->set($name . '_for_layout', ob_get_clean());
    }

}