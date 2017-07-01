<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\UserYearSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search  form-inline">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="form-group form-inline left">
        <?= DatePicker::widget([
            'id' => 'start_date',
            'name' => 'start_date',//当没有设置model时和attribute时必须设置name
            'language' => 'zh-CN',
            'size' => 'ms',
            'value' => Yii::$app->request->get('start_date'),
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true,
            ],
        ]); ?>
        至
        <?= DatePicker::widget([
            'id' => 'end_date',
            'name' => 'end_date',
            'value' => Yii::$app->request->get('end_date'),
            'language' => 'zh-CN',
            'size' => 'ms',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true
            ]
        ]); ?>


        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset', ['/users'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
