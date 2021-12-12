<?php

declare(strict_types=1);

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class CustomersSeeder extends AbstractSeed
{
    private const TABLE = 'customers';

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return ['TruncateSeeder'];
    }

    public function run()
    {
        $faker = Factory::create();
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
            ];
            if ($i === 0) {
                $data[0]['id'] = 'ce3f51d4-80ff-4bf1-b7e5-6172249be169';
            }
        }

        $this->table(self::TABLE)
            ->insert($data)
            ->saveData();
    }
}
