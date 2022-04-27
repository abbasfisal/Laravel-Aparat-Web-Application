<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Category::count()) {
            Category::query()->truncate();

        }

        $data = [
            'عمومی' => ['icon' => '', 'banner' => ''],
            'خبری' => ['icon' => '', 'banner' => ''],
            'علم و تکنولوژی' => ['icon' => '', 'banner' => ''],
            'ورزشی' => ['icon' => '', 'banner' => ''],
            'بانوان' => ['icon' => '', 'banner' => ''],
            'بازی' => ['icon' => '', 'banner' => ''],
            'طنز' => ['icon' => '', 'banner' => ''],
            'آموزشی' => ['icon' => '', 'banner' => ''],
            'تفریحی' => ['icon' => '', 'banner' => ''],
            'فیلم' => ['icon' => '', 'banner' => ''],
            'مذهبی' => ['icon' => '', 'banner' => ''],
            'موسیقی' => ['icon' => '', 'banner' => ''],
            'سیاسی' => ['icon' => '', 'banner' => ''],
            'حوادث' => ['icon' => '', 'banner' => ''],
            'گردشگری' => ['icon' => '', 'banner' => ''],
            'حیوانات' => ['icon' => '', 'banner' => ''],
            'متفرقه' => ['icon' => '', 'banner' => ''],
            'تبلیغات' => ['icon' => '', 'banner' => ''],
            'هنری' => ['icon' => '', 'banner' => ''],
            'کارتون' => ['icon' => '', 'banner' => ''],
            'سلامت' => ['icon' => '', 'banner' => '']

        ];

        foreach ($data as $key => $value) {

            Category::query()->create([
                Category::col_title => $key,
                Category::col_icon => $value['icon'],
                Category::col_banner => $value['banner']
            ]);

            $this->command->info('Category => ' . $key . ' -- ADDED');
        }
    }
}
