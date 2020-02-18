<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Gallery */
/* @var $linkGalleryTag common\models\LinkGalleryTag */

$this->title = 'Create Gallery';
$this->params['breadcrumbs'][] = ['label' => 'Galleries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="gallery-form">

        <?php $form = ActiveForm::begin(['id' => 'gallery-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

        <?php
        if (isset($model->img) && file_exists(Yii::getAlias('@webroot', $model->img))) {
            echo Html::img($model->img, ['class' => 'img-responsive']);
            echo $form->field($model, 'del_img')->checkBox(['class' => 'span-1']);
        }
        ?>

        <?= $form->field($model, 'file')->fileInput() ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($linkGalleryTag, 'id_tag[]')->listBox($linkGalleryTag->getArrayTags(), ['multiple' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
