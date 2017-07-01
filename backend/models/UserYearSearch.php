<?php

namespace app\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserYearSearch represents the model behind the search form about `common\models\User`.
 */
class UserYearSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
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
        $query = User::find();

        $query->joinWith('info');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($params['start_date'])) {
            if (!empty($params['end_date']) && $params['start_date'] > $params['end_date']) {
                Yii::$app->session->setFlash('error', '开始时间不能大学结束时间');
                return $dataProvider;
            }
            $query->andWhere(['>', 'user.created_at', strtotime($params['start_date'])]);
        }

        if (!empty($params['end_date'])) {
            $query->andWhere(['<=', 'user.created_at', strtotime($params['end_date']) + 86400]);
        }

        $query->andFilterWhere([
            'user.id' => $this->id,
            'status' => $this->status,
            'user_info.phone_system' => Yii::$app->request->get('phone_system'),
            'user_info.province' => Yii::$app->request->get('province'),
            'user_info.ident' => Yii::$app->request->get('ident'),
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email]);

        $query->orderBy('id desc');

        return $dataProvider;
    }
}
