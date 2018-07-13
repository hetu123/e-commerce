<?php
namespace api\modules\v1\controllers;
use api\modules\v1\models\UploadForm;
use common\models\AppContactUs;
use common\models\AppContent;
use common\models\AppLocalizedString;

use common\models\AppLocalizedStrings;
use common\models\Cart;
use common\models\Category;
use common\models\CommanInfo;
use common\models\Contact_us;
use common\models\ContactUs;
use common\models\Faq;
use common\models\Faqs;
use common\models\Image;
use common\models\News;
use common\models\Order;
use common\models\OrderItem;
use common\models\Orders;
use common\models\Partners;
use common\models\Product;
use common\models\SubCategory;
use common\models\UserCart;
use yii;
use common\models\Region;
use common\models\User;
use yii\web\UploadedFile;
use frontend\models\ResetPasswordForm;
use yii\db\Query;
/**
* Api controller
*/
class  ApiController extends BaseApiController
{
    public function actionAddCategory(){
        $value = $this->_checkAuth();
        if (Yii::$app->request->post()) {
            $model = new UploadForm();
            $category = new Category();
            $category->name = Yii::$app->request->post('name');
            $category->pid = Yii::$app->request->post('pid');
            $model->mainImage = UploadedFile::getInstancesByName('mainImage');
            foreach ($model->mainImage as $file) {
                $filename = $file->baseName . uniqid() . '.' . $file->extension;
                $path = Yii::getAlias('@img') . '/category/';
                $upload = $file->saveAs($path . $filename);
               $path = "http://" . $_SERVER['SERVER_NAME'] . '/e-commerce/uploads/category/';
             //   $path = "http://" . $_SERVER['SERVER_NAME'] . '/uploads/category/';
                $category->image = $path . $filename;
            }
            if ($category->save(false)) {
                $response['status'] = 'true';
                $response['category'] = $category;
                $message = 'Category add successfully';
                $this->_sendResponse($response, $message);
            } else {
                $error_msg = 'Category not add';
                $this->_sendErrorResponse(200, $error_msg, 501);
            }

        }
        else{
            $error_msg = 'Parameter not set';
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
    }
    public function actionGetCategory(){
        // $category = Category::findAll(['pid'=>'0']);
        // print_r($category);die;
        $category = Category::find()->where(['pid'=>NULL])->andWhere(['is_active'=>1])->asArray()->all();
        if($category==NULL){
            $response['status']='false';
            $error_msg = "no records found";
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
        $response['status']='true';
        $response['category'] = $category;
        $message = 'Category list send successfully';
        $this->_sendResponse($response, $message);
    }
    public function actionGetSubCategory(){

        $category = Category::find()->where(['pid'=>Yii::$app->request->get('category_id')])->andWhere(['is_active'=>1])->asArray()->all();
        if($category==NULL){
            $response['status']='false';
            $error_msg = "no records found";
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
        $response['status']='true';
        $response['category'] = $category;
        $message = 'Category list send successfully';
        $this->_sendResponse($response, $message);
    }
    public function actionAddProduct()
    {
        $value = $this->_checkAuth();
        if (Yii::$app->request->post()) {
            $model = new UploadForm();
            $model->imageFiles= UploadedFile::getInstancesByName('imageFiles');

            if($model->imageFiles==NULL){
                $error_msg = 'Plz select image in to Imagefiles';
                $this->_sendErrorResponse(200, $error_msg, 501);

            }

           // $model = new UploadForm();
            $product = new Product();
            $product->category_id = Yii::$app->request->post('category_id');
            $product->name =Yii::$app->request->post('name');
            $product->price =Yii::$app->request->post('price');
            $product->description = Yii::$app->request->post('description');
            $product->short_description = Yii::$app->request->post('short_description');
            $product->total_stock = Yii::$app->request->post('total_stock');
            $product->left_stock = Yii::$app->request->post('total_stock');
            $product->brand =  Yii::$app->request->post('brand');
            $product->size =  Yii::$app->request->post('size');
            $model->mainImage= UploadedFile::getInstancesByName('mainImage');
            foreach ($model->mainImage as $file) {
                $filename = $file->baseName.uniqid().'.'.$file->extension;
                $path = Yii::getAlias('@img') .'/product/';
                $upload=$file->saveAs($path . $filename);
              //  $path = "http://" . $_SERVER['SERVER_NAME'] . '/uploads/product/';
                $path ="http://" .$_SERVER['SERVER_NAME'].'/e-commerce/uploads/product/';
                $product->image= $path . $filename;
                $product->save();
            }
            if($product->save(false))
            {



                foreach ($model->imageFiles as $file) {
                    $filename = $file->baseName.uniqid().'.'.$file->extension;
                    print_r($filename);
                    $path = Yii::getAlias('@img') .'/product/';
                    $upload=$file->saveAs($path . $filename);
                    if($upload == 1){
                    //    $path = "http://" . $_SERVER['SERVER_NAME'] . '/uploads/product/';
                        $path ="http://" .$_SERVER['SERVER_NAME'].'/e-commerce/uploads/product/';
                        $modelImage = new Image();
                        $modelImage->image = $path . $filename;
                        $modelImage->product_id = $product->id;
                        $modelImage->save(false);
                    }
                }
                if ($modelImage->save(false)) {
                    $response['product'] = $product;
                    // $response['ProductImage']=$modelImage;
                    $message = 'Product add successfully';
                    $this->_sendResponse($response, $message);
                }
                else{
                    $error_msg = 'product not add';
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
            }            else{
                $error_msg = 'product not add';
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
        }
        else{
            $error_msg = 'perameter not set';
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
    }
    public function actionGetProduct(){
        if(Yii::$app->request->get()){
            $user_id = Yii::$app->request->get('user_id');
            $product_id = Yii::$app->request->get('product_id');
            $anonymous_identifier = Yii::$app->request->get('anonymous_identifier');
            if($user_id){
                $wishlist = (new Query())
                    ->select('count(id)')
                    ->from('wishlist')
                    ->where(['user_id'=>$user_id])
                    ->andWhere('product_id = p.id');

                $quantity = (new Query())
                    ->select('quantity')
                    ->from('cart')
                    ->where(['user_id'=>$user_id])
                    ->andWhere('product_id = p.id');
            }
            else{
                $wishlist = (new Query())
                    ->select('count(id)')
                    ->from('wishlist')
                    ->where(['anonymous_identifier'=>$anonymous_identifier])
                    ->andWhere('product_id = p.id');

                $quantity = (new Query())
                    ->select('quantity')
                    ->from('cart')
                    ->where(['anonymous_identifier'=>$anonymous_identifier])
                    ->andWhere('product_id = p.id');
            }



               $product = Product::find();
               $product->alias('p');
               $product->select('p.*');
               $product->with('images');
               $product->where(['p.id'=>$product_id]);
               $product->addSelect(['wishlist'=>$wishlist]);
                $product->addSelect(['quantity'=>$quantity]);

           //    $product->leftJoin('cart');
               $product->asArray();
               $result=$product->all();

            if($result==NULL){
                $error_msg = "no records found";
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            $response['product'] = $result;
            $message = 'Product list send successfully';
            $this->_sendResponse($response, $message);
        }
        else{
            $error_msg = 'perameter not set';
            $this->_sendErrorResponse(200, $error_msg, 501);
        }

    }
    public function actionCategoryDetail(){

        if(Yii::$app->request->get()){
            $user_id = Yii::$app->request->get('user_id');
            $category_id =  Yii::$app->request->get('category_id');
            $anonymous_identifier = Yii::$app->request->get('anonymous_identifier');
            $offset = Yii::$app->request->get('offset');
            if($user_id){
                $wishlist = (new Query())
                    ->select('count(id)')
                    ->from('wishlist')
                    ->where(['user_id'=>$user_id])
                    ->andWhere('product_id = p.id');
            }
            else{
                $wishlist = (new Query())
                    ->select('count(id)')
                    ->from('wishlist')
                    ->where(['anonymous_identifier'=>$anonymous_identifier])
                    ->andWhere('product_id = p.id');
            }

            $product = Product::find();
            $product->alias('p');
            $product->select('p.*');
            $product->addSelect(['wishlist'=>$wishlist]);
            $product->where(['category_id'=>$category_id]);
            $product->with('images');
            $product->asArray();
            $product->offset($offset);
            $product->limit(5);
            $result = $product->all();





            if(empty($result)){
                $error_msg = 'record not found';
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            $response['productDetail'] = $result;
            $message = 'Product detail send successfully';
            $this->_sendResponse($response, $message);
        }else{
            $error_msg = 'perameter not set';
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
    }
    public function actionAddStock(){
      //  $value = $this->_checkAuth();
        if (Yii::$app->request->post()) {
            $product= Product::findOne(['id'=>Yii::$app->request->post('id')]);
            if(!empty($product)){
                $product->total_stock+=Yii::$app->request->post('stock');
                $product->left_stock+=Yii::$app->request->post('stock');
                $product->save();
                $response['product'] = $product;
                $message = 'add stock successfully';
            }
            else{
                $error_msg = 'record not found';
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
        }
        else{
            $error_msg = 'parameter not set';
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
        $this->_sendResponse($response, $message);
    }
    public function actionAddCart(){
        if (Yii::$app->request->get()) {
            $product_id = Yii::$app->request->get('product_id');
            $user_id =  Yii::$app->request->get('user_id');
            $anonymous_identifier = Yii::$app->request->get('anonymous_identifier');
            $quantity = Yii::$app->request->get('quantity');

            $product = Product::findOne(['id'=>$product_id]);
            if($product==NULL){
               $error_msg = 'Product Id not metch';
               $this->_sendErrorResponse(200, $error_msg, 501);
            }
            if($product->left_stock < $quantity){
               if($product->left_stock < 0){
                   $error_msg = 'stock not available';
               }
               else{
                   $error_msg = 'You can select more than available stock';
               }
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            if($user_id){
                $result = Cart::findOne(['user_id' =>$user_id,'product_id'=>$product_id]);
                if(!empty($result)){
                    $result->quantity +=$quantity;
                }
                else {
                    $result = new Cart();
                    $result->quantity = $quantity;
                    $result->user_id = $user_id;
                    $result->product_id =$product_id;
                }

                    $result->store_price = $product['price'];
                    $result->discount = 0;
                    $result->total_price = $result->quantity * $product['price'];
                    $result->paid_price = $result->total_price;
                    $result->save(false);
            }
            else{
                $result = Cart::findOne(['anonymous_identifier' => $anonymous_identifier,'product_id'=>$product_id]);
                if(!empty($result)){
                    $result->quantity +=$quantity;
                }
                else {
                    $result = new Cart();
                    $result->quantity = $quantity;
                    $result->anonymous_identifier = $anonymous_identifier;
                    $result->product_id =$product_id;
                }

                $result->store_price = $product['price'];
                $result->discount = 0;
                $result->total_price = $result->quantity * $product['price'];
                $result->paid_price = $result->total_price;
                $result->save(false);
            }
            if($result->save(false)){
                $product->use_stock+=Yii::$app->request->get('quantity');
                $product->left_stock-=Yii::$app->request->get('quantity');
                $product->save(false);
                if($product->save(false)){
                    $response['cart'] = $result;
                    $message = 'successfully add to cart';
                    $this->_sendResponse($response, $message);
                }
            }
            else{
                $error_msg = 'Product not add to cart';
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
        }
        else{
            $error_msg = 'parameter not set';
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
      //  $this->_sendResponse($response, $message);
    }
    public function actionRemoveFromCart(){
        if(Yii::$app->request->post()) {
            if (Yii::$app->request->post('user_id')) {
                $quantity = Cart::findOne(['product_id' => Yii::$app->request->post('product_id'), 'user_id' => Yii::$app->request->post('user_id')]);
                if ($quantity == NULL) {
                    $error_msg = "product all ready remove from cart";
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
                $strore_quantity = $quantity->quantity;
                $cart = Cart::findOne(['product_id' => Yii::$app->request->post('product_id'), 'user_id' => Yii::$app->request->post('user_id')])->delete();
            } else {
                $quantity = Cart::findOne(['anonymous_identifier' => Yii::$app->request->post('anonymous_identifier'), 'user_id' => Yii::$app->request->post('user_id')]);
                if ($quantity == NULL) {
                    $error_msg = "product all ready remove from cart";
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
                $strore_quantity = $quantity->quantity;
                $cart = Cart::findOne(['anonymous_identifier' => Yii::$app->request->post('anonymous_identifier'), 'user_id' => Yii::$app->request->post('user_id')])->delete();
            }
            if ($cart == true) {
                $product = Product::findOne(['id' => Yii::$app->request->post('product_id')]);
                //  print_r($product);die;
                $product->use_stock -= $strore_quantity;
                $product->left_stock += $strore_quantity;
                //  print_r($product->use_stock);die;
                //  $product->save(false);
                if ($product->save(false)) {
                    $response['cart'] = '';
                    $message = 'successfully remove from cart';
                    $this->_sendResponse($response, $message);
                } else {
                    $error_msg = "product not remove";
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
            }
        }
        else{
            $error_msg = 'parameter not set';
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
    }
    public function actionAddQuantity(){
        if(Yii::$app->request->post()) {
            $quantity =Yii::$app->request->post('quantity');
            $product_id = Yii::$app->request->post('product_id');

            $user_id = Yii::$app->request->post('user_id');
            $product = Product::findOne(['id' =>$product_id]);
            $anonymous_identifier = Yii::$app->request->post('anonymous_identifier');
            if ($product->left_stock < $quantity) {
                if ($product->left_stock < 0) {
                    $error_msg = 'stock not available';
                } else {
                    //  $error_msg = 'only '.$product->left_stock.' product available';
                    $error_msg = 'You can select more than available stock';
                }

                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            if ($user_id) {
                $cart = Cart::findOne(['user_id' => $user_id,'product_id'=>$product_id]);
            } else {
                $cart = Cart::findOne(['anonymous_identifier' => $anonymous_identifier ,'product_id'=>$product_id]);
            }
            if ($cart == NULL) {
                $error_msg = "product not match";
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            else{
                $old_quantity = $cart->quantity;
                $cart->quantity = $quantity;
                $cart->total_price = $quantity * $cart->store_price;
                $cart->paid_price = $cart->total_price;
               if($cart->save(false)){
                   $product->use_stock = $product->use_stock + $quantity - $old_quantity;
                   $product->left_stock = $product->left_stock - $quantity + $old_quantity;
                   if ($product->save(false)) {
                       $response['cart'] = $cart;
                       $message = 'successfully add quantity';
                       $this->_sendResponse($response, $message);
                   }
               }
            }
        }
        else{
            $error_msg = 'parameter not set';
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
    }
    public function actionOrder(){
        $value = $this->_checkAuth();
        if (Yii::$app->request->post()) {
            $cart = Cart::findOne(['product_id' => Yii::$app->request->post('product_id')], ['user_id' => Yii::$app->request->post('user_id')]);
            if(empty($cart)){
                $error_msg = 'Plz add product to card';
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            $order =  new Orders();
            $order->user_id = $cart['user_id'];
            $user =  User::findOne(['id'=>$cart['user_id']]);
            $order->name = $user['username'];
            $order->email = $user['email'];
            $order->card_num = Yii::$app->request->post('card_num');
            $order->card_cvc = Yii::$app->request->post('card_cvc');
            $order->card_exp_month = Yii::$app->request->post('card_exp_month');
            $order->card_exp_year = Yii::$app->request->post('card_exp_year');
            $order->paid_amount = Yii::$app->request->post('paid_amount');
            if($order->save(false)){
                $order_item = new OrderItem();
                $order_item->user_id = $cart['user_id'];
                $order_item->order_id = $order->id;
                $order_item->item_name = $cart['product_name'];
                $order_item->item_number = $cart['product_id'];
                $order_item->item_price =$cart['store_price'];
                $order_item->quantity=$cart['quantity'];
                if($order_item->save(false)){

                    $cart = Cart::findOne(['product_id' => Yii::$app->request->post('product_id')], ['user_id' => Yii::$app->request->post('user_id')])->delete();
                    $response['order'] = $order;
                    $response['order_item'] = $order_item;
                    $message = 'successfully order';
                    $this->_sendResponse($response, $message);
                }
            }
        }
        else{
            $error_msg = 'parameter not set';
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
    }
    public function actionForgotPassword(){
        $result="";
        $message="";
        $email=Yii::$app->request->post('email');
        $model = new User();
        $user= User::findOne(['email' => $email]);
        if(empty($user))
        {
            $message = "email does not match";
            $result= "false";
        }
        else
        {
            $password_reset_token=Yii::$app->security->generateRandomString() . '_' . time();
            $user->password_reset_token=$password_reset_token;
            if($user->save(false)){
              //  $user=User::findOne(['email'=>$email]);
                $subject = 'Reset password link';
                $message ="<a href='http://e-commerce/site/reset-password?token=$user->password_reset_token'>http://e-commerce/site/reset-password?token=$user->password_reset_token</a>";
                if ($model->sendEmail1($email,$subject,$message))
                {
                    $result = "true";
                    $message = "mail send successfully";
                }
                else
                {
                    $result = "false";
                    $message = "mail not send";
                }
            }
        }
        $response['mailResponse'] = $result;
        $this->_sendResponse($response, $message);
    }


}