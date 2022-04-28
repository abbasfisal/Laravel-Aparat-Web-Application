<?php

namespace Database\Seeders;

use App\Models\PlayList;
use Illuminate\Database\Seeder;

class PlayListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (PlayList::count()) {
            PlayList::truncate();
        }

        $data = [
            'لیست پخش یک',
            'لیست پخش دو',
        ];

        foreach ($data as $item) {
            PlayList::query()->create([
                PlayList::col_user_id => 2,
                PlayList::col_title => $item
            ]);
        }

        $this->command->info('playList created =>' . implode(',', $data));
    }
}
