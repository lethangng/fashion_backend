<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $user = new User();
        $user->u_id = 'KKOAW8GqDRXGAvjPZ4biVrWpzho2';
        $user->fullname = 'Admin';
        $user->email = 'admin@gmail.com';
        $user->password = Hash::make('123456');
        $user->role = 0;
        $user->login_type = 'password';
        $user->save();

        $category = new Category();
        $category->name = 'Quần áo nam';
        $category->save();

        $category = new Category();
        $category->name = 'Quần áo nữ';
        $category->save();

        $category = new Category();
        $category->name = 'Quần áo trẻ em';
        $category->save();

        $category = new Brand();
        $category->name = 'H&M';
        $category->save();

        $category = new Brand();
        $category->name = 'Nike';
        $category->save();

        $category = new Brand();
        $category->name = 'Gucci';
        $category->save();

        $category = new Brand();
        $category->name = 'Adidas';
        $category->save();
    }
}
