<?php

declare(strict_types=1);

use Faker\Provider\Base;
use Phinx\Seed\AbstractSeed;

class CartProductsSeeder extends AbstractSeed
{
    private const TABLE = 'carts_products';

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return ['TruncateSeeder', 'CartsSeeder'];
    }

    public function run()
    {
        for ($i = 0; $i < 20; $i++) {
            $cart = $this->fetchRow("SELECT id FROM carts ORDER BY random() LIMIT 1;");
            $product = $this->fetchRow("SELECT id FROM products ORDER BY random() LIMIT 1;");

            $cartId = $cart ? $cart['id'] ?? null : null;
            $productId = $product ? $product['id'] ?? null : null;

            if ($cartId && $productId) {
                $cartsProducts = $this->fetchRow(
                    "SELECT * FROM carts_products WHERE carts_id = '{$cartId}' AND products_id = '{$productId}';"
                );

                if (!$cartsProducts) {
                    $this->table(self::TABLE)
                        ->insert([
                            'carts_id' => $cartId,
                            'products_id' => $productId,
                            'amount' => Base::numberBetween(1, 6)
                        ])
                        ->saveData();
                }
            }
        }
    }
}
