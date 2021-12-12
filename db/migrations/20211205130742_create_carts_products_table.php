<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class CreateCartsProductsTable extends AbstractMigration
{
    public function change(): void
    {
        if ($this->hasTable('carts') && $this->hasTable('products')) {
            $this->table('carts_products', [
                'id' => false,
                'primary_key' => 'id'
            ])
                ->addColumn('id', 'uuid', [
                    'default' => Literal::from('gen_random_uuid()')
                ])
                ->addColumn('carts_id', 'uuid')
                ->addColumn('products_id', 'uuid')
                ->addColumn('amount', 'integer')
                ->addColumn('updated', 'timestamp', [
                    'null' => true,
                    'default' => null
                ])
                ->addColumn('created', 'timestamp', [
                    'default' => 'CURRENT_TIMESTAMP'
                ])
                ->addForeignKey('carts_id', 'carts', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'CASCADE'
                ])
                ->addForeignKey('products_id', 'products', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'CASCADE'
                ])
                ->save();
        }
    }
}
