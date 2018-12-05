<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\User;

use psc7helper\App\User\Environment;
use psc7helper\App\Session\Session;

class User {

    /**
     * enrironment
     * @var object 
     */
    private $enrironment;

    /**
     * ip
     * @var string
     */
    private $ip;

    /**
     * ipv6
     * @var string
     */
    private $ipv6;

    /**
     * proxyIp
     * @var string 
     */
    private $proxyIp;

    /**
     * host
     * @var string
     */
    private $host;

    /**
     * agent
     * @var string 
     */
    private $agent;

    /**
     * referer
     * @var string 
     */
    private $referer;

    /**
     * uri
     * @var string 
     */
    private $uri;

    /**
     * identifierClass
     * @var object 
     */
    private $identifierClass;

    /**
     * identifier
     * @var string
     */
    private $identifier;

    /**
     * crsfToken
     * @var object 
     */
    private $crsfToken;

    /**
     * token
     * @var string 
     */
    private $token;

    /**
     * userId
     * @var integer 
     */
    private $userId;

    /**
     * instance
     * @var object
     */
    private static $instance;

    /**
     * __construct
     */
    private function __construct() {
        $this->enrironment = new Environment();
        $this->setIp()
             ->setHost()
             ->setAgent()
             ->setReferer()
             ->setUri();
        $this->identifierClass = new Identifier();
        $this->setIdentifier();
        $this->crsfToken = new CRSFToken();
        $this->setToken();
        $this->setUserId();
    }

    /**
     * __clone
     */
    final private function __clone() {
        
    }

    /**
     * getInstance
     * @return self
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * init
     * @return self
     */
    public static function init() {
        return self::getInstance();
    }

    /**
     * setIp
     * @return $this
     */
    private function setIp() {
        $this->ip = $this->enrironment->getIp();
        $this->ipv6 = $this->enrironment->getIpv6();
        $this->proxyIp = $this->enrironment->getProxyIp();
        return $this;
    }

    /**
     * setHost
     * @return $this
     */
    private function setHost() {
        $this->host = $this->enrironment->getHost();
        return $this;
    }

    /**
     * setAgent
     * @return $this
     */
    private function setAgent() {
        $this->agent = $this->enrironment->getAgent();
        return $this;
    }

    /**
     * setReferer
     * @return $this
     */
    private function setReferer() {
        $this->referer = $this->enrironment->getReferer();
        return $this;
    }

    /**
     * setUri
     * @return $this
     */
    private function setUri() {
        $this->uri = $this->enrironment->getUri(false);
        return $this;
    }

    /**
     * setIdentifier
     * @return $this
     */
    private function setIdentifier() {
        if (!Session::get('identifier')) {
            $this->identifier = $this->identifierClass->generate(false)->getIdentifier();
            Session::set('identifier', $this->identifier);
        }
        $this->identifier = Session::get('identifier');
        return $this;
    }

    /**
     * setToken
     * @return $this
     */
    private function setToken() {
        if (!Session::get('token')) {
            $this->token = $this->crsfToken->generate(true)->getToken();
            Session::set('token', $this->token);
        }
        $this->token = Session::get('token');
        return $this;
    }

    /**
     * setUserId
     * @return $this
     */
    private function setUserId() {
        $userid = false;
        if (Session::get('userid')) {
            $userid = Session::get('userid');
        } else {
            $this->rights = false;
        }
        $this->userId = $userid;
        return $this;
    }

    /**
     * get
     * @param string $key
     * @return mixed
     */
    public static function get($key) {
        $return = false;
        $user = self::getInstance();
        $key = strtolower($key);
        switch ($key) {
            case 'ip':
                $return = $user->ip;
                break;
            case 'ipv6':
                $return = $user->ipv6;
                break;
            case 'proxyip':
                $return = $user->proxyIp;
                break;
            case 'host':
                $return = $user->host;
                break;
            case 'agent':
                $return = $user->agent;
                break;
            case 'referer':
                $return = $user->referer;
                break;
            case 'uri':
                $return = $user->uri;
                break;
            case 'identifier':
                $return = $user->identifier;
                break;
            case 'token':
                $return = $user->token;
                break;
            case 'userid':
                $return = $user->userId;
                break;
            default:
                $return = '';
        }
        return $return;
    }

}
