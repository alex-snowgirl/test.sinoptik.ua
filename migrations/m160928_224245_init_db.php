<?php

use yii\db\Migration;

class m160928_224245_init_db extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->getDb()->getDriverName() === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bank}}',
            [
                'id' => $this->primaryKey(11),
                'name' => $this->string()->notNull(),
                'code' => $this->string()->notNull(),
            ]
            ,$tableOptions);

        $this->createIndex('bank_code_idx', '{{%bank}}', 'code', true);


        $this->createTable('{{%currency}}',
            [
                'id' => $this->primaryKey(11),
                'name' => $this->string()->notNull(),
                'code' => $this->string()->notNull(),
            ]
            ,$tableOptions);

        $this->createTable('{{%course}}',
            [
                'id' => $this->primaryKey(11),
                'bank_id' => $this->integer(11)->notNull(),
                'currency_id' => $this->integer(11)->notNull(),
                'sell' => $this->float(5)->notNull(),
                'buy' => $this->float(5)->notNull(),
                'pub_date' => $this->timestamp(),
            ]
            ,$tableOptions);

        $this->addForeignKey('fk_bank_course', '{{%course}}', 'bank_id', '{{%bank}}', 'id');
    }

}