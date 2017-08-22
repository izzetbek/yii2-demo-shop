<?php

namespace shop\forms\manager\Shop\Product;

use shop\entities\Shop\Characteristic;
use shop\forms\CompositForm;
use shop\forms\manager\MetaForm;
use shop\entities\Shop\Product\Product;

/**
 * @property PriceForm $price
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property TagsForm $tags
 * @property PhotosForm $photos
 * @property ValueForm[] $values
 */
class ProductEditForm extends CompositForm
{
    public $brandId;
    public $code;
    public $name;

    private $_product;

    public function __construct(Product $product, array $config = [])
    {
        $this->meta = new MetaForm($product->meta);
        $this->tags = new TagsForm($product);
        $this->values = array_map(function(Characteristic $characteristic) {
            return new ValueForm($characteristic, $this->_product->getValue($characteristic->id));
        }, Characteristic::find()->orderBy('sort')->all());
        $this->_product = $product;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['brandId', 'code', 'name'], 'required'],
            [['code', 'name'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
            [['code'], 'unique', 'targetClass' => Product::class, 'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null],
        ];
    }

    public function internalForms(): array
    {
        return ['meta', 'tags', 'values'];
    }
}