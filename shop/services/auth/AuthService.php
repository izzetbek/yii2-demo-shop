<?php

namespace shop\services\auth;

use shop\forms\auth\LoginForm;
use shop\repositories\UserRepository;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form)
    {
        $user = $this->users->getByUsernameOrPassword($form->username);
        if(!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new \DomainException('Undefined user.');
        }
        return $user;
    }
}