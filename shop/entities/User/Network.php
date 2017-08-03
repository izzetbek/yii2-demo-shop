<?php

namespace shop\entities\User;

use yii\db\ActiveRecord;
use Webmozart\Assert\Assert;
/**
 * This is the model class for table "{{%networks}}".
 *
 * @property integer $user_id
 * @property string $identity
 * @property string $network
 *
 */
class Network extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%networks}}';
    }

    public function isFor($network, $identity): bool
    {
        return $this->network === $network && $this->identity === $identity;
    }

    /**
     * @inheritdoc
     */
    public static function create($network, $identity): self
    {
        Assert::notEmpty($network);
        Assert::notEmpty($identity);

        $item = new static();
        $item->network = $network;
        $item->identity = $identity;

        return $item;
    }
}
