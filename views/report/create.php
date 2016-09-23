<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model jonmer09\report\models\Report */

$this->title = Yii::t('app', 'Create Report');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'roles' => $roles
    ])
    ?>
</div>
