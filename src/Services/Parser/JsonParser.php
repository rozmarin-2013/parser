<?php

namespace App\Services\Parser;

class JsonParser implements ParserInterface
{
    public function pars(string $value, string $selector)
    {
        $result = json_decode($value, true);

        return $result[$selector];
    }
}