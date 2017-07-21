<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Attention */

$this->title = 'Create Attention';
$this->params['breadcrumbs'][] = ['label' => 'Attentions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attention-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
