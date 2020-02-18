<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "linkGalleryTag".
 *
 * @property string $guid_gallery
 * @property int $id_tag
 *
 * @property Gallery $guidGallery
 * @property Tag $tag
 */
class LinkGalleryTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'linkGalleryTag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['guid_gallery', 'id_tag'], 'required'],
            [['guid_gallery'], 'string'],
            [['id_tag'], 'integer'],
            [['guid_gallery', 'id_tag'], 'unique', 'targetAttribute' => ['guid_gallery', 'id_tag']],
            [['guid_gallery'], 'exist', 'skipOnError' => true, 'targetClass' => Gallery::className(), 'targetAttribute' => ['guid_gallery' => 'guid']],
            [['id_tag'], 'exist', 'skipOnError' => true, 'targetClass' => Tag::className(), 'targetAttribute' => ['id_tag' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'guid_gallery' => 'Guid Gallery',
            'id_tag' => 'Id Tag',
        ];
    }

    /**
     * Gets query for [[GuidGallery]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGuidGallery()
    {
        return $this->hasOne(Gallery::className(), ['guid' => 'guid_gallery']);
    }

    /**
     * Gets query for [[Tag]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'id_tag']);
    }
    
    public function getArrayTags()
    {
        $tag = new Tag();
        return $tag->arrayTags();
    }
    
    public static function dataProviderLinks($id)
    {
        $query = LinkGalleryTag::className()::find()->where(['guid_gallery'=>$id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->with('tag');

        return $dataProvider;
    }
}
