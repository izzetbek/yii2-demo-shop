<?php

namespace shop\forms\manager\Shop\Product;

use yii\base\Model;
use shop\entities\Shop\Product\Product;

class PriceForm extends Model
{
    public $old;
    public $new;

    public function __construct(Product $product = null, array $config = [])
    {
        if($product) {
            $this->new = $product->price_new;
            $this->old = $product->price_old;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['new'], 'required'],
            [['old', 'new'], 'integer', 'min' => 0],
        ];
    }
}