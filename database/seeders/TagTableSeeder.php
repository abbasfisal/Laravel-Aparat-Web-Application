<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'عمومی',
            'خبری',
            'علم و تکنولوژی',
            'ورزشی',
            'بانوان',
            'بازی',
            'طنز',
            'آموزشی',
            'تفریحی',
            'فیلم',
            'مذهبی',
            'موسیقی',
            'سیاسی',
            'حوادث',
            'گردشگری',
            'حیوانات',
            'متفرقه',
            'تبلیغات',
            'هنری',
            'کارتون',
            'سلامت'
        ];

        if (Tag::count()) {
            Tag::truncate();
        }

        foreach ($data as $item) {

            Tag::query()->create([
                Tag::col_title => $item
            ]);


        }
        $this->command->info('Tag Items Just Added');

    }
}
