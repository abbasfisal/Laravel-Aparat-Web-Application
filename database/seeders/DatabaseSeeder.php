<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // \App\Models\User::factory(10)->create();
        $this->call(UserTableSeeder::class);

        $this->call(CategoryTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(PlayListTableSeeder::class);


        Artisan::call('passport:install');

        Schema::enableForeignKeyConstraints();

        //region clear aparat temp dir
        $this->command->info('Clearing All Aparat Direcotry ');
        Artisan::call('aparat:clear');
        $this->command->info('Cleared All Directories');
        //endregion

    }
}
