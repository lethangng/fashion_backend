<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Size;
use App\Models\User;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Category;
use App\Models\DeliveryAddress;
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

        $categories = ['Thời trang nam', 'Thời trang nữ', 'Thời trang trẻ em', 'Phụ kiện'];

        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->name = $categoryName;
            $category->save();
        }

        $brands = ['H&M', 'Nike', 'Gucci', 'Adidas'];

        foreach ($brands as $brandName) {
            $brand = new Brand();
            $brand->name = $brandName;
            $brand->save();
        }

        $sizes = ['L', 'M', 'XL', 'S', '2XL'];

        foreach ($sizes as $sizeName) {
            $size = new Size();
            $size->size = $sizeName;
            $size->save();
        }

        $colors = [
            [
                'name' => 'Xanh',
                'color' => '#50a5f1',
            ],
            [
                'name' => 'Vàng',
                'color' => '#ffd966',
            ],
            [
                'name' => 'Be',
                'color' => '#d3c9bf',
            ],
            [
                'name' => 'Đen',
                'color' => 'black',
            ],
            [
                'name' => 'Tím',
                'color' => 'purple',
            ],
            [
                'name' => 'Đỏ',
                'color' => '#f44336',
            ],
            [
                'name' => 'Bạc',
                'color' => 'silver',
            ],
            [
                'name' => 'Lục',
                'color' => '#32a852',
            ],
            [
                'name' => 'Hồng',
                'color' => 'pink',
            ]
        ];

        foreach ($colors as $colorName) {
            $color = new Color();
            $color->name = $colorName['name'];
            $color->color = $colorName['color'];
            $color->save();
        }

        $deliveryAddress = new DeliveryAddress();
        $deliveryAddress->user_id = 1;
        $deliveryAddress->city = 'Hà Nội';
        $deliveryAddress->address = 'ĐH.Thủy Lợi';
        $deliveryAddress->save();
    }
}
