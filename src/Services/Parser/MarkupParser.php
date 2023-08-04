<?php

namespace App\Services\Parser;

use Symfony\Component\DomCrawler\Crawler;

class MarkupParser implements ParserInterface
{
    public function pars(string $value, string $selector)
    {
        $html = new Crawler($value);
        $result = ($html->filter($selector)->each(function ($node, $i) {
            return $node->text();
        }));

        return $this->resultToArray($result);
    }

    protected function resultToArray(array $values)
    {
        $result = [];

        foreach ($values as $value) {
            $tickets = explode('|', $value);

            $result[] = [
                'sector' => explode(':', $tickets[0])[1],
                'row' => explode(':', $tickets[1])[1],
                'seat' => explode(':', $tickets[2])[1],
                'price' => explode(':', $tickets[3])[1]
            ];
        }

        return $result;
    }
}