<?php

namespace jonmer09\report\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use jonmer09\report\models\Filter;

/**
 * FilterSearch represents the model behind the search form about `jonmer09\report\models\Filter`.
 */
class FilterSearch extends Filter {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'report_id'], 'integer'],
            [['name', 'widget_class', 'created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Filter::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'report_id' => $this->report_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'widget_class', $this->widget_class])
                ->andFilterWhere(['like', 'widget_class', $this->widget_class_data]);

        return $dataProvider;
    }

}
