<?php

namespace common\models;

use Yii;
use \yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;


/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $slug
 * @property string $preview
 * @property string $main_image
 * @property int $created_at
 * @property int $update_at
 * @property string|null $header
 * @property string|null $content
 */
class News extends ActiveRecord
{
    public $file;
    public $del_img;
    
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'header',
                // 'slugAttribute' => 'slug',
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['header', 'content'], 'required'],
            [['preview', 'main_image', 'content'], 'string'],
            [['header'], 'string', 'max' => 255],
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
            'id' => 'ID',
            'slug' => 'Slug',
            'preview' => 'Preview',
            'main_image' => 'Main Image',
            'created_at' => 'Created At',
            'updated_at' => 'Update At',
            'header' => 'Header',
            'content' => 'Content',
        ];
    }

}
