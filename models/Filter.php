<?php

namespace jonmer09\report\models;

use Yii;

/**
 * Description of Filter
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 * This is the model class for table "{{%report_filter}}".
 *
 * @property integer $id
 * @property integer $report_id
 * @property string $name
 * @property string $paramName
 * @property string $widget_class_data
 * @property string $widget_class
 * @property string $created_at
 *
 * @property Report $report
 */
class Filter extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%report_filter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['report_id', 'name'], 'required'],
            [['report_id'], 'integer'],
            [['created_at', 'widget_class_data'], 'safe'],
            [['name'], 'string', 'max' => 60],
            [['widget_class'], 'string', 'max' => 100],
            [['report_id'], 'exist', 'skipOnError' => true, 'targetClass' => Report::className(), 'targetAttribute' => ['report_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'report_id' => Yii::t('app', 'Report'),
            'name' => Yii::t('app', 'Name'),
            'widget_class' => Yii::t('app', 'Widget Class'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReport() {
        return $this->hasOne(Report::className(), ['id' => 'report_id']);
    }

    public function getParamName() {
        return "{$this->report_id}_{$this->name}";
    }

}
