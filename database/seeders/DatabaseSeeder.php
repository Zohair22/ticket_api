<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Zohair Osama',
            'email' => 'zohairosama321@gmail.com',
        ])->tickets()->createMany(Ticket::factory()->count(3)->make()->toArray());

        User::factory()->count(5)->create()->each(function ($user) {
            Ticket::factory()->count(3)->create(['user_id' => $user->id]);
        });
    }
}
