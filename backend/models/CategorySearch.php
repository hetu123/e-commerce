<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Category;
use kartik\daterange\DateRangeBehavior;
/**
/**
 * CategorySearch represents the model behind the search form of `common\models\Category`.
 */
class CategorySearch extends Category
{
    public $createTimeRange;
    public $createTimeStart;
    public $createTimeEnd;
   
    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'createTimeRange',
                'dateStartAttribute' => 'createTimeStart',
                'dateEndAttribute' => 'createTimeEnd',
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createTimeRange'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
            [['id', 'is_active'], 'integer'],
            [['name', 'image', 'created_at', 'updated_at'], 'safe'],
            [['createTimeStart','createTimeEnd'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Category::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $createTimeEnd="";
        $createTimeStart="";
        if(!empty($this->createTimeStart)){
            $createTimeStart=date('Y-m-d h:i A', $this->createTimeStart);
            $createTimeEnd=date('Y-m-d h:i A', $this->createTimeEnd);
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'is_active' => $this->is_active,
           // 'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'image', $this->image]);
        $query->andFilterWhere(['between', 'category.created_at', $createTimeStart, $createTimeEnd]);
        return $dataProvider;
    }


}
