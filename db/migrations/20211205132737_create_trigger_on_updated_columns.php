<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTriggerOnUpdatedColumns extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(
            "
            CREATE OR REPLACE FUNCTION trigger_update()
            RETURNS TRIGGER AS $$
            BEGIN
              NEW.updated = NOW();
              RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        "
        );

        if ($this->hasTable('customers')) {
            $this->setupTrigger('customers');
        }

        if ($this->hasTable('products')) {
            $this->setupTrigger('products');
        }

        if ($this->hasTable('carts')) {
            $this->setupTrigger('carts');
        }

        if ($this->hasTable('carts_products')) {
            $this->setupTrigger('carts_products');
        }
    }

    protected function setupTrigger(string $tableName): void
    {
        $this->execute(
            "
            CREATE TRIGGER set_updated
            BEFORE UPDATE ON {$tableName}
            FOR EACH ROW
            EXECUTE PROCEDURE trigger_update();
        "
        );
    }

    public function down(): void
    {
        $this->execute("DROP TRIGGER IF EXISTS set_updated ON customers;");
        $this->execute("DROP TRIGGER IF EXISTS set_updated ON products;");
        $this->execute("DROP TRIGGER IF EXISTS set_updated ON carts;");
        $this->execute("DROP TRIGGER IF EXISTS set_updated ON carts_products;");
        $this->execute("DROP FUNCTION IF EXISTS trigger_update;");
    }
}
