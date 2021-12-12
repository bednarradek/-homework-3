<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Managers\IdentityManager;
use Nette\Utils\DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpInternalServerErrorException;

class CartMiddleware
{
    public function __construct(private IdentityManager $identityManager)
    {
    }

    /**
     * @throws HttpInternalServerErrorException
     */
    public function __invoke(ServerRequestInterface $request, RequestHandler $handler): ResponseInterface
    {
        $cart = $this->identityManager->getCart();
        if (!$cart) {
            throw new HttpInternalServerErrorException($request, 'Cart is not initialized');
        }
        $cart->setUpdated(new DateTime());
        $cart->save();
        return $handler->handle($request);
    }
}
