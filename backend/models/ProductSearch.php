<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;
use kartik\daterange\DateRangeBehavior;
/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
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
            [['id', 'total_stock', 'use_stock', 'left_stock'], 'integer'],
            [['name', 'category_id', 'sub_category_id', 'image', 'description', 'short_description', 'created_at', 'updated_at'], 'safe'],
            [['price'], 'number'],
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
        $query = Product::find();
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
        $createTimeEnd="";
        $createTimeStart="";
        if(!empty($this->createTimeStart)){
            $createTimeStart=date('Y-m-d h:i A', $this->createTimeStart);
            $createTimeEnd=date('Y-m-d h:i A', $this->createTimeEnd);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
          //  'category_id' => $this->category_id,
          //  'sub_category_id' => $this->sub_category_id,
            'price' => $this->price,

            'total_stock' => $this->total_stock,
            'use_stock' => $this->use_stock,
            'left_stock' => $this->left_stock,
           // 'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        $query->joinWith(['category as c']);
        $query->andFilterWhere(['like','c.name',$this->category_id]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'short_description', $this->short_description]);
        $query->andFilterWhere(['between', 'product.created_at', $createTimeStart, $createTimeEnd]);
        return $dataProvider;
    }
}
