<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Managers\IdentityManager;
use App\Models\StatusMessage;
use App\Orm\Entities\CartEntity;
use App\Orm\Entities\CartProductEntity;
use App\Orm\Repositories\CartsProductsRepository;
use App\Orm\Repositories\ProductsRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

class CartsController extends BaseController
{
    public function __construct(
        private CartsProductsRepository $cartsProductsRepository,
        private ProductsRepository $productsRepository,
        private IdentityManager $identityManager
    ) {
        parent::__construct();
    }

    /**
     * @throws HttpInternalServerErrorException
     */
    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $cart = $this->getCart($request);
        return $this->sendJsonResponse($response, $cart);
    }

    public function getProducts(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $products = $this->identityManager->getCartProducts();
        return $this->sendJsonResponse($response, $products);
    }

    /**
     * @throws HttpBadRequestException
     * @throws HttpNotFoundException
     * @throws HttpInternalServerErrorException
     */
    public function addOrUpdateProduct(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $amount = $request->getAttribute('amount');
        $this->checkValidUuid($id, $request);

        if (!is_numeric($amount)) {
            throw new HttpBadRequestException($request, 'Amount except be number');
        }

        $amount = (int)$amount;

        $cart = $this->getCart($request);

        $product = $this->productsRepository->findById($id);
        if (!$product) {
            throw new HttpNotFoundException($request, 'Product not found');
        }

        $cartProductEntity = $this->cartsProductsRepository->findFirst([
            'carts_id' => $cart->getId(),
            'products_id' => $product->getId()
        ]);

        if ($cartProductEntity) {
            $cartProductEntity->setAmount($amount);
        } else {
            $cartProductEntity = new CartProductEntity($this->cartsProductsRepository);
            $cartProductEntity->setAmount($amount);
            $cartProductEntity->setCart($cart);
            $cartProductEntity->setProduct($product);
        }

        $cartProductEntity->save();

        return $this->sendJsonResponse(
            $response,
            new StatusMessage('Product was added')
        );
    }

    /**
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     * @throws HttpInternalServerErrorException
     */
    public function removeProduct(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $this->checkValidUuid($id, $request);

        $cart = $this->getCart($request);

        $product = $this->productsRepository->findById($id);
        if (!$product) {
            throw new HttpNotFoundException($request, 'Product not found');
        }

        $cartProductEntity = $this->cartsProductsRepository->findFirst([
            'carts_id' => $cart->getId(),
            'products_id' => $product->getId()
        ]);

        if ($cartProductEntity) {
            $this->cartsProductsRepository->delete($cartProductEntity);
        }

        return $this->sendJsonResponse(
            $response,
            new StatusMessage('Product was removed')
        );
    }

    /**
     * @throws HttpInternalServerErrorException
     */
    private function getCart(ServerRequestInterface $request): CartEntity
    {
        $cart = $this->identityManager->getCart();
        if (!$cart) {
            throw new HttpInternalServerErrorException($request, 'Cart is not initialized');
        }
        return $cart;
    }
}
