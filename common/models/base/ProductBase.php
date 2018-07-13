<?php

namespace common\models\base;

use Yii;
use common\models\Cart;
use common\models\Image;
use common\models\Category;
use common\models\Wishlist;

/**
 * This is the model class for table "product".
*
    * @property integer $id
    * @property integer $category_id
    * @property string $name
    * @property double $price
    * @property string $image
    * @property string $description
    * @property string $short_description
    * @property string $brand
    * @property string $size
    * @property integer $wishlist_cnt
    * @property integer $total_stock
    * @property integer $use_stock
    * @property integer $left_stock
    * @property string $created_at
    * @property string $updated_at
    *
            * @property Cart[] $carts
            * @property Image[] $images
            * @property Category $category
            * @property Wishlist[] $wishlists
    */
class ProductBase extends \yii\db\ActiveRecord
{
/**
* @inheritdoc
*/
public static function tableName()
{
return 'product';
}

/**
* @inheritdoc
*/
public function rules()
{
        return [
            [['category_id', 'name', 'price'], 'required'],
            [['category_id', 'wishlist_cnt', 'total_stock', 'use_stock', 'left_stock'], 'integer'],
            [['price'], 'number'],
            [['description', 'short_description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'image', 'brand', 'size'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => 'ID',
    'category_id' => 'Category ID',
    'name' => 'Name',
    'price' => 'Price',
    'image' => 'Image',
    'description' => 'Description',
    'short_description' => 'Short Description',
    'brand' => 'Brand',
    'size' => 'Size',
    'wishlist_cnt' => 'Wishlist Cnt',
    'total_stock' => 'Total Stock',
    'use_stock' => 'Use Stock',
    'left_stock' => 'Left Stock',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCarts()
    {
    return $this->hasMany(Cart::className(), ['product_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getImages()
    {
    return $this->hasMany(Image::className(), ['product_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getCategory()
    {
    return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getWishlists()
    {
    return $this->hasMany(Wishlist::className(), ['product_id' => 'id']);
    }
}