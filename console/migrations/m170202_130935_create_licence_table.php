<?php

use yii\db\Migration;

/**
 * Handles the creation of table `licence`.
 */
class m170202_130935_create_licence_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%licence}}', [
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
        ]);
        $this->addColumn('{{%user}}', 'passport', $this->string());
        $this->addColumn('{{%user}}', 'inn', $this->string());
        $this->addColumn('{{%user}}', 'date_consolidator', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%licence}}');
        $this->dropColumn('{{%user}}', 'passport', $this->string());
        $this->dropColumn('{{%user}}', 'inn', $this->string());
    }
}
