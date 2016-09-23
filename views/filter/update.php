<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model jonmer09\report\models\Filter */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Filter',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Filters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="filter-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
