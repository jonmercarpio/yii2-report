<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\Modal;

/* @var $this yii\web\View */
/* @var $searchModel jonmer09\report\models\ReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Reports');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?=
        Html::a(Yii::t('app', 'Create Report'), ['create'], [
            'class' => 'btn btn-success',
            'data' => [
                'toggle' => 'modal',
                'target' => '#new-modal',
                'title' => 'Report']
        ])
        ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'query_select',
            'query_from',
            'query_where',
            [
                'class' => yii\grid\ActionColumn::className(),
            ]
        ],
    ]);
    ?>
</div>

<?php
Modal::begin([
    'id' => 'new-modal',
    'ajax' => true,
    'header' => '<h2></h2>',
    'size' => Modal::SIZE_LARGE
]);
Modal::end();
