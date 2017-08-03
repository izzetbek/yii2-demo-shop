<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_networks`.
 */
class m170704_161151_create_user_networks_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = "CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB";

        $this->createTable('{{%networks}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'identity' => $this->string()->notNull(),
            'network' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-user_networks-identity-name}}', '{{%networks}}', ['identity', 'network'], true);
        $this->createIndex('{{%idx-user_networks-user_id}}', '{{%networks}}', 'user_id');

        $this->addForeignKey('{{%idx-user_networks-user_id}}', '{{%networks}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%networks}}');
    }
}
