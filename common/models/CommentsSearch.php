<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Comments;

/**
 * CommentsSearch represents the model behind the search form about `common\models\Comments`.
 */
class CommentsSearch extends Comments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type_id', 'type', 'star', 'sort', 'parent_id', 'like_count', 'comment_count', 'comment_line_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['contents', 'child_ids'], 'safe'],
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
        $query = Comments::find();

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
            'type_id' => $this->type_id,
            'type' => $this->type,
            'star' => $this->star,
            'sort' => $this->sort,
            'parent_id' => $this->parent_id,
            'like_count' => $this->like_count,
            'comment_count' => $this->comment_count,
            'comment_line_id' => $this->comment_line_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'contents', $this->contents])
            ->andFilterWhere(['like', 'child_ids', $this->child_ids]);

        return $dataProvider;
    }
}
