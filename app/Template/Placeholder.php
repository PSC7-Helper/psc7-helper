<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App\Template;

use psc7helper\App\Common\Escape;
use psc7helper\App\Config\Config;
use psc7helper\App\Config\Lang;
use psc7helper\App\Date\Date;
use psc7helper\App\Http\Scheme;
use psc7helper\App\Http\UrlParser;
use psc7helper\App\Session\Session;
use psc7helper\App\User\User;

class Placeholder
{
    /**
     * placeholder.
     *
     * @var array
     */
    protected $placeholder;

    /**
     * __construct.
     */
    public function __construct()
    {
        $this->setDefault()
             ->setLang()
             ->setPage();
    }

    /**
     * setLang.
     *
     * @return $this
     */
    private function setLang()
    {
        $lang = Lang::get('lang');
        $path = THEMES_PATH . DS . Config::get('theme');
        if (file_exists($path . DS . 'lang.json')) {
            $jsonFile = file_get_contents($path . DS . 'lang.json');
            $jsonDecode = json_decode($jsonFile, true);
            foreach ($jsonDecode[$lang] as $key => $value) {
                $this->placeholder[] = [$key => $value];
            }
            $globLang = $GLOBALS['LANG'];
            foreach ($globLang as $key => $value) {
                $this->placeholder[] = [$key => $value];
            }
        }

        return $this;
    }

    /**
     * setDefault.
     *
     * @return $this
     */
    private function setDefault()
    {
        $this->placeholder[] = ['meta_lang' => 'de'];
        $this->placeholder[] = ['root_path' => Scheme::get() . UrlParser::getRootUrl()];
        $this->placeholder[] = ['theme_path' => 'themes' . DS . Config::get('theme')];
        $uri = User::get('uri');
        if ('/' == substr($uri, 0, 1)) {
            $uri = substr($uri, 1);
        }
        $this->placeholder[] = ['html_class' => ''];
        $this->placeholder[] = ['meta_viewport' => 'width=device-width, initial-scale=1'];
        $this->placeholder[] = ['meta_title' => ''];
        $this->placeholder[] = ['meta_websitename' => Config::get('name')];
        $this->placeholder[] = ['meta_description' => ''];
        $this->placeholder[] = ['meta_canonical' => Scheme::get() . UrlParser::getCanonicalUrl()];
        $this->placeholder[] = ['meta_robots' => 'noindex, nofollow'];
        $this->placeholder[] = ['meta_shortcuticon' => ''];
        $this->placeholder[] = ['meta_appletouchicon' => ''];
        $this->placeholder[] = ['meta_css' => ''];
        $this->placeholder[] = ['head_js' => ''];
        $this->placeholder[] = ['body_class' => ''];
        $this->placeholder[] = ['date_d' => Date::getDate('d')];
        $this->placeholder[] = ['date_m' => Date::getDate('m')];
        $this->placeholder[] = ['date_Y' => Date::getDate('Y')];
        $this->placeholder[] = ['date_dmY' => Date::getDate('d.m.Y')];
        $this->placeholder[] = ['date_H' => Date::getDate('H')];
        $this->placeholder[] = ['date_i' => Date::getDate('i')];
        $this->placeholder[] = ['date_i' => Date::getDate('s')];
        $this->placeholder[] = ['date_His' => Date::getDate('H:i:s')];
        $this->placeholder[] = ['date_Hi' => Date::getDate('H:i')];
        $this->placeholder[] = ['formaction' => htmlspecialchars($_SERVER['REQUEST_URI'])];
        $this->placeholder[] = ['formkey' => Session::get('token')];
        $this->placeholder[] = ['body_js' => ''];
        $version = htmlspecialchars(file_get_contents(ROOT_PATH . DS . 'VERSION'));
        $this->placeholder[] = ['version' => $version];

        return $this;
    }

    /**
     * setPage.
     *
     * @return $this
     */
    private function setPage()
    {
        $themePath = 'themes' . DS . Config::get('theme');
        $urlPath = 'themes' . DS . Config::get('theme');
        $this->placeholder[] = ['meta_shortcuticon' => $themePath . DS . 'images' . DS . 'favicon.ico'];
        $this->placeholder[] = ['meta_appletouchicon' => $themePath . DS . 'images' . DS . 'apple-touch-icon.png'];
        $this->placeholder[] = ['meta_css' => $this->getCss($themePath, $urlPath)];
        $this->placeholder[] = ['head_js' => $this->getJs($themePath, $urlPath)];
        $this->placeholder[] = ['copyright' => Config::get('copyright')];
        $this->placeholder[] = ['copyright_year' => Config::get('copyrightYear')];
        $this->placeholder[] = ['CONTROLLERACTION' => 'not set'];
        $this->placeholder[] = ['body_js' => $this->getJsBody($themePath, $urlPath)];

        return $this;
    }

    /**
     * getCss.
     *
     * @param string $themePath
     * @param string $urlPath
     *
     * @return string
     */
    private function getCss($themePath, $urlPath)
    {
        $cssList = [];
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
     * getJs.
     *
     * @param string $themePath
     * @param string $urlPath
     *
     * @return string
     */
    private function getJs($themePath, $urlPath)
    {
        $jsList = [];
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
     * getJsBody.
     *
     * @param string $themePath
     * @param string $urlPath
     *
     * @return string
     */
    private function getJsBody($themePath, $urlPath)
    {
        $jsList = [];
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
     * set.
     *
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $key = Escape::key($key);
        $value = Escape::value($value, 0, true);
        $this->placeholder[$key] = $value;

        return $this;
    }

    /**
     * setMulti.
     *
     * @param array $placeholder
     *
     * @return $this
     */
    public function setMulti($placeholder)
    {
        foreach ($placeholder as $key => $value) {
            $key = Escape::key($key);
            $value = Escape::value($value, 0, true);
            $this->placeholder[$key] = $value;
        }

        return $this;
    }

    /**
     * get.
     *
     * @return array
     */
    public function get()
    {
        return $this->placeholder;
    }
}
