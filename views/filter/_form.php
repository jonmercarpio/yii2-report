<?php

use yii\bootstrap\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\builder\Form;
use jonmer09\report\components\ReportHelper;

/* @var $this yii\web\View */
/* @var $model jonmer09\report\models\Filter */
/* @var $form kartik\widgets\ActiveForm */
?>

<div class="filter-form">

    <?php
    $form = ActiveForm::begin(['enableAjaxValidation' => false, 'id' => 'filter-form']);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 4,
        'attributes' => [
            'report_id' => ['type' => Form::INPUT_TEXT, 'options' => []],
            'name' => ['type' => Form::INPUT_TEXT, 'options' => []],
//            'type' => ['type' => Form::INPUT_TEXT, 'options' => []],
            'widget_class' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => ReportHelper::$widgetClassList, 'options' => ['prompt' => '-']],
            'widget_class_data' => ['type' => Form::INPUT_TEXTAREA, 'options' => []],
    ]]);
    ?>

    <?= Html::button('Submit', ['type' => 'submit', 'class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>
