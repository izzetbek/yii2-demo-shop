<?php

namespace shop\entities\Shop;

use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class Characteristic
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $required
 * @property string $default
 * @property integer $sort
 * @property array $variants
 */
class Characteristic extends ActiveRecord
{
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_STRING = 'string';

    public $variants;

    public static function tableName()
    {
        return '{{%shop_characteristics}}';
    }

    /**
     * @param $name
     * @param $type
     * @param $required
     * @param $default
     * @param $variants
     * @param $sort
     * @return Characteristic
     */
    public static function create($name, $type, $required, $default, array $variants, $sort): self
    {
        $characteristic = new static();
        $characteristic->name = $name;
        $characteristic->type = $type;
        $characteristic->required = $required;
        $characteristic->default = $default;
        $characteristic->variants = $variants;
        $characteristic->sort = $sort;
        return $characteristic;
    }

    /**
     * @param $name
     * @param $type
     * @param $required
     * @param $default
     * @param array $variants
     * @param $sort
     */
    public function edit($name, $type, $required, $default, array $variants, $sort): void
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->default = $default;
        $this->variants = $variants;
        $this->sort = $sort;
    }

    /**
     * @return bool
     */
    public function isSelect(): bool
    {
        return count($this->variants) > 0;
    }

    public function isString(): bool
    {
        return is_string($this->name);
    }

    public function isInteger(): bool
    {
        return is_integer($this->name);
    }

    public function isFloat(): bool
    {
        return is_float($this->name);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->setAttribute('variants_json', Json::encode($this->variants));
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->variants = Json::decode($this->getAttribute('variants_json'));
        parent::afterFind();
    }
}