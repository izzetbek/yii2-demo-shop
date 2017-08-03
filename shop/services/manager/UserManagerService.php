<?php

namespace shop\services\manager;

use shop\entities\User\User;
use shop\forms\manager\User\UserEditForm;
use shop\repositories\UserRepository;
use shop\forms\manager\User\UserCreateForm;

class UserManagerService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(UserCreateForm $form)
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );
        $this->repository->save($user);
        return $user;
    }

    public function edit($id, UserEditForm $form)
    {
        $user = $this->repository->get($id);
        $user->edit(
            $form->username,
            $form->email
        );
        $this->repository->save($user);
        return $user;
    }
}