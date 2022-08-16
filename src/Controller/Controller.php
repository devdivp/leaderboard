<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;

abstract class Controller
{
    abstract public function execute(Request $request) : Response;

    protected function em()
    {
        return $GLOBALS['entityManager'];
    }

    protected function response($content, string $type, int $code): Response
    {
        $content = is_array($content) ? json_encode($content) : $content;

        return new Response($content, $type, $code);
    }

    protected function sanitize($input)
    {
        if (is_array($input)) {
            foreach ($input as $k => $v) {
                if (is_string($v)) {
                    $input[$k] = trim(htmlspecialchars($v, ENT_QUOTES));
                }
            }
        } else {
            $input = trim(htmlspecialchars($input, ENT_QUOTES));
        }

        return $input;
    }

    protected function typeInt(array $array): array
    {
        array_walk($array, function (&$value) {
            if (ctype_digit($value)) {
                $value = (int) $value;
            }
        });

        return $array;
    }
}
