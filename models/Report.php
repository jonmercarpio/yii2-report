<?php

namespace jonmer09\report\models;

use Yii;
use jonmer09\report\components\ReportHelper;

/**
 * Description of Report
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 * 
 * This is the model class for table "{{%report_report}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $query_select
 * @property string $query_from
 * @property string $query_where
 * @property string $permissions
 * @property string $created_at
 *
 * @property ReportFilter[] $reportFilters
 */
class Report extends \yii\db\ActiveRecord {

    public $querySelectArray;
    public $queryFromArray;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%report_report}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'query_select', 'query_from'], 'required'],
            [['query_select', 'query_from', 'query_where'], 'string'],
            [['created_at', 'permissions'], 'safe'],
            [['name'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'query_select' => Yii::t('app', 'Query Select'),
            'query_from' => Yii::t('app', 'Query From'),
            'query_where' => Yii::t('app', 'Query Where'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilters() {
        return $this->hasMany(Filter::className(), ['report_id' => 'id']);
    }

    public function beforeSave($insert) {
        $this->query_select = serialize(explode(',', $this->query_select));
        $this->query_from = serialize(explode(',', $this->query_from));
        $this->permissions = serialize($this->permissions);
        return parent::beforeSave($insert);
    }

    public function afterFind() {
        $this->querySelectArray = unserialize($this->query_select);
        $this->queryFromArray = unserialize($this->query_from);
        $this->query_select = implode(',', $this->querySelectArray);
        $this->query_from = implode(',', $this->queryFromArray);
        $this->permissions = unserialize($this->permissions);
        return parent::afterFind();
    }

    public function getQuery_Columns() {
        $columns = [];
        foreach ($this->querySelectArray as $select) {
            $_s = explode(' ', $select);
            $columns[] = array_pop($_s);
        }
        return $columns;
    }

    /**
     * @return \yii\data\ArrayDataProvider
     */
    public function getProvider() {
        return ReportHelper::getProvider($this);
    }

    public function getGridFilters() {
        return ReportHelper::getGridFilters($this);
    }

    public function getQueryWhereArray() {
        $whereArray = [];
        $preg = preg_split("/[\[\]]/", $this->query_where, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        foreach ($preg as $p) {
            if (preg_match_all('/{+(.*?)}/i', $p, $matches)) {
                $whereArray[$matches[1][0]] = $p;
            } else {
                $whereArray[] = $p;
            }
        }
        return $preg;
    }

    public function getQueryWhereString() {
        return implode(' ', $this->queryWhereArray);
    }

}
