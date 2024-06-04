<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Umutphp\LaravelModelRecommendation\InteractWithRecommendation;
use Umutphp\LaravelModelRecommendation\HasRecommendation;

class Product extends Model implements InteractWithRecommendation
{
    use HasFactory, HasRecommendation;
    protected $table = 'products';
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'name',
        'category_id',
        'brand_id',
        'status',
        'newest',
        'sell_off',
        'image',
        'price_off',
        'list_images',
        'colors',
        'sizes',
        'import_price',
        'description',
    ];

    // public static function getRecommendationConfig(): array
    // {
    //     return [
    //         'sold_together' => [
    //             'recommendation_algorithm'         => 'db_relation',
    //             'recommendation_data_table'        => 'order_products',
    //             'recommendation_data_table_filter' => [
    //                 // 'field' => 'value'
    //             ],
    //             'recommendation_data_field'        => 'product_id',
    //             'recommendation_data_field_type'   => self::class,
    //             'recommendation_group_field'       => 'order_id',
    //             'recommendation_count'             => 5,
    //             // 'recommendation_order'             => 'desc'
    //         ]
    //     ];
    // }

    public static function getRecommendationConfig(): array
    {
        return [
            'similar_products' => [
                'recommendation_algorithm'            => 'similarity',
                'similarity_feature_weight'           => 1,
                'similarity_numeric_value_weight'     => 1,
                'similarity_numeric_value_high_range' => 1,
                'similarity_taxonomy_weight'          => 1,
                'similarity_feature_attributes'       => [
                    // 'material',
                    'colors',
                    'sizes',
                ],
                'similarity_numeric_value_attributes' => [
                    'price_off'
                ],
                'similarity_taxonomy_attributes'      => [
                    [
                        'category' => 'name',
                        'brand' => 'name',
                    ]
                ],
                'recommendation_count'                => 5,
                'recommendation_order'                => 'desc'
            ]
        ];
    }

    /**
     * Get the category associated with the product.
     */
    public function category()
    {
        return $this->hasOne(Category::class, 'id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id');
    }
}
