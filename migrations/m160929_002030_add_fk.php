<?php

use yii\db\Migration;

class m160929_002030_add_fk extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->getDb()->getDriverName() === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->addForeignKey('fk_currency_course', '{{%course}}', 'currency_id', '{{%currency}}', 'id');
    }

}