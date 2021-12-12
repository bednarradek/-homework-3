<?php

declare(strict_types=1);

use Faker\Factory;
use Faker\Provider\Base;
use Phinx\Seed\AbstractSeed;

class ProductsSeeder extends AbstractSeed
{
    private const TABLE = 'products';

    private const PRODUCT_LIST = [
        'Speakers',
        'Playing card',
        'Truck',
        'Apple',
        'Eye liner',
        'Book',
        'Key chain',
        'Canvas',
        'Purse',
        'Doll',
        'Tomato',
        'Picture frame',
        'Rubber duck',
        'Cat',
        'Stockings',
        'Chalk',
        'Toothpaste',
        'Glow stick',
        'Mp3 player',
        'Remote',
        'Cork',
        'Cell phone',
        'Tree',
        'Bed',
        'Checkbook',
        'Clamp',
        'Cinder block',
        'Video games',
        'Towel',
        'Brocolli',
        'Pillow',
        'Glasses',
        'Fridge',
        'Soap',
        'Cookie jar',
        'Boom box',
        'Fork',
        'Bag',
        'Mop',
        'Soda can',
        'Pants',
        'Stop sign',
        'Pencil',
        'Watch',
        'Ring',
        'Bookmark',
        'Toothbrush',
        'Desk',
        'Box',
        'Glass'
    ];

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
        for ($i = 0; $i < 50; $i++) {
            $data[] = [
                'name' => self::PRODUCT_LIST[$i],
                'description' => $faker->realText(100),
                'price' => Base::randomFloat(2, 50, 300),
            ];
        }

        $this->table(self::TABLE)
            ->insert($data)
            ->saveData();
    }
}
