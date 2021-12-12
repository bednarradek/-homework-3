<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class TruncateSeeder extends AbstractSeed
{
    public function run()
    {
        $this->execute("TRUNCATE carts_products, carts, products, customers;");
    }
}
