<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;

/**
 * @property integer $tag_id
 * @property integer $product_id
 */
class TagAssignment extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_product_tag_assignments}}';
    }

    public static function create($tagId): self
    {
        $assignment = new static();
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    public function isForTag($id): bool
    {
        return $this->tag_id == $id;
    }
}