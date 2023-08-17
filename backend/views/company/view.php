<?php

use common\models\Enum;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Company $model */

$this->title = $model->company_name;
$this->params['breadcrumbs'][] = ['label' => 'Company', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Upload Company Images', ['company/upload-images', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'company_name',
            'address:ntext',
            'company_email:email',
            'contact_number',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    $userStatuses = Enum::GENERAL_STATUS_ARRAY;
                    return !empty($userStatuses[$model->status]) ? $userStatuses[$model->status] : "";
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return date("dS F, Y  h:i A", $model->created_at);
                }
            ],
        ],
    ]) ?>
    
    <h1><?= Html::encode($model->company_name) ?></h1>

    <h2>Images</h2>
    <div class="container image-gallery">
        <div class="row">
            <?php foreach ($model->companyImages as $image) { ?>
                <div class="col-md-3">
                        <?= Html::img(
                            '/' . Yii::getAlias('@baseDir/common/' . Enum::UPLOAD_COMPANY_IMAGES . $image->image_name), [
                                'alt' => 'Image', 
                                'width' => "100%",
                                'class' => 'modelOpen',
                                'data-toggle' => 'modal',
                                'data-target' => '#imageModal',
                            ]
                        ); ?>
                        <a class="btn btn-danger mt-2 removeImage" data-company-id="<?= $image->company_id; ?>" href="">Remove</a>
                </div>
            <?php } ?>
        </div>

    </div>

    <?php Modal::begin([
        'id' => "imageModal",
        'size' => Modal::SIZE_LARGE,
        'title' => 'Image Preview',
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => false], // Prevent closing on backdrop click or keyboard interaction
    ]);
    
    echo '<div id="image-content"></div>';

    Modal::end(); ?>


</div>

<?php
$js = <<<JS
    $(document).on('click', '.modelOpen', function() {
        var imageUrl = $(this).attr('src');
        $('#image-content').html('<img src="' + imageUrl + '" class="img-fluid">');
    });
JS;

$this->registerJs($js, View::POS_READY);
?>