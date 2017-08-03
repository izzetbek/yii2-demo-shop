<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $file
 * @property integer $sort
 */
class Photo extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_product_photos}}';
    }

    public static function create($file): self
    {
        $photo = new static();
        $photo->file = $file;
        return $photo;
    }

    public function isEqualTo($id): bool
    {
        return $this->id === $id;
    }

    public function setSort($sort): void
    {
        $this->sort = $sort;
    }
}