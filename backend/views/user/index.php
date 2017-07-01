<?php

use common\models\Base;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserYearSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '会员管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php

    echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>

        <?php
        echo $this->render('limit');
        ?>

    <p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                [
                    'label' => '头像',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::img(($info = $model->info) ? $info->photoUrl() . '?imageView2/2/w/25' : '');
                    },
                ],

                [
                    'label' => '用户地区',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return ($info = $model->info) ? ($info->userArea ? $info->userArea->name : '') : '';
                    },
                    'filter' => Html::dropDownList('province', Yii::$app->request->get('province'), ['' => 'All'] + \common\models\City::province(), ['class' => 'form-control'])
                ],
                'username',
//             'email:email',
                [
//                'attribute' => 'status',
                    'label' => '是否有效',
                    'value' => function ($model) {
                        return $model->getStatus();
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'status', ['' => 'All'] + $searchModel->status(), ['class' => 'form-control']),
                ],
                [
                    'label' => '手机类型',
                    'value' => function ($model) {

                        return ($info = $model->info) ? $info->phoneSystem() : '';
                    },
                    'filter' => Html::dropDownList('phone_system', Yii::$app->request->get('phone_system'), ['' => 'All'] + Base::$phoneSystem, ['class' => 'form-control'])
                ],

                [
                    'attribute' => 'created_at',
                    'label' => '注册时间',
                    'value' => function ($model) {
                        return date('Y-m-d H:i:s', $model->created_at);
                    },
                ],
                /*
           [
               'label'=>'更新日期',
               'format' => ['date', 'php:Y-m-d H:i:s'],
               'value' => 'updated_at'
           ],

           [
               'class' => 'yii\grid\ActionColumn',
               'header' => '操作',
               'options' => ['width' => '100px;'],
               'template' => '{view} {update} {area}',
               'buttons' => [
                   'area' => function ($url, $model) {
                       return Html::a('<span class="glyphicon glyphicon-list"></span>', $url, [
                           'title' => Yii::t('app', 'Area'),
                       ]);
                   }
               ],
               'urlCreator' => function ($action, $model, $key, $index) {
                   if ($action === 'view') {
                       return ['view', 'id' => $model->id];
                   } else if ($action === 'update') {
                       return ['update', 'id' => $model->id];
                   } else if ($action === 'area') {
                       return ['area/index', 'group_id' => $model->id];
                   }
               }
           ],
           // 'updated_at',
           [
               'class' => 'yii\grid\ActionColumn',
               'header' => '操作',
               'template' => '{export} {use}',
               'buttons' => [
                   'export' => function ($url, $model) {
                       if($model->status==20){
                           return Html::a('导出 Excel', $url, [
                               'class' => 'btn btn-success',
                           ]);
                       }
                       return null;
                   },
                   'use' => function ($url, $model) {
                       if($model->status==10){
                           return Html::a('投入使用', $url, [
                               'class' => 'btn btn-info',
                               'data' => [
                                   'confirm' => "确认要投入使用吗？",
                                   'method' => 'post',
                               ],
                           ]);
                       }
                       return null;
                   },
               ],
           ],*/

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

</div>

