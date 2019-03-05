<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jonmer09\report\components;

use kartik\grid\GridView as BaseGridView;
use kartik\helpers\Html;
use Yii;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;

/**
 * Description of GridView
 *
 * @author jcarpio
 */
class GridView extends BaseGridView
{

    public $reportId;
    public $hasSession;
    public $panelBeforeTemplate = <<< HTML
    <div class="pull-right">
        <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
            {toolbar}
        </div>    
    </div>
    <div class="clearfix"></div>
        {before}
    <div class="clearfix"></div>
HTML;

    public function init()
    {
        $this->showPageSummary = FilterHelper::getReportFilterData('group', false) ? true : false;

        parent::init();

        $provider = $this->dataProvider;

        Yii::$app->getDb()->cache(function () use ($provider)
        {
            $provider->prepare();
        }, null, ReportHelper::getReportDependency());
    }

    public function renderSessionData()
    {
        $sessions = ReportHelper::getSessionsData($this->reportId);
        $data = json_encode(Yii::$app->getRequest()->getQueryParams());
        $sessionData = "";
        $form = Html::beginForm(['save-report-session'])
                . Html::hiddenInput('ReportSession[id]', $this->reportId)
                . Html::hiddenInput('ReportSession[data]', $data)
                . Html::textInput('ReportSession[name]', '', ['placeholder' => 'Report Name'])
                . Html::endForm();

        $items = ["<li role='presentation' class='dropdown-header'>$form</li>"];

        foreach ($sessions as $k => $s)
        {
            $params = json_decode($s, true);
            array_unshift($params, '');

            $deleteUrl = Url::to(['delete-report-session', 'id' => $this->reportId, 'name' => $k]);
            $url = Url::to($params);

            $items[] = [
                'label' => Html::icon('trash', ['onclick' => "$.post('$deleteUrl')"]) . " $k",
                'url' => $url,
            ];
        }

        $dropdown = ButtonDropdown::widget([
                    'options' => [
                        'class' => 'btn-default'
                    ],
                    'encodeLabel' => false,
                    'label' => 'Reports',
                    'dropdown' => [
                        'items' => $items,
                        'encodeLabels' => false
                    ],
        ]);

        $sessionData .= $dropdown;

        return $sessionData;
    }

    public function initLayout()
    {
        if ($this->hasSession !== false)
        {
            array_unshift($this->toolbar, [
                'content' => $this->renderSessionData()
            ]);
        }
        parent::initLayout();
    }

}
