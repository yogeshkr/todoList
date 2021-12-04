<?php

use Illuminate\Database\Seeder;
use App\Models\Token;


class TokenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run () {
        factory(Token::class, 20)->create();
    }
}
