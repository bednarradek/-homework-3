<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Util\Literal;

final class CreateCustomersTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('customers', [
            'id' => false,
            'primary_key' => 'id'
        ])
            ->addColumn('id', 'uuid', [
                'default' => Literal::from('gen_random_uuid()')
            ])
            ->addColumn('first_name', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 100
            ])
            ->addColumn('last_name', 'string', [
                'null' => true,
                'default' => null,
                'limit' => 100
            ])
            ->addColumn('email', 'string', [
                'limit' => 200
            ])
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
