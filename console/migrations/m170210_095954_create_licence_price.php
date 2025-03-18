<?php

use yii\db\Migration;

class m170210_095954_create_licence_price extends Migration
{
    public function up()
    {
          $this->createTable('{{%licence_price}}', [
            'id' => $this->primaryKey(),
            'minimal_number_licences' => $this->integer(),
            'maximum_number_licences' => $this->integer(),
            'price' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%licence_price}}');
    }
}
