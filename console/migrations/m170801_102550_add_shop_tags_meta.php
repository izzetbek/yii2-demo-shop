<?php

use yii\db\Migration;

class m170801_102550_add_shop_tags_meta extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_tags}}', 'meta_json', 'JSON NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('{{%shop_tags}}', 'meta_json');
    }
}
