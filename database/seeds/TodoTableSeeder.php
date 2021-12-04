<?php

use Illuminate\Database\Seeder;
use App\Models\Todo;

class TodoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run () {
        factory(Todo::class, 20)->create();
    }
}
