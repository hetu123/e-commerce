<?php

namespace common\models\base;

use Yii;
use common\models\Product;

/**
 * This is the model class for table "image".
*
    * @property integer $id
    * @property integer $product_id
    * @property string $image
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Product $product
    */
class ImageBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'image';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['product_id'], 'required'],
            [['product_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['image'], 'string', 'max' => 255],
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
    'product_id' => 'Product ID',
    'image' => 'Image',
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
}