<?php

namespace jonmer09\report\components;

use Yii;
use jonmer09\report\models\Report;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use kartik\helpers\Html;
use kartik\datecontrol\DateControl;
use jonmer09\report\models\Filter;

/**
 * Description of ReportHelper
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 */
class ReportHelper {

    public static $widgetClassList = [
        '\kartik\date\DatePicker' => 'Date',
        '\kartik\time\TimePicker' => 'Time',
        '\kartik\datetime\DateTimePicker' => 'DateTime',
        '\kartik\select2\Select2' => 'Multiple'
    ];

    public static function findAllProvider() {
        $providers = [];
        $models = Report::find()->all();
        foreach ($models as $model) {
            $query = new Query();
            $query->select($model->query_select);
            $query->from($model->query_from);
            $query->where($model->query_where);
            $providers[] = new ArrayDataProvider([
                'allModels' => $query->all(),
                'sort' => [
                    'attributes' => $model->query_columns,
                ],
            ]);
        }
        return $providers;
    }

    /**
     * @return \yii\data\SqlDataProvider
     */
    public static function getProvider(Report $model) {
        $_where = [];
        foreach ($model->queryWhereArray as $cond) {
            if (preg_match_all('/{+(.*?)}/i', $cond, $matches)) {
                $name = $matches[1][0];
                if ($get = self::get("{$model->id}_{$name}")) {
                    $_where[] = strtr($cond, ["{{$name}}" => is_array($get) ? implode('\', \'', $get) : $get]);
                }
            } else {
                $_where[] = $cond;
            }
        }
        $where = implode(' ', $_where);
        $count = $model->getDb()->createCommand("select count(*) from {$model->query_from} where {$where}")->queryScalar();
        $provider = new SqlDataProvider([
            'sql' => "select {$model->query_select} from {$model->query_from} where {$where}",
            'totalCount' => $count,
            'sort' => [
                'attributes' => $model->query_columns,
            ],
        ]);
        return $provider;
    }

    public static function getGridFilters(Report $model) {
        $filters = Html::beginForm(['', 'id' => self::get('id')], 'get')
                . "<div class='report_filter_co'>{items}</div>"
                . "<div class='col-sm-3 row'>"
                . "<div class='row'>&nbsp;</div>"
                . " <input type='submit' value='search' class='btn btn-success'>"
                . "</div>"
                . Html::endForm();

        $items = "";
        foreach ($model->filters as $filter) {
            $class = $filter->widget_class;
            $items .= self::getGridFiltersContent($class, $filter, $model);
        }
        return strtr($filters, ['{items}' => $items]);
    }

    private static function getGridFiltersContent($class, $filter, $model) {
        $content = "<div class='col-sm-3 row'><div class='col-sm-12 row'>{label}</div><div class='col-sm-12 row'>{input}</div></div>";
        $label['{label}'] = Html::label($filter->name);
        if (class_exists($class)) {
            $input['{input}'] = self::getFilterWidget($class, $filter);
        } else {
            $input['{input}'] = Html::textInput($filter->paramName, self::get($filter->paramName), ['class' => 'report_filter', 'id' => "report_filter_$filter->id"]);
        }
        $content = strtr($content, $label);
        $content = strtr($content, $input);
        return $content;
    }

    private static function getFilterWidget($class, $filter) {
        $input = "";
        switch ($class) {
            case '\kartik\date\DatePicker': {
                    $input = DateControl::widget([
                                'id' => "report_filter_$filter->id",
                                'type' => DateControl::FORMAT_DATE,
                                'name' => $filter->paramName,
                                'value' => self::get($filter->paramName),
                                'class' => 'report_filter',
                    ]);
                    break;
                }
            case '\kartik\time\TimePicker': {
                    $input = DateControl::widget([
                                'id' => "report_filter_$filter->id",
                                'type' => DateControl::FORMAT_TIME,
                                'name' => $filter->paramName,
                                'value' => self::get($filter->paramName),
                                'class' => 'report_filter'
                    ]);
                    break;
                }
            case '\kartik\time\DateTimePicker': {
                    $input = DateControl::widget([
                                'id' => "report_filter_$filter->id",
                                'type' => DateControl::FORMAT_DATETIME,
                                'name' => $filter->paramName,
                                'value' => self::get($filter->paramName),
                                'class' => 'report_filter'
                    ]);
                    break;
                }
            case '\kartik\select2\Select2': {
                    $input = $class::widget([
                                'id' => "report_filter_$filter->id",
                                'name' => $filter->paramName,
                                'value' => self::get($filter->paramName),
                                'class' => 'report_filter',
                                'data' => self::getFilterData($filter),
                                'options' => [
                                    'prompt' => '-',
                                    'multiple' => true
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                    ]);
                    break;
                }
            default : {
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

    public static function getRequest() {
        return \Yii::$app->getRequest();
    }

    public static function get($id = null) {
        return self::getRequest()->get($id);
    }

    private static function getFilterData(Filter $filter) {
        $data = \Yii::$app->db->createCommand($filter->widget_class_data)->queryAll();
        return \yii\helpers\ArrayHelper::map($data, 'value', 'text');
    }

    public static function getUserReport() {
        $reports = [];
        foreach (Report::find()->all() as $model) {
            foreach ((array) $model->permissions as $permission) {
                if (Yii::$app->user->can($permission)) {
                    $reports[$model->id] = $model;
                    break;
                }
            }
        }
        return $reports;
    }

}
