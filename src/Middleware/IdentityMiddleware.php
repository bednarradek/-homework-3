<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Managers\IdentityManager;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface;

class IdentityMiddleware
{
    public function __construct(protected IdentityManager $identityManager)
    {
    }

    public function __invoke(ServerRequestInterface $request, RequestHandler $handler): ResponseInterface
    {
        //TODO implement detection customer from request
        $this->identityManager->setupCart('ce3f51d4-80ff-4bf1-b7e5-6172249be169');
        return $handler->handle($request);
    }
}
