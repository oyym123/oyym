<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserAddress;

/**
 * UserAddressSearch represents the model behind the search form about `common\models\UserAddress`.
 */
class UserAddressSearch extends UserAddress
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'province_id', 'city_id', 'street_id', 'area_id', 'default_address', 'status', 'created_at', 'updated_at'], 'integer'],
            [['user_name', 'lng', 'lat', 'postal', 'telephone', 'detail_address', 'str_address'], 'safe'],
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
        $query = UserAddress::find();

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
            'user_id' => $this->user_id,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'default_address' => $this->default_address,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'lng', $this->lng])
            ->andFilterWhere(['like', 'lat', $this->lat])
            ->andFilterWhere(['like', 'postal', $this->postal])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'detail_address', $this->detail_address])
            ->andFilterWhere(['like', 'str_address', $this->str_address]);

        return $dataProvider;
    }
}
