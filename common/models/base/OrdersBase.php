<?php

namespace common\models\base;

use Yii;
use common\models\OrderItem;
use common\models\User;

/**
 * This is the model class for table "orders".
*
    * @property integer $id
    * @property integer $user_id
    * @property string $name
    * @property string $email
    * @property integer $card_num
    * @property integer $card_cvc
    * @property integer $card_exp_month
    * @property integer $card_exp_year
    * @property double $paid_amount
    * @property string $created_at
    * @property string $updated_at
    *
            * @property OrderItem[] $orderItems
            * @property User $user
    */
class OrdersBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'orders';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['user_id', 'name', 'email', 'card_num', 'card_cvc', 'card_exp_month', 'card_exp_year', 'paid_amount'], 'required'],
            [['user_id', 'card_num', 'card_cvc', 'card_exp_month', 'card_exp_year'], 'integer'],
            [['paid_amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email'], 'string', 'max' => 255],
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
    'name' => 'Name',
    'email' => 'Email',
    'card_num' => 'Card Num',
    'card_cvc' => 'Card Cvc',
    'card_exp_month' => 'Card Exp Month',
    'card_exp_year' => 'Card Exp Year',
    'paid_amount' => 'Paid Amount',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrderItems()
    {
    return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getUser()
    {
    return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}