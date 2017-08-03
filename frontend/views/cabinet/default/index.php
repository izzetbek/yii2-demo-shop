<?php

/* @var $this yii\web\View */

$this->title = 'My cabinet';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Hi, there!</h1>
    </div>

    <h2>Attach socials</h2>
    <?= yii\authclient\widgets\AuthChoice::widget([
        'baseAuthUrl' => ['cabinet/network/attach']
    ]); ?>
</div>
