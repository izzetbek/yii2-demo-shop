<?php

namespace shop\forms\manager\User;

use shop\entities\User\User;
use yii\base\Model;

class UserEditForm extends Model
{
    public $username;
    public $email;

    public $_user;

    public function __construct(User  $user, array $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['username', 'email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]]
        ];
    }
}