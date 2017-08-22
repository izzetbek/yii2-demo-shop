<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;

/**
 * @property integer $product_id
 * @property integer $related_product_id
 */
class RelatedProductAssignment extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_product_related_product_assignments}}';
    }

    public static function create($relatedProductId): RelatedProductAssignment
    {
        $assignment = new static();
        $assignment->related_product_id = $relatedProductId;
        return $assignment;
    }

    public function isForRelatedProduct($id): bool
    {
        return $this->related_product_id == $id;
    }
}