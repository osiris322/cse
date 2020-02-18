<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\GallerySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Galleries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Gallery', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'guid',
            'img',
            [
            'attribute'=>'linkGalleryTags.tag.name',
            'value'=>function($model, $key, $index){ 
                $res='';
                    foreach ( $model->linkGalleryTags as $k=>$v ){
                                $res .= Html::tag('div',$v->tag->name);
                    }                                                                                                                            
                return $res;
                },                                                                                                                                   
             'format'=>'raw'
             ],
            'description:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
