<?php

namespace App\Controller;


use App\Services\Parser\JsonParser;
use App\Services\Parser\MarkupParser;
use App\Services\ParserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParserController extends AbstractController
{
    const PARSER_TYPES = [
        'svg' => [
            'key' => 2,
            'selector' => 'text'
        ],
        'html' => [
            'key' => 1,
            'selector' => '.ticket'
        ],
        'json' => [
            'key' => 3,
            'selector' => 'tickets'
        ]
    ];

    public function __construct(
        private ParserService $parserService,
        private MarkupParser  $markupParser,
        private JsonParser    $jsonParser
    )
    {

    }

    #[Route(path: '/api/data/{type}', name: 'api_data_svg', methods: ['GET'])]
    public function data(string $type): JsonResponse
    {
        try {
            return $this->json($this->getServiceByType($type));
        } catch (\Exception $e) {
            return $this->json(
                $e->getMessage(),
                ($e->getCode()) ?: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function getServiceByType(string $type)
    {
        return match ($type) {
            'svg', 'html' => $this->parserService->pars(
                $this->markupParser,
                self::PARSER_TYPES[$type]['selector'],
                self::PARSER_TYPES[$type]['key']
            ),
            'json' => $this->parserService->pars(
                $this->jsonParser,
                self::PARSER_TYPES[$type]['selector'],
                self::PARSER_TYPES[$type]['key']
            ),
            default => throw new \Exception('Invalid parser type', 400)
        };
    }
}