<?php

namespace App\Services\Parser;

interface ParserInterface
{
    public function pars(string $value, string $selector);
}