<?php

/**
 * Description of manage
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 */
use kartik\helpers\Html;
use yii\bootstrap\Modal;
?>
<div class="">
    <p>
        <?=
        Html::a('Add New', ['report/create'], [
            'class' => 'btn btn-primary',
            'data' => [
                'toggle' => 'modal',
                'target' => '#new-modal',
                'title' => 'Report'
            ]
        ])
        ?>
    </p>
</div>
<?php
Modal::begin([
    'id' => 'new-modal',
    'header' => '<h2></h2>',
    'size' => Modal::SIZE_LARGE
]);
Modal::end();

$js = <<<EOF
    $(".modal").on("show.bs.modal", function (e) {
        var modal = $(this);
        var link = $(e.relatedTarget);
        modal.find(".modal-body").load(link.attr("href"));
        modal.find(".modal-header > h2").html(link.data('title'));
    });
EOF;
$this->registerJs($js, yii\web\View::POS_END);
?>