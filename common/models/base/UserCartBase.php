<?php

namespace common\models\base;

use Yii;
use common\models\Cart;
use common\models\Product;

/**
 * This is the model class for table "user_cart".
*
    * @property integer $id
    * @property integer $cart_id
    * @property integer $product_id
    * @property integer $quantity
    * @property double $store_price
    * @property double $total_price
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Cart $cart
            * @property Product $product
    */
class UserCartBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'user_cart';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['cart_id', 'product_id', 'quantity', 'store_price', 'total_price'], 'required'],
            [['cart_id', 'product_id', 'quantity'], 'integer'],
            [['store_price', 'total_price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['cart_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::className(), 'targetAttribute' => ['cart_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'cart_id' => 'Cart ID',
    'product_id' => 'Product ID',
    'quantity' => 'Quantity',
    'store_price' => 'Store Price',
    'total_price' => 'Total Price',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCart()
    {
    return $this->hasOne(Cart::className(), ['id' => 'cart_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getProduct()
    {
    return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}