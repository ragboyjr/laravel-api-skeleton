<?php

use Illuminate\Database\Seeder;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseFixtures extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(EntityManagerInterface $em)
    {
        // $this->call(UserFixtures::class);
        $em->flush();
    }
}
