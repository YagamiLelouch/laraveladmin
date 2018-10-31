<?php
/**
 * Created by PhpStorm.
 * User: Wen
 * Date: 2018/9/26/026
 * Time: 22:21
 */

namespace App\Services;
use App\Models\Admin\Category;

class CategoryService
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }
}