<?php

namespace shop\forms\manager\Shop\Product;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use shop\entities\Shop\Product\Product;

/**
 * @property array $newNames
 */
class TagsForm extends Model
{
    public $existingTags = [];
    public $textNew;

    public function __construct(Product $product = null, array $config = [])
    {
        if($product) {
            $this->existingTags = ArrayHelper::getColumn($product->tagAssignments, 'tag_id');
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['existing', 'each', 'rule' => ['integer']],
            ['textNew', 'string']
        ];
    }

    public function getNewNames(): array
    {
        return array_map('trim', preg_split('#\s*,\s*#i', $this->textNew));
    }
}