<?php

use kartik\grid\GridView;
use kartik\helpers\Html;

/**
 * Description of index
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 */
?>
<?php

foreach ($models as $model) {
    echo GridView::widget([
        'id' => $model->id,
        'dataProvider' => $model->provider,
        'panel' => [
            'before' => $model->gridFilters
        ],
        'condensed' => true,
        'toolbar' => [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['admin/'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])
            ],
            '{export}',
            '{toggleData}',
        ]
    ]);
}