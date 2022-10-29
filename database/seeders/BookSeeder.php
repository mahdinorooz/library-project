<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::insert([[
            'name' => 'برقکشی ساختمان',
            'author' => 'میراحمدی',
            'description' => 'اصول برقکشی ساختمان',
            'price' => '500000',
            'release_date' => '2012-08-09',
            'number_of_pages' => '200',
            'type_id' => '1',
        ],[
            'name' => 'لوله کشی ساختمان',
            'author' => 'قاسم قاضی زاده',
            'description' => 'اصول لوله کشی ساختمان ',
            'price' => '600000',
            'release_date' => '2000-06-09',
            'number_of_pages' => '264',
            'type_id' => '1',
        ]]);
    }
}
