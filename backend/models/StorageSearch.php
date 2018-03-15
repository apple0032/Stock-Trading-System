<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Storage;

/**
 * StorageSearch represents the model behind the search form about `common\models\Storage`.
 */
class StorageSearch extends Storage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'amount', 'type'], 'integer'],
            [['stock_code','stock_string'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Storage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->where(['user_id' => Yii::$app->user->identity->id]);
        $query->andWhere(['!=','amount','0']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            //'id' => $this->id,
            //'user_id' => $this->user_id,
            //'amount' => $this->amount,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'stock_code', $this->stock_code])
            ->andFilterWhere(['like', 'stock_string', $this->stock_string])
            ->andFilterWhere(['like', 'amount', $this->amount]);

        $query->orderBy('type asc');

        return $dataProvider;
    }
}
