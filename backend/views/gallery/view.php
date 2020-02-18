<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Gallery */
/* @var $dataProviderLinks common\models\LinkGalleryTag */
/* @var $linkGalleryTag common\models\LinkGalleryTag */

$this->title = $model->guid;
$this->params['breadcrumbs'][] = ['label' => 'Galleries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="gallery-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->guid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->guid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'guid',
            'img:image',
            'img',
            'description:ntext',
        ],
    ]) ?>
    <p>
        <h3>Tags links:</h3>
    </p>
    <div class="linkGalleryTag-form">

        <?php $form = ActiveForm::begin(); ?>


        <?= $form->field($linkGalleryTag, 'guid_gallery')->hiddenInput(['value'=> $model->guid])->label(false) ?>

        <?= $form->field($linkGalleryTag, 'id_tag')->dropDownList($linkGalleryTag->getArrayTags(),[
        'prompt' => 'Select tag...'
    ]) ?>

            <div class="form-group">
        <?= Html::submitButton('Add tag', ['class' => 'btn btn-success']) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
    <?= ListView::widget([
        'dataProvider' => $dataProviderLinks,
        'itemView' => '_list',
    ]) ?>
</div>
