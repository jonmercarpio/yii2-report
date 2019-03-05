<?php

namespace jonmer09\report\components;

use jonmer09\report\models\Report;
use kartik\grid\GridView;
use kartik\helpers\Html;
use kartik\widgets\Select2;
use Yii;
use yii\caching\TagDependency;
use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\helpers\Url;

/**
 * Description of ReportHelper
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 */
class ReportHelper extends BaseHelper
{

    public static $session_key = 'ReportSession';
    public static $report_cache_dependency_key = 'cache_report';
    public static $report_session_cache_dependency_key = 'cache_report_session';
    private static $gridColumnsName;
    private static $combinedGridColumnsName;
    private static $where;
    private static $providers;
    private static $pjaxContainerId;
    public static $randomPjaxContainerId = false;
    public static $report_class = 'jonmer09\report\models\Report';

    public static function findAllProvider()
    {
        $providers = [];
        $models = Report::find()->all();
        foreach ($models as $model)
        {
            $query = new Query();
            $query->select($model->query_select);
            $query->from($model->query_from);
            $query->where($model->query_where);
            $providers[] = new ArrayDataProvider([
                'allModels' => $query->all(),
                'sort' => [
                    'attributes' => $model->query_columns,
                ],
                'pagination' => [
                    'pageSize' => 40
                ]
            ]);
        }
        return $providers;
    }

    /**
     * @return SqlDataProvider
     */
    public static function getProvider(Report $model)
    {
        if (isset(self::$providers[$model->id]))
        {
            return self::$providers[$model->id];
        }

        $where = self::getWhereProvider($model);
        $select = self::getSelectProvider($model);
        $count = self::getCount($model);
        $sort = self::getSortProvider($model);

        $sql = "select {$select} from {$model->query_from} where {$where}";

        $provider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'sort' => $sort,
            'pagination' => [
                'pageSize' => 40
            ]
        ]);

        self::$providers[$model->id] = $provider;

        return $provider;
    }

    public static function getIsValid(Report $model)
    {
        
    }

    public static function getItems($model)
    {
        return FilterHelper::getItems($model);
    }

    public static function getAggregates($model)
    {
        $_aggregates = ""
                . "<div class='btn-group'>{avg}</div>"
                . "<div class='btn-group'>{count}</div>"
                . "<div class='btn-group'>{max}</div>"
                . "<div class='btn-group'>{min}</div>"
                . "<div class='btn-group'>{sum}</div>"
                . "<div class='btn-group'>{view}</div>"
                . "<div class='btn-group col-sm-3 row'>{group}</div>"
                . "<div class='btn-group'>{search}</div>"
                . "";

        $aggregates = strtr($_aggregates, [
            '{group}' => self::getAggregateButtonGroup($model, 'group', 'Group', true),
            '{view}' => self::getAggregateButtonGroup($model, 'view', 'View'),
            '{sum}' => self::getAggregateButtonGroup($model, 'sum', 'Sum'),
            '{avg}' => self::getAggregateButtonGroup($model, 'avg', 'Average'),
            '{max}' => self::getAggregateButtonGroup($model, 'max', 'Max'),
            '{min}' => self::getAggregateButtonGroup($model, 'min', 'Min'),
            '{count}' => self::getAggregateButtonGroup($model, 'count', 'Count'),
            '{search}' => "<input type='submit' value='search' class='btn btn-success'>"
        ]);

        return $aggregates;
    }

    public static function getGridFilters(Report $model)
    {
        $_params = self::get();
        $url = array_merge([''], $_params);

        $filters = "<div class='report-form col-sm-12 row'>"
                . Html::beginForm($url, 'get', ['data' => ['pjax' => true]])
                . "<div class='btn-toolbar kv-grid-toolbar row' role='toolbar'>{aggregates}</div>"
                . "<div class='row'>&nbsp;</div>"
                . "<div class='btn-toolbar kv-grid-toolbar row report_filter_co' role='toolbar'>{items}</div>"
                . Html::endForm()
                . "</div>";

        $items = static::getItems($model);
        $aggregates = static::getAggregates($model);

        return strtr($filters, ['{items}' => $items, '{aggregates}' => $aggregates]);
    }

    public static function getAggregateButtonGroup(Report $model, $name, $displayName, $select2 = false)
    {
        $aggregatesList = [];
        $n = FilterHelper::getReportFilterData($name, []);

        $data = $model->gridColumnsName;
        if ($select2)
        {
            $data = self::getCombinedGridColumnsName($model);
            self::moveElements($data, $n);
            $data = array_combine($data, $data);
            return Select2::widget([
                        'value' => $n,
                        'name' => "ReportFilter[$name]",
                        'data' => $data,
                        'showToggleAll' => false,
                        'options' => ['multiple' => true, 'placeholder' => "Select $displayName"],
                        'pluginOptions' => [
                            'maximumSelectionLength' => 2
                        ],
                        'pluginEvents' => [
                            "select2:select" => "function(evt) { 
                                console.log(this);
                                var element = evt.params.data.element;
                                var \$element = $(element);
                                \$element.detach();
                                $(this).append(\$element);
                                $(this).trigger('change');
                                    }",
                        ],
                        'size' => Select2::SMALL,
                        'pjaxContainerId' => static::getPjaxContainerId()
            ]);
        } else
        {
            foreach ($data as $key => $value)
            {
                $aggregatesList[] = Html::a(Html::checkbox("ReportFilter[$name][{$key}]", key_exists($key, $n), ['value' => $value]) . " $value", null, [
                            'class' => "small",
                            'tabIndex' => "-1"
                ]);
            }
            $ul = Html::ul($aggregatesList, ['class' => 'dropdown-menu', 'encode' => false]);

            $_a = '<div class="button-group">'
                    . '<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">'
                    . $displayName . '<span class="caret"></span>'
                    . '</button>'
                    . '{ul}'
                    . '</div>';
            return strtr($_a, ['{ul}' => $ul]);
        }
    }

    static function getColumnsName(Report $model)
    {
        $m = ReportHelper::getFirstModel($model);
        return $m ? array_keys($m) : [];
    }

    public static function getGridColumnsName(Report $model)
    {
        if (self::$gridColumnsName == null)
        {
            $data = $model->gridColumnsName;
            self::moveElements($data, self::getGroupColumnsFilter());
            self::$gridColumnsName = $data;
        }
        return self::$gridColumnsName;
    }

    public static function getGridColumns(Report $model)
    {
        $subGroupOf = null;
        $view = self::getSelectColumnsProvider($model);
        $avg = FilterHelper::getReportFilterData('avg');
        $count = FilterHelper::getReportFilterData('count');
        $max = FilterHelper::getReportFilterData('max');
        $min = FilterHelper::getReportFilterData('min');
        $sum = FilterHelper::getReportFilterData('sum');
        $group = FilterHelper::getReportFilterData('group');

        $headers = self::getCombinedGridColumnsName($model);
        $_headers = $model->gridColumnsName;

        $s = [
            GridView::F_AVG => $avg,
            GridView::F_COUNT => $count,
            GridView::F_MAX => $max,
            GridView::F_MIN => $min,
            GridView::F_SUM => $sum
        ];

        self::moveElements($headers, $group);

        foreach ($group as $k => $v)
        {
            $groupFooter = null;
            $attribute = $headers[$k];

            if ($sum || $avg || $max || $min || $count)
            {
                $groupFooter = function ($model, $key, $index, $widget) use ($sum, $avg, $max, $min, $count, $attribute, $k, $_headers, $headers)
                {
                    $r = [
                        'content' => [$k => "Summary( " . $model[$attribute] . " )"],
                        'options' => ['class' => 'success', 'style' => 'font-weight:bold;']
                    ];

                    self::setAggregateToGroup($r, $avg, GridView::F_AVG, $_headers, $headers);
                    self::setAggregateToGroup($r, $count, GridView::F_COUNT, $_headers, $headers);
                    self::setAggregateToGroup($r, $max, GridView::F_MAX, $_headers, $headers);
                    self::setAggregateToGroup($r, $min, GridView::F_MIN, $_headers, $headers);
                    self::setAggregateToGroup($r, $sum, GridView::F_SUM, $_headers, $headers);

                    return $r;
                };
            }

            $headers[$k] = [
                'attribute' => $attribute,
                'group' => true,
                'groupFooter' => $groupFooter,
                'subGroupOf' => $subGroupOf,
            ];

            $subGroupOf = $k;
        }

        $new_headers = array_filter($headers, function($var) use ($view)
        {
            $attr = is_array($var) ? $var['attribute'] : $var;
            return in_array($attr, $view);
        });

        $headers = $new_headers ? $new_headers : $headers;

        foreach ($s as $s_k => $s_v)
        {
            foreach ($s_v as $_s_v)
            {
                $headers[$_s_v] = [
                    'attribute' => $_s_v,
                    'pageSummary' => true,
                    'pageSummaryFunc' => $s_k,
                    'format' => ['decimal', 1]
                ];
            }
        }

        return $headers;
    }

    public static function getUserReportSessionUrl()
    {
        $reports = static::getUserReport();
        $urls = [];
        foreach ($reports as $model)
        {
            $sessions = static::getSessionsData($model->id);
            foreach ($sessions as $k => $s)
            {
                $params = json_decode($s, true);
                array_unshift($params, '');
                $urls[$k] = Url::to($params);
            }
        }
        return $urls;
    }

    /**
     * @return []
     */
    public static function getUserReport()
    {
        $reports = [];
        foreach (static::getReports() as $model)
        {
            foreach ((array) $model->permissions as $permission)
            {
                if (Yii::$app->user->can($permission))
                {
                    $reports[$model->id] = $model;
                    break;
                }
            }
        }
        return $reports;
    }

    protected static function getReports()
    {
        return Report::find()->all();
    }

    public static function getGroupColumnsFilter()
    {
        return FilterHelper::getReportFilterData('group');
    }

    public static function getWhereProvider(Report $model)
    {
        if (self::$where)
        {
            return self::$where;
        }

        $_where = [];

        foreach ($model->queryWhereArray as $cond)
        {
            if (preg_match_all('/{+(.*?)}/i', $cond, $matches))
            {
                $name = $matches[1][0];
                if ($get = self::get("{$model->id}_{$name}"))
                {
                    $_where[] = strtr($cond, ["{{$name}}" => is_array($get) ? implode('\', \'', $get) : $get]);
                }
            } else
            {
                $_where[] = $cond;
            }
        }
        self::$where = implode(' ', $_where);
        return self::$where;
    }

    static function getNewIndexFromHeaderFilter($a, $_headers, $headers)
    {
        return array_search($_headers[$a], array_values($headers));
    }

    static function setAggregateToGroup(&$group, $data, $aggregate, $_headers, $headers)
    {
        foreach ($data as $a => $v)
        {
            $b = self::getNewIndexFromHeaderFilter($a, $_headers, $headers);
            $group['content'][$b] = $aggregate;
            $group['contentFormats'][$b] = [
                'decimals' => 1,
                'format' => 'number'
            ];
        }
    }

    static function getFirstModel(Report $model)
    {
        $m = $model->getDb()->createCommand("select {$model->query_select} from {$model->query_from} limit 1")->cache()->queryOne();
        return $m;
    }

    static function getCount(Report $model)
    {
        $where = self::getWhereProvider($model);
        $sql = "select count(*) from {$model->query_from} where {$where}";
        $count = $model->getDb()->createCommand($sql)->cache()->queryScalar();
        return $count;
    }

    static function getSelectColumnsProvider($model)
    {
        $v = FilterHelper::getReportFilterData('view');
        if (!$v)
        {
            return [];
        }

        $g = FilterHelper::getReportFilterData('group');
        array_walk($g, function(&$value, $key, $columns)
        {
            $value = $columns[$value];
        }, self::getCombinedGridColumnsName($model));
        $v = array_diff($v, $g);
        array_splice($v, 0, 0, $g);
        return $v;
    }

    static function getSelectProvider(Report $model)
    {
        $v = FilterHelper::getReportFilterData('view');
        $g = FilterHelper::getReportFilterData('group');
        $select = $model->query_select;
        if ($v)
        {
            $v = self::getSelectColumnsProvider($model);
            array_walk($v, function(&$value)
            {
                $value = "`$value`";
            });
            $select = implode(',', $v);
        }
        return $select;
    }

    public static function getOrderProvider(Report $model)
    {
        $order = "";
        $o = self::get('sort');
        $v = FilterHelper::getReportFilterData('view');
        $g = FilterHelper::getReportFilterData('group');
        $where = self::getWhereProvider($model);
        $columns = $model->gridColumnsName;

        $g = $v ? array_flip($g) : $g;

        if (!preg_match('/order by/i', $where) && $g)
        {
            array_walk($g, function(&$v) use ($columns)
            {
                $v = $columns[$v];
            });
            $order .= " order by " . implode(',', $g);
        }
        return $order;
    }

    public static function getDefaultOrder(Report $model)
    {
        $order = [];
        $g = FilterHelper::getReportFilterData('group');
        foreach ($g as $v)
        {
            $order[self::getCombinedGridColumnsName($model)[$v]] = SORT_ASC;
        }
        return $order;
    }

    static function getCombinedGridColumnsName(Report $model)
    {
        if (self::$combinedGridColumnsName)
        {
            return self::$combinedGridColumnsName;
        }
        self::$combinedGridColumnsName = array_combine($model->gridColumnsName, $model->gridColumnsName);
        return self::$combinedGridColumnsName;
    }

    static function getSessionsData($id)
    {
        $cache = Yii::$app->cache;
        $r = $cache->get(self::$session_key . $id);
        return $r ?: [];
    }

    static function getFromReport($model, $plus = '')
    {
        $matches = [];
        preg_match('/([a-zA-Z0-9_$#-`]*\.?\s?)/i', $model->query_from, $matches);
        $table_name = trim(str_replace('`', '', $matches[1])) . $plus . " ";
        return str_replace($matches[1], $table_name, $model->query_from);
    }

    static function invalidateCache()
    {
        TagDependency::invalidate(Yii::$app->cache, ReportHelper::$report_cache_dependency_key);
        TagDependency::invalidate(Yii::$app->cacheFrontend, ReportHelper::$report_cache_dependency_key);
    }

    static function getReportDependency()
    {
        return new TagDependency(['tags' => static::$report_cache_dependency_key]);
    }

    static function getReportSessionDependency()
    {
        return new TagDependency(['tags' => static::$report_session_cache_dependency_key]);
    }

    public static function getSortProvider($model)
    {
        $v = self::getSelectColumnsProvider($model);
        $where = self::getWhereProvider($model);
        if (!preg_match('/order by/i', $where))
        {
            return [
                'attributes' => $v ? $v : $model->gridColumnsName,
                'defaultOrder' => self::getDefaultOrder($model),
                'enableMultiSort' => true
            ];
        } else
        {
            return [];
        }
    }

    public static function getPjaxContainerId()
    {
        if (static::$pjaxContainerId === null)
        {
            static::$pjaxContainerId = static::$randomPjaxContainerId ? 'pjax-' . time() : 'pjax-unique-id';
        }
        return static::$pjaxContainerId;
    }

}
