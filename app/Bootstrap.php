<?php

/**
 * This file is part of the psc7-helper/psc7-helper.
 *
 * @see https://github.com/PSC7-Helper/psc7-helper
 *
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 */

namespace psc7helper\App;

use psc7helper\App\Config\Config;
use psc7helper\App\Routing\Route;
use psc7helper\App\Session\Session;
use psc7helper\App\Template\Placeholder;
use psc7helper\App\Template\TemplateSystem;
use psc7helper\App\User\User;

class Bootstrap implements Bootstrap_Interface
{
    /**
     * CONTROLLERNAMESPACE.
     */
    const CONTROLLERNAMESPACE = '\\psc7helper\\Controller\\';

    /**
     * requests.
     *
     * @var array
     */
    protected $requests;

    /**
     * controller.
     *
     * @var string
     */
    protected $controller;

    /**
     * action.
     *
     * @var string
     */
    protected $action = false;

    /**
     * param.
     *
     * @var string
     */
    protected $param;

    /**
     * security.
     *
     * @var bool
     */
    protected $security = false;

    /**
     * login.
     *
     * @var bool
     */
    protected $login = false;

    /**
     * install.
     *
     * @var bool
     */
    protected $install = false;

    /**
     * config.
     *
     * @var bool
     */
    protected $config = false;

    /**
     * theme.
     *
     * @var string
     */
    protected $theme = false;

    /**
     * __construct.
     *
     * @param mixed $requests
     */
    public function __construct($requests)
    {
        $this->requests = $requests;
        System::initialize();
        $this->setRoute()
             ->securityCheck()
             ->loginCheck()
             ->installCheck()
             ->configCheck()
             ->checkRoute()
             ->setTheme();
    }

    /**
     * setRoute.
     *
     * @return $this
     */
    private function setRoute()
    {
        $requests = $this->requests;
        $route = new Route($requests);
        $this->controller = $route->getController();
        $this->action = $route->getAction();
        $this->param = $route->getParam();
        $this->param;

        return $this;
    }

    /**
     * securityCheck.
     *
     * @return $this
     */
    private function securityCheck()
    {
        if (! User::get('identifier') || ! User::get('token')) {
            $this->security = false;

            return $this;
        }
        if (! Session::get('init') || ! Session::get('identifier') || ! Session::get('token')) {
            $this->security = false;
            Session::destroy();

            return $this;
        }
        if (User::get('identifier') != Session::get('identifier')) {
            $this->security = false;
            Session::destroy();

            return $this;
        }
        if (User::get('token') != Session::get('token')) {
            $this->security = false;
            Session::destroy();

            return $this;
        }
        if (User::get('userid') != Session::get('userid')) {
            $this->security = false;
            Session::destroy();

            return $this;
        }
        $this->security = true;

        return $this;
    }

    /**
     * loginCheck.
     *
     * @return $this
     */
    private function loginCheck()
    {
        if (! $this->security) {
            $this->login = false;

            return $this;
        }
        if (! User::get('userid')) {
            $this->login = false;

            return $this;
        }
        if (! Session::get('userid')) {
            $this->login = false;

            return $this;
        }
        if (User::get('userid') != Session::get('userid')) {
            $this->login = false;

            return $this;
        }
        if (! Session::validateLogin()) {
            $this->login = false;

            return $this;
        }
        $this->login = true;

        return $this;
    }

    /**
     * installCheck.
     *
     * @return $this
     */
    private function installCheck()
    {
        $file = ROOT_PATH . DS . 'var' . DS . 'install.lock';
        if (! file_exists($file)) {
            $this->install = false;

            return $this;
        }
        $this->install = true;

        return $this;
    }

    /**
     * configCheck.
     *
     * @return $this
     */
    private function configCheck()
    {
        $file = ROOT_PATH . DS . 'config.php';
        if (file_exists($file)) {
            $this->config = true;
        }

        return $this;
    }

    /**
     * checkRoute.
     *
     * @return $this
     */
    private function checkRoute()
    {
        if (! $this->login) {
            $this->controller = 'login';
            $this->action = 'index';
        }
        if (! $this->install || ! $this->config) {
            $this->controller = 'install';
            $this->action = 'index';
        }

        return $this;
    }

    /**
     * setTheme.
     *
     * @return $this
     */
    private function setTheme()
    {
        $this->theme = THEMES_PATH . DS . Config::get('theme');

        return $this;
    }

    /**
     * displayError.
     *
     * @param int $code
     *
     * @return bool
     */
    private function displayError($code)
    {
        $ph = new Placeholder();
        $placeholder = $ph->get();
        $templateSystem = new TemplateSystem();
        $template = ROOT_PATH . DS . 'themes' . DS . Config::get('theme') . DS . 'template' . DS . 'error' . $code . '.phtml';
        $templateSystem->readTemplate($template)->setPlaceholder($placeholder);
        $errorTemplate = $templateSystem->renderTemplate();
        exit($errorTemplate);

        return true;
    }

    /**
     * run.
     */
    public function run()
    {
        $requests = $this->requests;
        $controller = ROOT_PATH . DS . 'controllers' . DS . ucfirst($this->controller) . DS . 'Controller.php';
        $templatePath = 'controllers' . DS . ucfirst($this->controller) . DS . 'template';
        if (! file_exists($controller)) {
            $this->displayError(404);

            return false;
        }
        $controllerName = $this->controller;
        $action = $this->action;
        $param = $this->param;
        $login = $this->login;
        $theme = $this->theme;
        $class = self::CONTROLLERNAMESPACE . ucfirst($this->controller) . '\\Controller';
        $reflectionClass = new \ReflectionClass($class);
        $args = [$requests, $templatePath, $controllerName, $action, $param, $login, $theme];
        $instance = $reflectionClass->newInstanceArgs($args);

        return $instance;
    }
}
