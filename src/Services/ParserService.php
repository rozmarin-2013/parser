<?php

namespace App\Services;

use App\Controller\Request\DataParsingRequest;
use App\Services\Parser\ParserInterface;

class ParserService
{
    public function __construct(
        private DataParsingRequest $dataParsingRequest,
        private array              $allResources = []
    ) {
        $this->allResources = $this->dataParsingRequest->getAllSources();
    }

    public function pars(ParserInterface $parser, string $selector, int $key): array
    {
        return $parser->pars($this->allResources[$key], $selector);
    }
}