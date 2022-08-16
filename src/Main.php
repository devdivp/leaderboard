<?php

namespace App;

class Main
{
    protected $controllers;

    public function __construct(array $controllers)
    {
        foreach($controllers as $endpoint => $controller)
        {
            if(!is_a($controller, 'App\Controller\Controller'))
                throw new \Exception("Controller for $endpoint is the wrong type");
        }

        $this->controllers = $controllers;
    }

    public function handle($request)
    {
        $endpoint = $request->getEndpoint();

        if(!isset($this->controllers[$endpoint]))
        {
            http_response_code(404);
            echo "<h1>Endpoint '$endpoint' not found</h1>";
            die();
        }

        try {
            $response = $this->controllers[$endpoint]->execute($request);
        }
        catch(\Exception $e)
        {
            http_response_code(500);
            echo '<h1>Uncaught Exception: ' . $e->getMessage() . '</h1>';
            die();
        }

        http_response_code($response->getCode());
        header('Content-Type: ' . $response->getType());
        echo $response->getContent();
    }
}
