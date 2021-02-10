<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private $URLS = [
        'https://google.com',
        'https://yandex.ru'
    ];
    public function run()
    {
        foreach ($this->URLS as $url) {
            DB::table('urls')->insert([
                'name' => $url,
                'created_at' => Carbon::parse(Carbon::now()),
                'updated_at' => Carbon::parse(Carbon::now())
            ]);
        }
    }
}
