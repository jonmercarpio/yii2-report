<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model jonmer09\report\models\Filter */

$this->title = Yii::t('app', 'Create Filter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Filters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filter-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
