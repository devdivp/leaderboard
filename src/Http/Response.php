<?php

namespace App\Http;

class Response
{
    protected $content, $type, $code;

    public function __construct(string $content, string $type = 'application/json', int $code = 200)
    {
        $this->content = $content;
        $this->type = $type;
        $this->code = $code;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getCode()
    {
        return $this->code;
    }
}