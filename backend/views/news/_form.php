<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(['id' => 'news-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php
    if (isset($model->main_image) && file_exists(Yii::getAlias('@webroot', $model->main_image))) {
        echo Html::img($model->main_image, ['class' => 'img-responsive']);
        echo $form->field($model, 'del_img')->checkBox(['class' => 'span-1']);
    }
    ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?= $form->field($model, 'header')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
