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
class ProductCreateForm extends CompositForm
{
    public $brandId;
    public $code;
    public $name;

    public function __construct(array $config = [])
    {
        $this->price = new PriceForm();
        $this->meta = new MetaForm();
        $this->categories = new CategoriesForm();
        $this->tags = new TagsForm();
        $this->photos = new PhotosForm();
        $this->values = array_map(function(Characteristic $characteristic) {
            return new ValueForm($characteristic);
        }, Characteristic::find()->orderBy('sort')->all());
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['brandId', 'code', 'name'], 'required'],
            [['code', 'name'], 'string', 'max' => 255],
            [['brandId'], 'integer'],
            [['code'], 'unique', 'targetClass' => Product::class],
        ];
    }

    public function internalForms(): array
    {
        return ['price', 'meta', 'categories', 'tags', 'photos', 'values'];
    }
}