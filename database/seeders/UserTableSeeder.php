<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        User::truncate();
        if (User::count()) {
            return ;
        }
        static::createAdmin();
        static::createUser();
    }

    public function createAdmin()
    {
        User::factory()->admin()
            ->state([
                'name' => 'Administatrator',
                'email' => 'admin@a.b',
                'mobile' => '+989356743672',
            ])
            ->create();

        $this->command->info(static::msg('Adminstrator'));
    }

    public function createUser()
    {
        User::factory()
            ->state([
                'name' => 'User',
                'email' => 'user@a.b',
                'mobile' => '+989356743673',
            ])
            ->create();
        $this->command->info(static::msg());
    }

    private static function msg($type = 'Normal')
    {
        return "A $type User was created Successfully";
    }
}
