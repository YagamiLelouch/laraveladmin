<?php
/**
 * Created by PhpStorm.
 * User: shaowen.wang
 * Date: 2018/9/26
 * Time: 9:07
 */

namespace App\Services;
use App\Models\Admin\Product;


class ProductService
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

}