<?php
use App\Models\Users;
use Illuminate\Database\Seeder;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run () {
        factory(Users::class, 20)->create();
    }
}
