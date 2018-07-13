<?php

namespace common\models\base;

use Yii;
use common\models\Product;
use common\models\User;

/**
 * This is the model class for table "user_favorite_product".
*
    * @property integer $id
    * @property integer $user_id
    * @property integer $product_id
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Product $product
            * @property User $user
    */
class UserFavoriteProductBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'user_favorite_product';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_id', 'product_id'], 'required'],
            [['user_id', 'product_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'user_id' => 'User ID',
    'product_id' => 'Product ID',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProduct()
    {
    return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}