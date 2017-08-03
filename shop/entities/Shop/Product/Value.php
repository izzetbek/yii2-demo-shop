<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $product_id
 * @property integer $characteristic_id
 * @property string $value
 */
class Value extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_product_values}}';
    }

    public static function create($characteristicId, $value): self
    {
        $object = new static();
        $object->characteristic_id = $characteristicId;
        $object->value = $value;
        return $object;
    }

    public static function blank($characteristicId): self
    {
        $object = new static();
        $object->characteristic_id = $characteristicId;
        return $object;
    }

    public function isForCharacteristic($id): bool
    {
        return $this->characteristic_id == $id;
    }
}