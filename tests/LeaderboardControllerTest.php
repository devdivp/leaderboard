<?php

use PHPUnit\Framework\TestCase;

class LeaderboardControllerTest extends TestCase
{
    private $http;

    public function setUp(): void
    {
        $this->http = new GuzzleHttp\Client(['base_uri' => 'http://localhost:8000']);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }
}
