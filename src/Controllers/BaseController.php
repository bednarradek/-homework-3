<?php

declare(strict_types=1);

namespace App\Controllers;

use JsonSerializable;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

abstract class BaseController
{
    protected Processor $schemaProcessor;

    public function __construct()
    {
        $this->schemaProcessor = new Processor();
    }

    /**
     * @param ResponseInterface $response
     * @param array<string, mixed>|JsonSerializable $payload
     * @param int $statusCode
     * @return ResponseInterface
     */
    protected function sendJsonResponse(
        ResponseInterface $response,
        array|JsonSerializable $payload,
        int $statusCode = 200
    ): ResponseInterface {

        $json = json_encode($payload);

        if ($json) {
            $response
                ->getBody()
                ->write($json);
        }

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }

    /**
     * @throws HttpBadRequestException
     */
    protected function checkValidUuid(string $uuid, ServerRequestInterface $request): void
    {
        $schema = Expect::string()->pattern('^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$');

        try {
            $this->schemaProcessor->process($schema, $uuid);
        } catch (ValidationException $exception) {
            throw new HttpBadRequestException($request, $exception->getMessage());
        }
    }
}
