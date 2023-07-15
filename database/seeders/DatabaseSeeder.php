<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->createMany([
            ['name' => '二村'],
            ['name' => '渡邉'],
            ['name' => '平山'],
            ['name' => 'ME'],
        ]);
        //        PhoneCall::factory()->create();
    }
}
