<?php

namespace common\models\base;

use Yii;
use common\models\User;
use common\models\Orders;

/**
 * This is the model class for table "order_item".
*
    * @property integer $id
    * @property integer $user_id
    * @property integer $order_id
    * @property string $item_name
    * @property integer $item_number
    * @property integer $item_price
    * @property integer $quantity
    * @property string $created_at
    * @property string $updated_at
    *
            * @property User $user
            * @property Orders $order
    */
class OrderItemBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'order_item';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_id', 'order_id', 'item_name', 'item_number', 'item_price', 'quantity'], 'required'],
            [['user_id', 'order_id', 'item_number', 'item_price', 'quantity'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
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
    'order_id' => 'Order ID',
    'item_name' => 'Item Name',
    'item_number' => 'Item Number',
    'item_price' => 'Item Price',
    'quantity' => 'Quantity',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrder()
    {
    return $this->hasOne(Orders::className(), ['id' => 'order_id']);
    }
}