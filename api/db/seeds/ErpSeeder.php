<?php

use Phinx\Seed\AbstractSeed;

class ErpSeeder extends AbstractSeed
{

    public function run()
    {
        // Companies
        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id'      => $i+1,
                'name'    => $faker->company,
                'balance' => $faker->randomNumber(5),
                'country' => $faker->country,
            ];
        }

        $this->insert('company', $data);

        // Products
        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id'      => $i+1,
                'name'    => $faker->colorName,
                'price' => $faker->randomNumber(3),
                'tax' => $faker->numberBetween(1, 100),
                'stock' => $faker->randomNumber(3),
            ];
        }

        $this->insert('product', $data);

        // Providers
        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id'      => $i+1,
                'name'    => $faker->company,
                'address' => $faker->address,
                'country' => $faker->country,
            ];
        }

        $this->insert('provider', $data);

        // Clients
        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id'      => $i+1,
                'name'    => $faker->name,
                'address' => $faker->address,
                'country' => $faker->country,
            ];
        }

        $this->insert('client', $data);

        // Employees
        $data = [];
        $data[] = [
            'id' => 1,
            'name' => 'admin',
            'password' => '$2y$10$NTNqUmxTZEJpaXFaWlRDVObZw8bQ6sfkgzbeQvxQYhb9uef2O21v.', // 123456
            'birthday' => (new \DateTime())->format('Y-m-d'),
            'country' => 'France',
            'firstday' => (new \DateTime())->format('Y-m-d'),
            'role' => 'admin'
        ];
        $data[] = [
            'id' => 2,
            'name' => 'user',
            'password' => '$2y$10$NTNqUmxTZEJpaXFaWlRDVObZw8bQ6sfkgzbeQvxQYhb9uef2O21v.', // 123456
            'birthday' => (new \DateTime())->format('Y-m-d'),
            'country' => 'France',
            'firstday' => (new \DateTime())->format('Y-m-d'),
            'role' => 'user'
        ];

        $this->insert('employee', $data);

         // Transactions
        $data = [];
        $data[] = [
            'id' => 1,
            'company_id' => 1,
            'client_id' => 1,
            'product_id' => 1,
            'quantity' => 1,
            'employee_id' => 1
        ];

        $this->insert('transaction', $data);

    }
}
