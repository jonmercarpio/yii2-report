<?php

/**
 * Description of index
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 */
use kartik\grid\GridView;
use kartik\helpers\Html;

/* @var $report jonmer09\report\models\Report */
/* @var $this yii\web\View */

$this->title = "Report";
?>
<div class="col-sm-12 panel">
    <div class="panel-body">
        <ul class="list-unstyled">
            <?php
            foreach ($models as $model) {
                $a = Html::a("{$model->name}&nbsp;&nbsp;&nbsp;", ['', 'id' => $model->id], []);
                echo Html::tag('li', $a, ['class' => 'pull-left']);
            }
            ?>
        </ul>
    </div>
</div>
<div class="col-sm-12 row">
    <?php
    if ($report) {
        echo GridView::widget([
            'id' => $report->id,
            'dataProvider' => $report->provider,
            'panel' => [
                'before' => $report->gridFilters,
                'heading' => "<h3 class='panel-title'>{$report->name}</h3>",
            ],
            'condensed' => true
        ]);
    }
    ?>
</div>
<div class="clearfix">
</div>