<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\components\Modal;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model jonmer09\report\models\Report */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-view">
    <p>
        <?=
        Html::a(Yii::t('app', 'Add Filter'), ['filter/create', 'id' => $model->id], [
            'class' => 'btn btn-success',
            'data' => [
                'toggle' => 'modal',
                'target' => '#view-new-modal',
                'title' => 'Filter'
            ]
        ])
        ?>
        <?=
        Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], [
            'class' => 'btn btn-primary'
        ])
        ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'query_select:ntext',
            'query_from:ntext',
            'query_where:ntext',
            'created_at',
        ],
    ])
    ?>
    <div>
        <?=
        GridView::widget([
            'id' => 'detail',
            'dataProvider' => $filterProvider,
            'columns' => [
                'name',
                'widget_class',
                'widget_class_data',
                'created_at',
                [
                    'class' => common\components\ActionColumn::className(),
                    'template' => '{update} {delete}',
                    'controller' => 'filter',
                    'includeJsFunction' => false
                ]
            ]
        ])
        ?>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'view-new-modal',
    'ajax' => true,
]);
Modal::end();
?>