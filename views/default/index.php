<?php

/**
 * Description of index
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 */
use jonmer09\report\models\Report;
use kartik\helpers\Html;
use yii\web\View;

/* @var $report Report */
/* @var $this View */

$this->title = "Report";
?>
<div class="col-sm-12 panel row">    
    <div class="panel-body">
        <fieldset>
            <legend>
                <h4>
                    System Reports
                </h4>
            </legend>
            <ul class="list-unstyled">
                <?php
                foreach ($models as $model)
                {
                    $a = Html::a("{$model->name}&nbsp;&nbsp;&nbsp;", ['', 'id' => $model->id, '#' => 'go_here'], []);
                    echo Html::tag('li', $a, ['class' => 'pull-left']);
                }
                ?>
            </ul>
        </fieldset>
        <div class="clearfix ">&nbsp;</div>
        <?php
        if ($sessions)
        {
            ?>
            <fieldset>
                <legend>
                    <h4>
                        Custom Reports
                    </h4>
                </legend>
                <ul class="list-unstyled">
                    <?php
                    foreach ($sessions as $k => $v)
                    {
                        $a = Html::a("{$k}&nbsp;&nbsp;&nbsp;", "$v#go_here", []);
                        echo Html::tag('li', "$a", ['class' => 'pull-left']);
                    }
                    ?>    
                </ul>
            </fieldset>
            <?php
        }
        ?>
    </div>
</div>
<div class="col-sm-12 row" id="go_here">
    <?php
    if ($report)
    {
        echo $this->render('_report', ['report' => $report]);
    } else
    {
        ?>
        <div class="row">
            <div class=" text-center">
                <?= Html::img('@web/img/logo.png', ['height' => 400, 'class' => 'media blur']) ?>                    
            </div>    
        </div>
        <?php
    }
    ?>
</div>
<div class="clearfix">
</div>
<style type="text/css">
    img.blur{
        filter: alpha(opacity=0.4);
        opacity: 0.4;
    }
</style>
