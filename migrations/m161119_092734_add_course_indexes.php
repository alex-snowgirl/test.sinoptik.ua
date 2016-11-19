<?php

use yii\db\Migration;

class m161119_092734_add_course_indexes extends Migration
{
    public function up()
    {
        $this->createIndex('ix_search', '{{%course}}', ['bank_id', 'currency_id', 'pub_date']);
    }

    public function down()
    {
        $this->dropIndex('ix_search', '{{%course}}');

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
