<?php

namespace shop\forms\manager\Shop\Product;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use shop\entities\Shop\Product\Product;

class CategoriesForm extends Model
{
    public $main;
    public $others = [];

    public function __construct(Product $product = null, array $config = [])
    {
        if($product) {
            $this->main = $product->category_id;
            $this->others= ArrayHelper::getColumn($product->categoryAssignments, 'category_id');
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['main', 'required'],
            ['main', 'integer'],
            ['others', 'each', 'rule' => ['integer']]
        ];
    }
}