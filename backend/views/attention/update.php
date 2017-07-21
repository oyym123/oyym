<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Attention */

$this->title = 'Update Attention: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Attentions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="attention-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
