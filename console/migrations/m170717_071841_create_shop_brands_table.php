<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_brands`.
 */
class m170717_071841_create_shop_brands_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%shop_brands}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'meta_json' => 'JSON NOT NULL',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%shop_brands}}');
    }
}
