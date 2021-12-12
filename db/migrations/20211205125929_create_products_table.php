<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class CreateProductsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('products', [
            'id' => false,
            'primary_key' => 'id'
        ])
            ->addColumn('id', 'uuid', [
                'default' => Literal::from('gen_random_uuid()')
            ])
            ->addColumn('name', 'string', [
                'limit' => 200
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'default' => null,
            ])
            ->addColumn('price', 'float')
            ->addColumn('updated', 'timestamp', [
                'null' => true,
                'default' => null
            ])
            ->addColumn('created', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP'
            ])
            ->save();
    }
}
