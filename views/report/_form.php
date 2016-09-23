<?php

use yii\bootstrap\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

/* @var $this yii\web\View */
/* @var $model jonmer09\report\models\Report */
/* @var $form kartik\widgets\ActiveForm */
?>

<div class="report-form">

    <?php
    $form = ActiveForm::begin(['enableAjaxValidation' => false, 'id' => 'report-form']);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 4,
        'attributes' => [
            'name' => ['type' => Form::INPUT_TEXT, 'options' => []],
            'query_select' => ['type' => Form::INPUT_TEXTAREA, 'options' => []],
            'query_from' => ['type' => Form::INPUT_TEXTAREA, 'options' => []],
            'query_where' => ['type' => Form::INPUT_TEXTAREA, 'options' => []],
            'permissions' => ['type' => Form::INPUT_WIDGET, 'widgetClass' => kartik\widgets\Select2::className(), 'options' => [
                    'data' => $roles,
                    'options' => ['multiple' => true, 'placeholder' => 'Select...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ]
                ]
            ]
    ]]);
    ?>
    <?= Html::button('Submit', ['type' => 'submit', 'class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); ?>

</div>
