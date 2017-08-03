<?php
namespace common\bootstrap;

use shop\repositories\Shop\BrandRepository;
use shop\repositories\Shop\CategoryRepository;
use shop\repositories\Shop\CharacteristicRepository;
use shop\repositories\Shop\TagRepository;
use shop\repositories\UserRepository;
use shop\services\auth\AuthService;
use shop\services\auth\NetworkService;
use shop\services\auth\PasswordResetService;
use shop\services\ContactService;
use shop\services\manager\Shop\BrandManageService;
use shop\services\manager\Shop\CategoryManageService;
use shop\services\manager\Shop\CharacteristicManageService;
use shop\services\manager\Shop\TagManageService;
use shop\services\manager\UserManagerService;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function() use($app) {
            return $app->mailer;
        });

        $container->setSingleton(UserRepository::class, function() {
            return new UserRepository();
        });
        $container->setSingleton(TagRepository::class, function() {
            return new TagRepository();
        });
        $container->setSingleton(BrandRepository::class, function() {
            return new BrandRepository();
        });
        $container->setSingleton(CategoryRepository::class, function() {
            return new BrandRepository();
        });
        $container->setSingleton(CharacteristicRepository::class, function() {
            return new BrandRepository();
        });

        $container->setSingleton(PasswordResetService::class);
        $container->setSingleton(AuthService::class);
        $container->setSingleton(NetworkService::class);
        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail'],
        ]);

        $container->setSingleton(UserManagerService::class);
        $container->setSingleton(TagManageService::class);
        $container->setSingleton(BrandManageService::class);
        $container->setSingleton(CategoryManageService::class);
        $container->setSingleton(CharacteristicManageService::class);
    }
}