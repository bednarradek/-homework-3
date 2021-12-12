<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\StatusMessage;
use App\Orm\Entities\ProductEntity;
use App\Orm\Repositories\ProductsRepository;
use Nette\Schema\Elements\Structure;
use Nette\Schema\Expect;
use Nette\Schema\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class ProductsController extends BaseController
{
    public function __construct(private ProductsRepository $productsRepository)
    {
        parent::__construct();
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $products = $this->productsRepository->findAll();
        return $this->sendJsonResponse($response, $products);
    }

    /**
     * @throws HttpNotFoundException
     * @throws HttpBadRequestException
     */
    public function getOne(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $this->checkValidUuid($id, $request);
        $product = $this->productsRepository->findById($id);
        if (!$product) {
            throw new HttpNotFoundException($request, 'Product not found');
        }
        return $this->sendJsonResponse($response, $product);
    }

    /**
     * @throws HttpBadRequestException
     * @throws HttpNotFoundException
     */
    public function delete(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $this->checkValidUuid($id, $request);

        $product = $this->productsRepository->findById($id);

        if (!$product) {
            throw new HttpNotFoundException($request, 'Product not found');
        }

        $this->productsRepository->delete($product);

        return $this->sendJsonResponse(
            $response,
            new StatusMessage('ok'),
        );
    }

    /**
     * @throws HttpBadRequestException
     */
    public function post(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body, flags: JSON_OBJECT_AS_ARRAY);

        try {
            $this->schemaProcessor->process($this->getItemStructure(), $data);
        } catch (ValidationException $exception) {
            throw new HttpBadRequestException($request, $exception->getMessage());
        }

        $item = new ProductEntity($this->productsRepository);
        $item->setName($data['name']);
        $item->setDescription($data['description']);
        $item->setPrice($data['price']);
        $item->save();

        return $this->sendJsonResponse(
            $response,
            new StatusMessage('Product was created')
        );
    }

    /**
     * @throws HttpBadRequestException
     * @throws HttpNotFoundException
     */
    public function put(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $body = $request->getBody()->getContents();
        $data = json_decode($body, flags: JSON_OBJECT_AS_ARRAY);

        $this->checkValidUuid($id, $request);

        try {
            $this->schemaProcessor->process($this->getItemStructure(), $data);
        } catch (ValidationException $exception) {
            throw new HttpBadRequestException($request, $exception->getMessage());
        }

        $item = $this->productsRepository->findById($id);

        if (!$item) {
            throw new HttpNotFoundException($request, 'Product not found');
        }

        $item->setName($data['name']);
        $item->setDescription($data['description']);
        $item->setPrice($data['price']);
        $item->save();

        return $this->sendJsonResponse(
            $response,
            new StatusMessage('Product was edited')
        );
    }

    private function getItemStructure(): Structure
    {
        return Expect::structure([
            'name' => Expect::string()->required(),
            'description' => Expect::string()->required(),
            'price' => Expect::type('float|int')->required()
        ]);
    }
}
