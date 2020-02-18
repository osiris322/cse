<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gallery".
 *
 * @property string $guid
 * @property string $img
 * @property string $description
 *
 * @property LinkGalleryTag[] $linkGalleryTags
 * @property Tag[] $tags
 */
class Gallery extends \yii\db\ActiveRecord
{
    public $file;
    public $del_img;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gallery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['guid', 'description'], 'string'],
            [['img'], 'string', 'max' => 255],
            [['guid'], 'unique'],
            [['file'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['del_img'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'guid' => 'Guid',
            'img' => 'Img',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[LinkGalleryTags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinkGalleryTags()
    {
        return $this->hasMany(LinkGalleryTag::className(), ['guid_gallery' => 'guid']);
    }

}
