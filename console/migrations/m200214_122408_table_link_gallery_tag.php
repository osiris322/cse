<?php

use yii\db\Migration;

/**
 * Class m200214_122408_table_link_gallary_tag
 */
class m200214_122408_table_link_gallery_tag extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%linkGalleryTag}}', [
        //'id' =>  $this->primaryKey(),
        'guid_gallery' => 'UUID NOT NULL',
        'id_tag' => $this->integer()->notNull(),
      ]);
      $this->addPrimaryKey('gallary-tag_pk', 'linkGalleryTag', ['guid_gallery', 'id_tag']);
      $this->createIndex('idx-linkGalleryTag-guid_gallery', 'linkGalleryTag', 'guid_gallery');
      $this->addForeignKey('fk-linkGalleryTag-guid_gallery','linkGalleryTag', 'guid_gallery', 'gallery', 'guid', 'CASCADE' );
      $this->createIndex('idx-linkGalleryTag-id_tag', 'linkGalleryTag', 'id_tag');
      $this->addForeignKey('fk-linkGalleryTag-id_tag', 'linkGalleryTag', 'id_tag', 'tag', 'id', 'CASCADE' );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
      $this->dropForeignKey('fk-linkGalleryTag-guid_gallery', 'linkGalleryTag');
      $this->dropIndex('idx-linkGalleryTag-guid_gallery', 'linkGalleryTag');
      $this->dropForeignKey('fk-linkGalleryTag-id_tag', 'linkGalleryTag');
      $this->dropIndex('idx-linkGalleryTag-id_tag', 'linkGalleryTag');
      $this->dropTable('{{%linkGalleryTag}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200214_122408_table_link_gallary_tag cannot be reverted.\n";

        return false;
    }
    */
}
