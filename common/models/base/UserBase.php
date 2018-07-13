<?php

namespace common\models\base;

use Yii;
use common\models\Cart;
use common\models\OrderItem;
use common\models\Orders;
use common\models\Wishlist;

/**
 * This is the model class for table "user".
*
    * @property integer $id
    * @property string $username
    * @property string $firstname
    * @property string $lastname
    * @property string $phonenumber
    * @property string $email
    * @property string $access_token
    * @property string $activate_token
    * @property string $auth_key
    * @property string $password_hash
    * @property string $password_reset_token
    * @property integer $google_account_id
    * @property integer $phonenumber_signin_id
    * @property integer $status
    * @property string $Brand
    * @property string $sixe
    * @property string $profile_pic
    * @property integer $created_at
    * @property integer $updated_at
    *
            * @property Cart[] $carts
            * @property OrderItem[] $orderItems
            * @property Orders[] $orders
            * @property Wishlist[] $wishlists
    */
class UserBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'user';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['auth_key', 'created_at', 'updated_at'], 'required'],
            [['google_account_id', 'phonenumber_signin_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'firstname', 'lastname', 'phonenumber', 'email', 'password_hash', 'password_reset_token', 'Brand', 'sixe', 'profile_pic'], 'string', 'max' => 255],
            [['access_token', 'activate_token'], 'string', 'max' => 64],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['phonenumber'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'username' => 'Username',
    'firstname' => 'Firstname',
    'lastname' => 'Lastname',
    'phonenumber' => 'Phonenumber',
    'email' => 'Email',
    'access_token' => 'Access Token',
    'activate_token' => 'Activate Token',
    'auth_key' => 'Auth Key',
    'password_hash' => 'Password Hash',
    'password_reset_token' => 'Password Reset Token',
    'google_account_id' => 'Google Account ID',
    'phonenumber_signin_id' => 'Phonenumber Signin ID',
    'status' => 'Status',
    'Brand' => 'Brand',
    'sixe' => 'Sixe',
    'profile_pic' => 'Profile Pic',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCarts()
    {
    return $this->hasMany(Cart::className(), ['user_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrderItems()
    {
    return $this->hasMany(OrderItem::className(), ['user_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getOrders()
    {
    return $this->hasMany(Orders::className(), ['user_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getWishlists()
    {
    return $this->hasMany(Wishlist::className(), ['user_id' => 'id']);
    }
}