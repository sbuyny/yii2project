<?php

use yii\db\Migration;

class m170208_085438_create_licence_log extends Migration
{
    public function up()
    {
         $this->createTable('{{%licence_log}}', [
            'id' => $this->primaryKey(),
            'broker_id' => $this->integer(),
            'owner_id' => $this->integer(),
            'procent' => $this->integer(),
            'price' => $this->integer(),
            'licence_number' => $this->string(),
            'documents_file' => $this->string(),
            'date_register' => $this->integer(),
            'date_start' => $this->integer(),
            'date_finish' => $this->integer(),
            'licence_id' => $this->integer(),
            'date_changed' => $this->integer(),
            'description' => $this->text(),
        ]);
        $this->batchInsert('{{%config}}', [ 'key', 'value', 'description', 'category'], [
            ['licence_price', '50', 'Стоимость лицензии брокера', 'finance'],
            ['consolidator_price', '150', 'Стоимость лицензии консолидатора', 'finance'],
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%licence_log}}');
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
