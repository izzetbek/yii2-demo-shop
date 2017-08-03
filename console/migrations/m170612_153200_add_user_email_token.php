<?php

use yii\db\Migration;

class m170612_153200_add_user_email_token extends Migration
{
    public function up()
    {
        $this->addColumn('{{%users}}', 'email_confirm_token', $this->string()->unique()->after('email'));
    }

    public function down()
    {
        $this->dropColumn('{{%users}}', 'email_confirm_token');
    }
}
