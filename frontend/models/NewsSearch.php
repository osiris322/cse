<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\News;

/**
 * NewsSearch represents the model behind the search form of `common\models\News`.
 */
class NewsSearch extends News {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [[ 'header'], 'string'],
            [[ 'created_at'], 'date','format' => 'php:Y-m-d'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function search($params) {
        $query = News::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
                'attributes' => [
                    'header',
                    'created_at',
                    ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
         $query->andFilterWhere([
          //'id' => $this->id,
          'created_at' => $this->created_at,
          ]); 

        $query->andFilterWhere(['ilike', 'header', $this->header]);

        return $dataProvider;
    }

}
