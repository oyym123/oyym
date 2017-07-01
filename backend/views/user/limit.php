<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\UserYearSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search  form-inline ">

    <?php $form = ActiveForm::begin([
        'action' => ['export'],
        'method' => 'post',
    ]); ?>

    <div class="pull-right ">
        <input type="text" name="limitStart" size="10" placeholder="用户起始ID" class="input-ms form-control"/> 至
        <input type="text" name="limitEnd" size="10" placeholder="结束ID" class="input-ms form-control"/>
        <?= Html::submitButton(Yii::t('app', '导出用户到xls'), ['class' => 'btn btn-success',]) ?>
    </div>

    
    <br>
</div>

<?php ActiveForm::end(); ?>

</div>
