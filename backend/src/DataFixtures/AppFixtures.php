<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Customer;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $seed = [
            ['name' => 'Alice Johnson', 'email' => 'alice.johnson@example.com', 'company' => 'Acme Corp', 'favorite' => true],
            ['name' => 'Bob Smith', 'email' => 'bob.smith@example.com', 'company' => 'Globex', 'favorite' => false],
            ['name' => 'Carol Lee', 'email' => 'carol.lee@example.com', 'company' => 'Initech', 'favorite' => false],
            ['name' => 'David Kim', 'email' => 'david.kim@example.com', 'company' => 'Hooli', 'favorite' => true],
            ['name' => 'Eva Morales', 'email' => 'eva.morales@example.com', 'company' => 'Umbrella', 'favorite' => false],
            ['name' => 'Frank Zhao', 'email' => 'frank.zhao@example.com', 'company' => 'Stark Industries', 'favorite' => false],
            ['name' => 'Grace Park', 'email' => 'grace.park@example.com', 'company' => 'Wayne Enterprises', 'favorite' => false],
            ['name' => 'Henry Adams', 'email' => 'henry.adams@example.com', 'company' => 'Wonka LLC', 'favorite' => true],
            ['name' => 'Ivy Chen', 'email' => 'ivy.chen@example.com', 'company' => 'Soylent', 'favorite' => false],
            ['name' => 'Jack Wilson', 'email' => 'jack.wilson@example.com', 'company' => 'Vandelay', 'favorite' => false],
            ['name' => 'Karen Davis', 'email' => 'karen.davis@example.com', 'company' => 'Massive Dynamic', 'favorite' => true],
            ['name' => 'Leo Turner', 'email' => 'leo.turner@example.com', 'company' => 'Cyberdyne', 'favorite' => false],
        ];

        foreach ($seed as $row) {
            $customer = (new Customer())
                ->setName($row['name'])
                ->setEmail($row['email'])
                ->setCompany($row['company'])
                ->setFavorite($row['favorite']);

            $manager->persist($customer);
        }

        $manager->flush();
    }
}
