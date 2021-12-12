<?php

declare(strict_types=1);

namespace App\Routes;

use App\Controllers\CartsController;
use App\Controllers\ProductsController;
use App\Middleware\CartMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class RoutesBuilder
{
    public function __construct(
        private ProductsController $productsController,
        private CartsController $cartsController,
        private CartMiddleware $cartMiddleware,
    ) {
    }

    public function build(App $app): void
    {
        $app->group('/products', function (RouteCollectorProxy $group) {
            $group->get('/', [$this->productsController, 'get']);
            $group->get('/{id}', [$this->productsController, 'getOne']);
            $group->delete('/{id}', [$this->productsController, 'delete']);
            $group->post('/', [$this->productsController, 'post']);
            $group->put('/{id}', [$this->productsController, 'put']);
        });

        $app->group('/cart', function (RouteCollectorProxy $group) {
            $group->get('/', [$this->cartsController, 'get']);
            $group->get('/products', [$this->cartsController, 'getProducts']);
            $group->post('/products/{id}/amount/{amount}', [$this->cartsController, 'addOrUpdateProduct']);
            $group->delete('/products/{id}', [$this->cartsController, 'removeProduct']);
            $group->put('/products/{id}/amount/{amount}', [$this->cartsController, 'addOrUpdateProduct']);
        })->add($this->cartMiddleware);
    }
}
