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
            'عمومی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'خبری' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'علم و تکنولوژی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'ورزشی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'بانوان' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'بازی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'طنز' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'آموزشی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'تفریحی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'فیلم' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'مذهبی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'موسیقی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'سیاسی' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'حوادث' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'گردشگری' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'حیوانات' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'متفرقه' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'تبلیغات' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'هنری' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'کارتون' => ['icon' => null, 'banner' => null, 'user_id' => null],
            'سلامت' => ['icon' => null, 'banner' => null, 'user_id' => null],

            /*category for user 1 (normal user )*/
            'دسته بندی یک' => ['icon' => null, 'banner' => null, 'user_id' => 2],


        ];

        foreach ($data as $key => $value) {

            Category::query()->create([
                Category::col_title => $key,
                Category::col_icon => $value['icon'],
                Category::col_banner => $value['banner'],
                Category::col_user_id => $value['user_id']
            ]);

            $this->command->info('Category => ' . $key . ' -- ADDED');
        }
    }
}
