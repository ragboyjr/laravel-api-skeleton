<?php

use Illuminate\Database\Seeder;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(EntityManagerInterface $em)
    {
        // $this->call(UsersTableSeeder::class);
        $em->flush();
    }
}
