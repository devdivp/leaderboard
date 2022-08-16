<?php

namespace App\Http;

class Request
{
    private $_GET, $_POST, $_SERVER, $input;

    private $endpoint, $path;

    public function __construct(
        array $GET, array $POST, array $SERVER, string $input)
    {
        $this->_GET = $GET;
        $this->_POST = $POST;
        $this->_SERVER = $SERVER;
        $this->input = $input;
    }

    public function getEndpoint()
    {
        $this->parseURI();
        return $this->endpoint;
    }

    protected function parseURI()
    {
        if (is_null($this->endpoint)) {
            $uri = $this->getURI();
            $path = strstr($uri, '?', true) ?: $uri;
            $endpoint = strstr($uri, '?', true) ?: $uri;
            $endpoint = trim($endpoint, '/');

            $this->endpoint = $endpoint;
            $this->path = $path;
        }
    }

    public function getURI()
    {
        return $this->_SERVER['REQUEST_URI'];
    }

    public function getPath()
    {
        $this->parseURI();
        return $this->path;
    }

    public function getUrlParam($key)
    {
        return isset ($this->_GET[$key])
            ? $this->_GET[$key]
            : NULL;
    }

    public function getPostParam($key)
    {
        return isset ($this->_POST[$key])
            ? $this->_POST[$key]
            : NULL;
    }

    public function getUrlParams()
    {
        return $this->_GET;
    }

    public function getPostParams()
    {
        return $this->_POST;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getMethod()
    {
        return $this->_SERVER['REQUEST_METHOD'];
    }
}
