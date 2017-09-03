<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Video */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="video-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'type_id')->textInput() ?>

    <?= $form->field($model, 'size_type')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <span style="display: none;">
    <?= $form->field($model, 'name')->textarea(['rows' => 6, 'id' => 'name']) ?>
    </span>
    <script src="/js/ueditor/ueditor.config.js"></script>
    <script src="/js/ueditor/ueditor.all.min.js"></script>
    <script src="/js/ueditor/lang/zh-cn/zh-cn.js"></script>

    <label class="control-label" for="intro">内容介绍</label>
    <script id="editor" type="text/plain" style="width:1024px;height:500px;"></script>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">

    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor', {
        autoHeight: true
    });

    ue.ready(function () {
        //设置编辑器的内容
        ue.setContent('<?= $model->name ?>');
        //获取html内容，返回: <p>hello</p>
//        var html = ue.getContent();
        //获取纯文本内容，返回: hello
//        var txt = ue.getContentTxt();
    });

    function setContents() {
        $("#contents").val(ue.getContent());
    }
</script>
