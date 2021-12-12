<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class CreateCartsTable extends AbstractMigration
{
    public function change(): void
    {
        if ($this->hasTable('products')) {
            $this->table('carts', [
                'id' => false,
                'primary_key' => 'id'
            ])
                ->addColumn('id', 'uuid', [
                    'default' => Literal::from('gen_random_uuid()')
                ])
                ->addColumn('customers_id', 'uuid')
                ->addColumn('updated', 'timestamp', [
                    'null' => true,
                    'default' => null
                ])
                ->addColumn('created', 'timestamp', [
                    'default' => 'CURRENT_TIMESTAMP'
                ])
                ->addForeignKey('customers_id', 'customers', 'id', [
                    'delete' => 'CASCADE',
                    'update' => 'CASCADE'
                ])
                ->save();
        }
    }
}
