<?php
/**
 * This file is part of Gear.
 *
 * @copyright 2015 Loïc Marchand
 * @license http://www.spdx.org/licenses/MIT MIT License
 */

namespace Gear\Network;

use \Gear\Util\Collection;

/**
 * Represent request send by user.
 */
class Request
{

    /**
     * Url requested.
     *
     * @var string $url
     */
    protected $url = '';

    /**
     * Base url.
     *
     * @var string $base
     */
    protected $base = '';

    /**
     * Request method : GET, POST, PUT, DELET, PATCH, HEAD, OPTIONS.
     *
     * @var string $method
     */
    protected $method = '';

    /**
     * Detect Ajax request.
     *
     * @var boolean $ajax
     */
    protected $ajax = false;

    /**
     * Request scheme.
     *
     * @var string $scheme
     */
    protected $scheme = '';

    /**
     * Request HTTP protocol.
     *
     * @var string $protocol
     */
    protected $protocol = '';

    /**
     * True if https, false otherwise.
     *
     * @var boolean $isSecure
     */
    protected $isSecure = false;

    /**
     * $_GET data.
     *
     * @var \Gear\Util\Collection $query
     */
    protected $query;

    /**
     * $_POST data.
     *
     * @var \Gear\Util\Collection $data
     */
    protected $data;

    /**
     * $_COOKIE data.
     *
     * @var \Gear\Util\Collection $cookie
     */
    protected $cookies;

    /**
     * $_FILES data.
     *
     * @var \Gear\Util\Collection $files
     */
    protected $files;

    /**
     * Default constructor.
     */
    public function __construct()
    {
        $this->base = dirname($this->getValue('SCRIPT_NAME', ''));
        $this->url = str_replace($this->base, '', $this->getValue('REQUEST_URI', '/'));
        if (strpos($this->url, '?') !== false) {
            $this->url = str_replace('?'.$this->getValue('QUERY_STRING', ''), '', $this->url);
        }
        if (!empty(trim($this->url, '/'))) {
            $this->url = '/' . trim($this->url, '/') . '/';
        }
        if (empty($this->url)) {
            $this->url = '/';
        }
        $this->method = $this->getValue('REQUEST_METHOD', 'GET');
        $this->ajax = (strtolower($this->getValue('HTTP_X_REQUESTED_WITH', '')) == 'xmlhttprequest');
        $this->scheme = $this->getValue('REQUEST_SCHEME', 'http');
        $this->protocol = $this->getValue('SERVER_PROTOCOL', 'HTTP/1.1');
        $this->isSecure = ($this->scheme == 'https');
        $this->query = new Collection($_GET);
        $this->data = new Collection($_POST);
        $this->cookies = new Collection($_COOKIE);
        $this->files = new Collection($_FILES);
    }

    /**
     * Magic method. Access to any property.
     *
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->{$name}) ? $this->{$name} : null;
    }

    /**
     * Get server variable.
     *
     * @param string $key Key.
     * @param mixed $default Default value if not find key.
     *
     * @return mixed Found value or default if not find $key.
     */
    protected function getValue($key, $default = null)
    {
        return isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
    }
}
