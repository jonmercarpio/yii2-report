<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jonmer09\report\components;

use kartik\datecontrol\DateControl;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Description of FilterHelper
 *
 * @author jcarpio
 */
class FilterHelper extends BaseHelper
{

    public static $widgetClassList = [
        '\kartik\date\DatePicker' => 'Date',
        '\kartik\time\TimePicker' => 'Time',
        '\kartik\datetime\DateTimePicker' => 'DateTime',
        '\kartik\select2\Select2' => 'Multiple',
        '\kartik\daterange\DateRangePicker' => 'Date Range'
    ];
    public static $filtersContentTemplate = <<< HTML
        <div class='col-sm-{size} row'>
            <div class='col-sm-12 row'>{label}</div>
            <div class='col-sm-12 row'>{input}</div>
        </div>
HTML;

    public static function getFilterData($filter)
    {
        $data = Yii::$app->db->createCommand($filter->widget_class_data)->cache()->queryAll();
        return ArrayHelper::map($data, 'value', 'text');
    }

    public static function getReportFilterData($name, $default = [])
    {
        return isset(self::get('ReportFilter')[$name]) ? self::get('ReportFilter')[$name] : $default;
    }

    public static function getFilterWidget($class, $filter)
    {
        $input = "";
        if (!class_exists($class))
        {
            return Html::textInput($filter->paramName, self::get($filter->paramName), [
                        'class' => 'report_filter', 'id' => "report_filter_$filter->id"]);
        }

        switch ($class)
        {
            case '\kartik\date\DatePicker':
                {
                    $input = DateControl::widget([
                                'id' => "report_filter_$filter->id",
                                'type' => DateControl::FORMAT_DATE,
                                'name' => $filter->paramName,
                                'value' => self::get($filter->paramName),
                                'class' => 'report_filter',
                                'widgetOptions' => [
                                    'pjaxContainerId' => ReportHelper::getPjaxContainerId()
                                ]
                    ]);
                    break;
                }
            case '\kartik\time\TimePicker':
                {
                    $input = DateControl::widget([
                                'id' => "report_filter_$filter->id",
                                'type' => DateControl::FORMAT_TIME,
                                'name' => $filter->paramName,
                                'value' => self::get($filter->paramName),
                                'class' => 'report_filter',
                                'widgetOptions' => [
                                    'pjaxContainerId' => ReportHelper::getPjaxContainerId()
                                ]
                    ]);
                    break;
                }
            case '\kartik\datetime\DateTimePicker':
                {
                    $input = DateControl::widget([
                                'id' => "report_filter_$filter->id",
                                'type' => DateControl::FORMAT_DATETIME,
                                'name' => $filter->paramName,
                                'value' => self::get($filter->paramName),
                                'class' => 'report_filter',
                                'widgetOptions' => [
                                    'pjaxContainerId' => ReportHelper::getPjaxContainerId()
                                ]
                    ]);
                    break;
                }
            case '\kartik\select2\Select2':
                {
                    $input = $class::widget([
                                'id' => "report_filter_$filter->id",
                                'name' => $filter->paramName,
                                'value' => self::get($filter->paramName),
                                'class' => 'report_filter',
                                'data' => static::getFilterData($filter),
                                'options' => [
                                    'prompt' => "Select -",
                                    'multiple' => true,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                'size' => Select2::SMALL,
                                'pjaxContainerId' => ReportHelper::getPjaxContainerId()
                    ]);
                    break;
                }
            case '\kartik\daterange\DateRangePicker':
                {
                    $input = DateRangePicker::widget([
                                'pjaxContainerId' => 'unique-pjax-id',
                                'id' => "report_filter_$filter->id",
                                'name' => $filter->paramName,
                                'presetDropdown' => true,
                                'hideInput' => true,
                                'value' => self::get($filter->paramName, ""),
                                'class' => 'report_filter',
                                'convertFormat' => true,
                                'startAttribute' => "{$filter->paramName}_start",
                                'endAttribute' => "{$filter->paramName}_end",
                                'startInputOptions' => ['value' => date("Y-m-d", strtotime("-1 days"))],
                                'endInputOptions' => ['value' => date("Y-m-d")],
                                'pluginOptions' => [
                                    'locale' => ['format' => 'Y-m-d'],
                                ]
                    ]);
                    break;
                }
            default :
                {
                    $input = $class::widget([
                                'id' => "report_filter_$filter->id",
                                'name' => $filter->paramName,
                                'value' => self::get($filter->paramName),
                                'class' => 'report_filter'
                    ]);
                }
        }
        return $input;
    }

    public static function getItems($model)
    {
        $items = "";
        foreach ($model->filters as $filter)
        {
            $class = $filter->widget_class;
            $items .= self::getFiltersContent($class, $filter, $model);
        }
        return $items;
    }

    public static function getFiltersContent($class, $filter, $model)
    {
        $content = static::$filtersContentTemplate;

        $opt['{label}'] = Html::label($filter->name);
        $opt['{size}'] = self::getFilterWidgetSize($class, $filter);
        $opt['{input}'] = self::getFilterWidget($class, $filter);
        return strtr($content, $opt);
    }

    public static function getFilterWidgetSize($class, $filter)
    {
        switch ($class)
        {
            case '\kartik\daterange\DateRangePicker':
                {
                    return 4;
                }
            default :
                {
                    return 3;
                }
        }
    }

}
