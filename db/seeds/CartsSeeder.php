<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CartsSeeder extends AbstractSeed
{
    private const TABLE = 'carts';

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return ['TruncateSeeder', 'ProductsSeeder', 'CustomersSeeder'];
    }

    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            $customer = $this->fetchRow("SELECT id FROM customers ORDER BY random() LIMIT 1;");
            $customerId = $customer ? $customer['id'] ?? null : null;

            if ($customerId) {
                $cart = $this->fetchRow("SELECT * FROM carts WHERE customers_id = '{$customerId}';");
                if (!$cart) {
                    $this->table(self::TABLE)
                        ->insert([
                            'customers_id' => $customerId
                        ])
                        ->saveData();
                }
            }
        }
    }
}
