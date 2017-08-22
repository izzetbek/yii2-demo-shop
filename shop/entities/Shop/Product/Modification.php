<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $price
 */
class Modification extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_product_modification}}';
    }

    public static function create($code, $name, $price): self
    {
        $modification = new static();
        $modification->code = $code;
        $modification->name = $name;
        $modification->price = $price;
        return $modification;
    }

    public function edit($code, $name, $price): void
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
    }

    public function isEqualTo($id)
    {
        return $this->id === $id;
    }

    public function isCodeEqualTo($code)
    {
        return $this->code = $code;
    }
}