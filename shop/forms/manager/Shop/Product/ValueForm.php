<?php

namespace shop\forms\manager\Shop\Product;

use shop\entities\Shop\Characteristic;
use yii\base\Model;
use shop\entities\Shop\Product\Value;

class ValueForm extends Model
{
    public $id;
    public $value;

    private $_characteristic;

    public function __construct(Characteristic $characteristic, Value $value = null, array $config = [])
    {
        if($value) {
            $this->value = $value->value;
        }
        $this->id = $characteristic->id;
        $this->_characteristic = $characteristic;
        parent::__construct($config);
    }

    public function rules()
    {
        return array_filter([
            $this->_characteristic->required ? ['value', 'required'] : false,
            $this->_characteristic->isString() ? ['value', 'string', 'max' => 255] : false,
            $this->_characteristic->isInteger() ? ['value', 'integer'] : false,
            $this->_characteristic->isFloat() ? ['value', 'number'] : false,
            ['value', 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'value' => $this->_characteristic->name,
        ];
    }

    public function getId(): int
    {
        return $this->_characteristic->id;
    }
}