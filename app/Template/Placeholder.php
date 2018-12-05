<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Template;

use psc7helper\App\Http\Scheme;
use psc7helper\App\Http\UrlParser;
use psc7helper\App\Date\Date;
use psc7helper\App\Config\Config;
use psc7helper\App\Config\Lang;
use psc7helper\App\User\User;
use psc7helper\App\Session\Session;
use psc7helper\App\Common\Escape;
use Detection\MobileDetect;

class Placeholder {

    /**
     * placeholder
     * @var array
     */
    protected $placeholder;

    /**
     * __construct
     */
    public function __construct() {
        $this->setDefault()
             ->setLang()
             ->setPage();
    }

    /**
     * setLang
     * @return $this
     */
    private function setLang() {
        $lang = Lang::get('lang');
        $path = THEMES_PATH . DS . Config::get('theme');
        if (file_exists($path . DS . 'lang.json')) {
            $jsonFile = file_get_contents($path . DS . 'lang.json');
            $jsonDecode = json_decode($jsonFile, true);
            foreach ($jsonDecode[$lang] as $key => $value) {
                $this->placeholder[] = array($key => $value);
            }
            $globLang = $GLOBALS['LANG'];
            foreach ($globLang as $key => $value) {
                $this->placeholder[] = array($key => $value);
            }
        }
        return $this;
    }

    /**
     * setDefault
     * @return $this
     */
    private function setDefault() {
        $this->placeholder[] = array('meta_lang' => 'de');
        $this->placeholder[] = array('root_path' => Scheme::get() . UrlParser::getRootUrl());
        $this->placeholder[] = array('theme_path' => 'themes' . DS . Config::get('theme'));
        $uri = User::get('uri');
        if (substr($uri, 0, 1) == '/') {
            $uri = substr($uri, 1);
        }
        $this->placeholder[] = array('html_class' => '');
        $this->placeholder[] = array('meta_viewport' => 'width=device-width, initial-scale=1');
        $this->placeholder[] = array('meta_title' => '');
        $this->placeholder[] = array('meta_websitename' => Config::get('name'));
        $this->placeholder[] = array('meta_description' => '');
        $this->placeholder[] = array('meta_canonical' => Scheme::get() . UrlParser::getCanonicalUrl());
        $this->placeholder[] = array('meta_robots' => 'noindex, nofollow');
        $this->placeholder[] = array('meta_shortcuticon' => '');
        $this->placeholder[] = array('meta_appletouchicon' => '');
        $this->placeholder[] = array('meta_css' => '');
        $this->placeholder[] = array('head_js' => '');
        $this->placeholder[] = array('body_class' => '');
        $this->placeholder[] = array('date_d' => Date::getDate('d'));
        $this->placeholder[] = array('date_m' => Date::getDate('m'));
        $this->placeholder[] = array('date_Y' => Date::getDate('Y'));
        $this->placeholder[] = array('date_dmY' => Date::getDate('d.m.Y'));
        $this->placeholder[] = array('date_H' => Date::getDate('H'));
        $this->placeholder[] = array('date_i' => Date::getDate('i'));
        $this->placeholder[] = array('date_i' => Date::getDate('s'));
        $this->placeholder[] = array('date_His' => Date::getDate('H:i:s'));
        $this->placeholder[] = array('date_Hi' => Date::getDate('H:i'));
        $this->placeholder[] = array('mobile_detect' => $this->mobileDetect());
        $this->placeholder[] = array('formaction' => htmlspecialchars($_SERVER['REQUEST_URI']));
        $this->placeholder[] = array('formkey' => Session::get('token'));
        $this->placeholder[] = array('body_js' => '');
        $version = htmlspecialchars(file_get_contents(ROOT_PATH . DS . 'VERSION'));
        $this->placeholder[] = array('version' => $version);
        return $this;
    }

    /**
     * setPage
     * @return $this
     */
    private function setPage() {
        $themePath = 'themes' . DS . Config::get('theme');
        $urlPath = 'themes' . DS . Config::get('theme');
        $this->placeholder[] = array('meta_shortcuticon' => $themePath . DS . 'images' . DS . 'favicon.ico');
        $this->placeholder[] = array('meta_appletouchicon' => $themePath . DS . 'images' . DS . 'apple-touch-icon.png');
        $this->placeholder[] = array('meta_css' => $this->getCss($themePath, $urlPath));
        $this->placeholder[] = array('head_js' => $this->getJs($themePath, $urlPath));
        $this->placeholder[] = array('copyright' => Config::get('copyright'));
        $this->placeholder[] = array('copyright_year' => Config::get('copyrightYear'));
        $this->placeholder[] = array('CONTROLLERACTION' => 'not set');
        $this->placeholder[] = array('body_js' => $this->getJsBody($themePath, $urlPath));
        return $this;
    }

    /**
     * getCss
     * @param string $themePath
     * @param string $urlPath
     * @return string
     */
    private function getCss($themePath, $urlPath) {
        $cssList = array();
        $files = glob($themePath . DS . 'css/*.css', GLOB_NOSORT);
        foreach ($files as $file) {
            $explode = explode('/', $file);
            $end = end($explode);
            $cssList[] = '<link rel="stylesheet" href="' . $urlPath . '/css/' . $end . '">';
        }
        $css = implode(PHP_EOL . "\t\t", $cssList);
        return $css;
    }

    /**
     * getJs
     * @param string $themePath
     * @param string $urlPath
     * @return string
     */
    private function getJs($themePath, $urlPath) {
        $jsList = array();
        $files = glob($themePath . DS . 'js/*_head.js', GLOB_NOSORT);
        foreach ($files as $file) {
            $explode = explode('/', $file);
            $end = end($explode);
            $jsList[] = '<script src="' . $urlPath . '/js/' . $end . '"></script>';
        }
        $js = implode(PHP_EOL . "\t\t", $jsList);
        return $js;
    }

    /**
     * getJsBody
     * @param string $themePath
     * @param string $urlPath
     * @return string
     */
    private function getJsBody($themePath, $urlPath) {
        $jsList = array();
        $files = glob($themePath . DS . 'js/*_body.js', GLOB_NOSORT);
        foreach ($files as $file) {
            $explode = explode('/', $file);
            $end = end($explode);
            $jsList[] = '<script src="' . $urlPath . '/js/' . $end . '"></script>';
        }
        $js = implode(PHP_EOL . "\t\t", $jsList);
        return $js;
    }

    /**
     * mobileDetect
     * @return $this
     */
    private function mobileDetect() {
        $os = 'unknown';
        $detect = new MobileDetect();
        $detect->setUserAgent(User::get('agent'));
        if ($detect->isMobile()) {
            $os = 'mobile';
        }
        if ($detect->isTablet()){
            $os = 'tablet';
        }
        if (!$detect->isMobile() && !$detect->isTablet()){
            $os = 'desktop';
        }
        return $os;
    }

    /**
     * set
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function set($key, $value) {
        $key = Escape::key($key);
        $value = Escape::value($value, 0, true);
        $this->placeholder[$key] = $value;
        return $this;
    }

    /**
     * setMulti
     * @param array $placeholder
     * @return $this
     */
    public function setMulti($placeholder) {
        foreach ($placeholder as $key => $value) {
            $key = Escape::key($key);
            $value = Escape::value($value, 0, true);
            $this->placeholder[$key] = $value;
        }
        return $this;
    }

    /**
     * get
     * @return array
     */
    public function get() {
        return $this->placeholder;
    }

}
