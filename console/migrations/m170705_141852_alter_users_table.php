<?php

use yii\db\Migration;

class m170705_141852_alter_users_table extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%users}}', 'username', $this->string()->unique());
        $this->alterColumn('{{%users}}', 'password_hash', $this->string());
        $this->alterColumn('{{%users}}', 'email', $this->string()->unique());
    }

    public function down()
    {
        $this->alterColumn('{{%users}}', 'username', $this->string()->notNull()->unique());
        $this->alterColumn('{{%users}}', 'password_hash', $this->string()->notNull());
        $this->alterColumn('{{%users}}', 'email', $this->string()->notNull()->unique());
    }
}
