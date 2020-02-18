<?php

use yii\db\Migration;

/**
 * Class m200211_105050_create_table_gallery
 */
class m200211_105050_create_table_gallery extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->createTable('{{%gallery}}', [
        'guid' => 'uuid PRIMARY KEY DEFAULT gen_random_uuid()',
        'img' => $this->string(255)->notNull(),
        'description' => $this->text()->notNull(),
      ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropTable('{{%gallery}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200211_105050_create_table_gallery cannot be reverted.\n";

        return false;
    }
    */
}
