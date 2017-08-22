<?php

namespace shop\entities\Shop\Product;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $created_at
 * @property int $user_id
 * @property int $vote
 * @property string $text
 * @property bool $status
 */
class Review extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_product_reviews}}';
    }

    public static function create($userId, $vote, $text): self
    {
        $review = new static();
        $review->user_id = $userId;
        $review->vote = $vote;
        $review->text = $text;
        $review->created_at = time();
        $review->status = false;
        return $review;
    }

    public function edit($vote, $text): void
    {
        $this->vote = $vote;
        $this->text = $text;
    }

    public function isIdEqualTo($id)
    {
        return $this->id == $id;
    }

    public function activate()
    {
        $this->status = true;
    }

    public function draft()
    {
        $this->status = false;
    }

    public function isActive()
    {
        return $this->status === true;
    }

    public function getRating(): bool
    {
        return $this->vote;
    }
}