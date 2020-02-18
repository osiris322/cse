<?php

use yii\db\Migration;

/**
* Class m200211_070042_create_table_news
*/
class m200211_070042_create_table_news extends Migration
{
  /**
  * {@inheritdoc}
  */
  public function safeUp()
  {
    $this->createTable('{{%news}}', [
      'id' => $this->primaryKey(),
      'slug' => $this->string(255)->notNull(),
      'preview' => $this->string(255)->null(),
      'main_image' => $this->string(255)->null(),
      'created_at' => $this->date()->notNull(),
      'updated_at' => $this->date()->notNull(),
      'header' => $this->string(255)->notNull(),
      'content' => $this->text()->notNull(),
    ]);
  }

  /**
  * {@inheritdoc}
  */
  public function safeDown()
  {
    $this->dropTable('{{%news}}');
  }

  /*
  // Use up()/down() to run migration code without a transaction.
  public function up()
  {

}

public function down()
{
echo "m200211_070042_create_table_news cannot be reverted.\n";

return false;
}
*/
}
