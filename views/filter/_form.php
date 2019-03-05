<?php

use jonmer09\report\components\FilterHelper;
use jonmer09\report\models\Filter;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Filter */
/* @var $form ActiveForm */
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
            'widget_class' => ['type' => Form::INPUT_DROPDOWN_LIST, 'items' => FilterHelper::$widgetClassList, 'options' => ['prompt' => '-']],
            'widget_class_data' => ['type' => Form::INPUT_TEXTAREA, 'options' => []],
    ]]);
    ?>

    <?= Html::button('Submit', ['type' => 'submit', 'class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>
