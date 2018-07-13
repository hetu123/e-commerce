<?php

namespace common\models\base;

use Yii;
use common\models\Product;

/**
 * This is the model class for table "category".
*
    * @property integer $id
    * @property string $name
    * @property string $image
    * @property integer $is_active
    * @property integer $pid
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Product[] $products
    */
class CategoryBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'category';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['name'], 'required'],
            [['is_active', 'pid'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'image'], 'string', 'max' => 255],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'name' => 'Name',
    'image' => 'Image',
    'is_active' => 'Is Active',
    'pid' => 'Pid',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProducts()
    {
    return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }
}