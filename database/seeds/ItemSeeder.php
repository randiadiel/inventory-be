<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        for($i =0 ; $i < 100; $i++){
        $faker = Faker\Factory::create();
        DB::table('items')->insert([
            'name' => $faker->firstName,
            'qty' => $i,
            'price' => $faker->randomNumber(3)*1000,
            'date_registered' => $faker->datetime,
            'box_status' => ($i%2 == 0)? 0 : 1,
            'location' => $faker->address,
            'id' => Str::random(10),
            'created_at' => $faker->datetime,
            'updated_at' => $faker->datetime,
        ]);
        }
        DB::table('users')->insert([
            'name' => "admin",
            'email' => "admin@randiadiel.xyz",
            'email_verified_at' => $faker->datetime,
            'password' => bcrypt('halohalo'),
            'remember_token' => 'kdjeubvp4',
            'created_at' => $faker->datetime,
            'updated_at' => $faker->datetime,
        ]);
    }
}
