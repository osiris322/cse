<?php

use yii\db\Migration;

/**
* Class m200211_105019_create_table_tag
*/
class m200211_105019_create_table_tag extends Migration
{
  /**
  * {@inheritdoc}
  */
  public function safeUp()
  {
    $this->createTable('{{%tag}}', [
      'id' => $this->primaryKey(),
      'name' => $this->string(255)->notNull(),
    ]);
  }

  /**
  * {@inheritdoc}
  */
  public function safeDown()
  {
    $this->dropTable('{{%tag}}');
  }

  /*
  // Use up()/down() to run migration code without a transaction.
  public function up()
  {

}

public function down()
{
echo "m200211_105019_create_table_tag cannot be reverted.\n";

return false;
}
*/
}
