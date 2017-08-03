<?php

namespace shop\forms\manager\Shop;

use shop\entities\Shop\Category;
use shop\forms\CompositForm;
use shop\forms\manager\MetaForm;
use shop\validators\SlugValidator;

/**
 * @property MetaForm $meta;
 */
class CategoryForm extends CompositForm
{
    public $name;
    public $slug;
    public $title;
    public $description;
    public $parentId;

    private $_category;

    public function internalForms(): array
    {
        return ['meta'];
    }

    public function __construct(Category $category = null, array $config = [])
    {
        if($category) {
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->title = $category->title;
            $this->description = $category->description;
            $this->parentId = $category->parent ? $category->parent->id : null;
            $this->meta = new MetaForm($category->meta);
            $this->_category = $category;
        } else {
            $this->meta = new MetaForm();
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'title', 'slug'], 'required'],
            [['name', 'title', 'slug'], 'string', 'max' => 255],
            ['description', 'string'],
            ['parentId', 'integer'],
            ['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Category::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null]
        ];
    }
}