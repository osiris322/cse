<?php
    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
?>
    <div class="tag-item"> 
        <?= HtmlPurifier::process($model->tag->name) ?>
        <?= Html::a('Delete', ['delete-tag', 'guidGallery' => $model->guid_gallery, 'idTag' => $model->id_tag], [
            'class' => 'btn btn-link',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
