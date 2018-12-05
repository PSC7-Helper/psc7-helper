<?php

/**
 * This file is part of the psc7-helper/psc7-helper
 * 
 * @link https://github.com/PSC7-Helper/psc7-helper
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * 
 */

namespace psc7helper\App\User;

use psc7helper\App\Common\Escape;

class Environment {

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
     * browser
     * @var string 
     */
    private $browser;

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
     * isLocal
     * @var bool 
     */
    private $isLocal;

    /**
     * __construct
     */
    public function __construct() {
        $this->setIp()
             ->setHost()
             ->setAgent()
             ->setReferer()
             ->setUri()
             ->setIsLocal();
    }

    /**
     * setIp
     * @return $this
     */
    private function setIp() {
        $ip = false;
        $ipv6 = false;
        $proxyIp = false;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && is_string($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $proxyIp = Escape::input('HTTP_X_FORWARDED_FOR', 'SERVER', 'ip');
        }
        if (isset($_SERVER['REMOTE_ADDR']) && is_string($_SERVER['REMOTE_ADDR'])) {
            $ip = Escape::input('REMOTE_ADDR', 'SERVER', 'ip');
        }
        if ($proxyIp) {
            $tmp = $ip;
            $ip = $proxyIp;
            $proxyIp = $tmp;
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ipv6 = $ip;
        }
        $this->ip = $ip;
        $this->ipv6 = $ipv6;
        $this->proxyIp = $proxyIp;
        return $this;
    }

    /**
     * setHost
     * @return $this
     */
    private function setHost() {
        $host = false;
        if (isset($_SERVER['REMOTE_ADDR']) && is_string($_SERVER['REMOTE_ADDR'])) {
            $host = Escape::input('REMOTE_ADDR', 'SERVER', 'full_special_chars');
            $host = gethostbyaddr($host);
        }
        $this->host = $host;
        return $this;
    }

    /**
     * setAgent
     * @return $this
     */
    private function setAgent() {
        $agent = false;
        if (isset($_SERVER['HTTP_USER_AGENT']) && is_string($_SERVER['HTTP_USER_AGENT'])) {
            $agent = Escape::input('HTTP_USER_AGENT', 'SERVER', 'full_special_chars');
        }
        $this->agent = $agent;
        return $this;
    }

    /**
     * setReferer
     * @return $this
     */
    private function setReferer() {
        $referer = false;
        if (isset($_SERVER['HTTP_REFERER']) && is_string($_SERVER['HTTP_REFERER'])) {
            $referer = Escape::input('HTTP_REFERER', 'SERVER', 'full_special_chars');
        }
        $this->referer = $referer;
        return $this;
    }

    /**
     * setUri
     * @param bool $raw
     * @return $this
     */
    private function setUri($raw = false) {
        $uri = false;
        if (isset($_SERVER['REQUEST_URI']) && is_string($_SERVER['REQUEST_URI'])) {
            if ($raw) {
                $uri = rawurlencode(Escape::input('REQUEST_URI', 'SERVER', 'url'));
            } else {
                $uri = Escape::input('REQUEST_URI', 'SERVER', 'url');
            }
        }
        $this->uri = $uri;
        return $this;
    }

    /**
     * setIsLocal
     * @return $this
     */
    private function setIsLocal() {
        $local = false;
        $ipList = array(
            '127.0.0.1', '::1'
        );
        if(in_array(Escape::input('REMOTE_ADDR', 'SERVER', 'string'), $ipList)){
            $local = true;
        }
        $this->isLocal = $local;
        return $this;
    }

    /**
     * getIp
     * @return string
     */
    public function getIp() {
        return (string) $this->ip;
    }

    /**
     * getIpv6
     * @return string
     */
    public function getIpv6() {
        return (string) $this->ipv6;
    }

    /**
     * getProxyIp
     * @return string
     */
    public function getProxyIp() {
        return (string) $this->proxyIp;
    }

    /**
     * getHost
     * @return string
     */
    public function getHost() {
        return (string) $this->host;
    }

    /**
     * getAgent
     * @return string
     */
    public function getAgent() {
        return (string) $this->agent;
    }

    /**
     * getReferer
     * @return string
     */
    public function getReferer() {
        return (string) $this->referer;
    }

    /**
     * getUri
     * @return string
     */
    public function getUri() {
        return (string) $this->uri;
    }
    
    public function getIsLocal() {
        return (bool) $this->isLocal;
    }

}
