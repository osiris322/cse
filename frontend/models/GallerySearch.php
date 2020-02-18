<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Gallery;

/**
 * GallerySearch represents the model behind the search form of `common\models\Gallery`.
 */
class GallerySearch extends Gallery
{
    public function attributes() {
        return array_merge(parent::attributes(), ['linkGalleryTags.tag.name']);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['linkGalleryTags.tag.name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Gallery::find();
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);
        $query->joinWith('linkGalleryTags');
        $query->joinWith('linkGalleryTags.tag');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'tag.name', $this->getAttribute('linkGalleryTags.tag.name')]);

        return $dataProvider;
    }
}
