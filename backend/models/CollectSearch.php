<?php

namespace backend\models;

use common\models\Bookmark;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Storage;

/**
 * StorageSearch represents the model behind the search form about `common\models\Storage`.
 */
class CollectSearch extends Bookmark
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
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
        $query = Bookmark::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->where(['user_id' => Yii::$app->user->identity->id]);


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
        ]);

        $query->andFilterWhere(['like', 'stock_code', $this->stock_code])
            ->andFilterWhere(['like', 'stock_string', $this->stock_string]);


        $query->orderBy('stock_code asc');

        return $dataProvider;
    }
}
