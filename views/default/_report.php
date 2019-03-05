<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use jonmer09\report\components\GridView;
use kartik\grid\GridGroupAsset;
use jonmer09\report\models\Report;

/* @var $report Report */
/* @var $this View */

$columns = array_values($report->gridColumns);
?>

<style type="text/css">
    .kv-panel-before > div{
        z-index: 2;
    }
    .kv-panel-before .report-form{
        margin-top: -35px;
        z-index: 1;
        position: unset;
    }
</style>
<?=

GridView::widget([
    'id' => $report->gridID,
    'reportId' => $report->id,
    'dataProvider' => $report->provider,
    'hasSession' => isset($hasSession) ? $hasSession : true,
    'panel' => [
        'before' => $report->gridFilters,
        'heading' => "<h3 class='panel-title'>{$report->name}</h3>",
    ],
    'condensed' => true,
    'showPageSummary' => false,
    'pjax' => isset($pjax) ? $pjax : true,
    'pjaxSettings' => [
        'neverTimeout' => true,
        'formSelector' => false,
        'options' => [
            'id' => $report->pjaxContainerId,
            'enableReplaceState' => false,
            'enablePushState' => false
        ],
    ],
    'striped' => true,
    'hover' => true,
    'columns' => $columns
])
?>
<?php

GridGroupAsset::register($this);
