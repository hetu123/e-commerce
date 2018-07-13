<?php
/**
 * Created by PhpStorm.
 * User: Hetal
 * Date: 2018-06-04
 * Time: 02:46 PM
 */

namespace api\modules\v1\controllers;
use api\modules\v1\models\UploadForm;
use common\models\Orders;
use common\models\UserCart;
use yii\db\Query;
use yii\web\UploadedFile;
use common\models\Cart;
use common\models\Product;
use common\models\UserFavoriteProduct;
use common\models\Wishlist;
use common\models\User;
use Yii;

class UserController extends BaseApiController
{
    public function actionSignup()
    {
        if (Yii::$app->request->post()) {
            $firstname =  Yii::$app->request->post('firstname');
            $lastname  =  Yii::$app->request->post('lastname');
            $password = Yii::$app->request->post('password');
            $email = Yii::$app->request->post('email');
            $username=Yii::$app->request->post('username');
            $phonenumber = Yii::$app->request->post('phonenumber');
            $auth_key= '';
            if(isset( $_SERVER['HTTP_AUTH_TOKEN'])){
                $auth_key = $_SERVER['HTTP_AUTH_TOKEN'];
            }

                $user = User::findIdentityByAuthKey($auth_key);
            if(empty($user)){
                $useremail = User::findOne(['email' => $email]);
                $user_username =  User::findOne(['username' => $username]);
                $user_phonenumber =  User::findOne(['phonenumber' => $phonenumber]);
                if(!empty($useremail)){

                    if(!empty($useremail->google_account_id)){
                        $response['token'] = $useremail->auth_key;
                        $response['user'] = $this->_userData($useremail);
                        $message = 'you allready signup with google account';
                     //   $error_msg = "you allready signup with google account";
                      //  $this->_sendErrorResponse(200, $error_msg, 501);
                    }
                    else{
                        $error_msg = "email id allready exist";
                        $this->_sendErrorResponse(200, $error_msg, 501);
                    }

                }
                elseif (!empty($user_username)){
                    $error_msg = "Username allready exist";
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
                elseif (!empty($user_phonenumber)){
                    if(!empty($user_phonenumber->phonenumber_signin_id)){
                        $response['token'] = $user_phonenumber->auth_key;
                        $response['user'] = $this->_userData($user_phonenumber);
                        $message = 'you allready signup with phonenumber';
                        $this->_sendResponse($response, $message);
                       // $error_msg = "you allready signup with phonenumber";
                       // $this->_sendErrorResponse(200, $error_msg, 501);
                    }
                    else{
                        $error_msg = "Phonenumber allready exist";
                        $this->_sendErrorResponse(200, $error_msg, 501);
                    }

                }
                else{
                    $user = new User();
                    // $user->access_token = Yii::$app->security->generateRandomString();
                    $user->email = $email;
                    $user->auth_key = Yii::$app->security->generateRandomString();
                    $user->password_hash =Yii::$app->security->generatePasswordHash($password);
                    $user->username = $username;
                    $user->phonenumber=$phonenumber;
                    $user->status = '0';
                    $user->created_at = date('Y-m-d H:i:s');
                }
            }

                $model = new UploadForm();
                if( $firstname){
                    $user->firstname = $firstname;
                }
                if($lastname){
                    $user->lastname = $lastname;
                }

                $user->updated_at = date('Y-m-d H:i:s');
                $model->mainImage= UploadedFile::getInstancesByName('mainImage');
                 if(!empty($model->mainImage)){
                     foreach ($model->mainImage as $file) {
                         $filename = $file->baseName.uniqid().'.'.$file->extension;
                         $path = Yii::getAlias('@img') .'/profile/';
                         $upload=$file->saveAs($path . $filename);
                       //  $path = "http://" . $_SERVER['SERVER_NAME'] . '/uploads/profile/';
                         $path ="http://" .$_SERVER['SERVER_NAME'].'/e-commerce/uploads/profile/';
                         $user->profile_pic = $path . $filename;
                         $user->save(false);
                     }
                 }
                 $user->save(false);
                if ($user->save(false)) {
                    $activate_token=Yii::$app->security->generateRandomString() . '_' . time();
                    $user->activate_token=$activate_token;
                    if($user->save(false)){
                        $subject = 'activation link';
                      //  $message ="<a href='http://e-commerce/user/active?token=$user->activate_token'>http://e-commerce/user/active?token=$user->activate_token</a>";
                        $message ="<a href='http://e-commerce/api/web/v1/user/active?token=$user->activate_token'>Activation Link</a>";
                        $user->sendEmail1($user->email, $subject,$message);
                    }
                    // $cart = Cart::findAll(['anonymous_identifier'=>Yii::$app->request->post('anonymous_identifier')]);
                    $carts = Cart::findAll(['anonymous_identifier'=>Yii::$app->request->post('anonymous_identifier')]);
                    if(!empty($carts)){
                        foreach ($carts as $cart) {
                            $cart->user_id = $user->id;
                            $cart->anonymous_identifier = NULL;
                            $cart->save(false);
                        }
                    }
                    $whishlists = Wishlist::findAll(['anonymous_identifier'=>Yii::$app->request->post('anonymous_identifier')]);
                    if(!empty($whishlists)){
                        foreach ($whishlists as $whishlist) {
                            $whishlist->user_id = $user->id;
                            $whishlist->anonymous_identifier = NULL;
                            $whishlist->save(false);

                        }
                    }
                    $response['token'] = $user->auth_key;
                    $response['user'] = $this->_userData($user);
                    $message = 'sussefully register. you have to confirm mail id';
                }

        }
        else{
            $error_msg = "parameter not set";
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
        $this->_sendResponse($response, $message);
    }
    public function actionSigninWithGoogle(){
        if (Yii::$app->request->post()) {
            $email = Yii::$app->request->post('email');
            $username = Yii::$app->request->post('username');
            $google_account_id = Yii::$app->request->post('google_account_id');
            $user = User::findOne(['email'=>$email]);
          // print_r($user);die;
            if(!empty($user)){
                $response['token'] = $user->auth_key;
                $response['user'] = $this->_userData($user);
                $message = 'successfully login with google account';
                $this->_sendResponse($response, $message);
            }
            else{
                $user = new User();
                $user->email = $email;
                $user->username = $username;
                $user->auth_key = Yii::$app->security->generateRandomString();
                $user->google_account_id=$google_account_id;
                $user->status = 1;
               // $model = new UploadForm();
                /*$model->mainImage= UploadedFile::getInstancesByName('mainImage');

                if(!empty($model->mainImage)){
                    foreach ($model->mainImage as $file) {
                        $filename = $file->baseName.uniqid().'.'.$file->extension;
                        $path = Yii::getAlias('@img') .'/profile/';
                        $upload=$file->saveAs($path . $filename);
                        //  $path = "http://" . $_SERVER['SERVER_NAME'] . '/uploads/profile/';
                        $path ="http://" .$_SERVER['SERVER_NAME'].'/e-commerce/uploads/profile/';
                        $user->profile_pic = $path . $filename;

                        $user->save(false);
                    }
                }*/
                $user->save(false);

            }
            $response['token'] = $user->auth_key;
            $response['user'] = $this->_userData($user);
            $message = 'successfully signup with google acount';
            $this->_sendResponse($response, $message);
        }
        else{
            $error_msg = "parameter not set";
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
    }
    public function actionSigninWithPhonenumber(){
        if (Yii::$app->request->post()) {

            $phonenumber = Yii::$app->request->post('phonenumber');
            $user = User::findOne(['phonenumber'=>$phonenumber]);
            // print_r($user);die;
            if(!empty($user)){
                $response['token'] = $user->auth_key;
                $response['user'] = $this->_userData($user);
                $message = 'successfully login with phonenumber';
                $this->_sendResponse($response, $message);
            }
            else{
                $user = new User();
                $user->phonenumber = $phonenumber;
                $user->phonenumber_signin_id =uniqid();
                //$user->username = $username;
                $user->auth_key = Yii::$app->security->generateRandomString();
                $user->status = 1;
               /* $model = new UploadForm();
                $model->mainImage= UploadedFile::getInstancesByName('mainImage');

                if(!empty($model->mainImage)){
                    foreach ($model->mainImage as $file) {
                        $filename = $file->baseName.uniqid().'.'.$file->extension;
                        $path = Yii::getAlias('@img') .'/profile/';
                        $upload=$file->saveAs($path . $filename);
                        //  $path = "http://" . $_SERVER['SERVER_NAME'] . '/uploads/profile/';
                        $path ="http://" .$_SERVER['SERVER_NAME'].'/e-commerce/uploads/profile/';
                        $user->profile_pic = $path . $filename;

                        $user->save(false);
                    }
                }*/
                $user->save(false);

            }
            $response['token'] = $user->auth_key;
            $response['user'] = $this->_userData($user);
            $message = 'successfully signup with google acount';
            $this->_sendResponse($response, $message);
        }
        else{
            $error_msg = "parameter not set";
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
    }
    public function actionLogin(){
        if (Yii::$app->request->post()) {
            $email = Yii::$app->request->post('email');
            $phonenumber =  Yii::$app->request->post('phonenumber');
            $username =  Yii::$app->request->post('username');
            if($username){
                $result = User::findOne(['username' => $username]);
            }
            if($email){
                $result = User::findOne(['email' => $email]);
                if(!empty($result->google_account_id)){
                    $response['token'] = $result->auth_key;
                    $response['user'] = $this->_userData($result);
                    $message = 'successfully Login';
                    $this->_sendResponse($response, $message);
                }
            }
            if($phonenumber){
                $result = User::findOne(['phonenumber' => $phonenumber]);
                if(!empty($result->phonenumber_signin_id)){
                    $response['token'] = $result->auth_key;
                    $response['user'] = $this->_userData($result);
                    $message = 'successfully Login';
                    $this->_sendResponse($response, $message);
                }
            }


            if(empty($result)){
                $error_msg = "wrong username,email or phonnumber address";
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            else{
                /*if($result->status==0){
                    $error_msg = 'You have to confirm you account';
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }*/
                $validate= Yii::$app->security->validatePassword(Yii::$app->request->post('password'), $result->password_hash);
                if($validate==1){
                    $carts = Cart::findAll(['anonymous_identifier'=>Yii::$app->request->post('anonymous_identifier')]);
                    if(!empty($carts)){
                        foreach ($carts as $cart) {
                            $test = Cart::findOne(['product_id'=>$cart->product_id,'user_id'=>$result->id]);
                            if(!empty($test)){
                                $test->user_id = $result->id;
                                $test->quantity+=$cart->quantity;
                                $test->total_price = $test->quantity * $test->store_price;
                                $test->anonymous_identifier = NULL;
                                $test->save(false);
                                $cart->delete();
                            }
                            else{
                                $cart->user_id = $result->id;
                                $cart->anonymous_identifier = NULL;
                                $cart->save(false);
                            }
                        }
                    }
                    $wishlists = Wishlist::findAll(['anonymous_identifier'=>Yii::$app->request->post('anonymous_identifier')]);
                    if(!empty($wishlists)){
                        foreach ( $wishlists as  $wishlist) {
                            $test = Wishlist::findOne(['product_id'=> $wishlist->product_id,'user_id'=>$result->id]);
                            if(!empty($test)){
                                $test->user_id = $result->id;
                                $test->anonymous_identifier = NULL;
                                $test->save(false);
                                $wishlist->delete();
                            }
                            else{
                                $wishlist->user_id = $result->id;
                                $wishlist->anonymous_identifier = NULL;
                                $wishlist->save(false);
                            }
                        }
                    }
                    $response['token'] = $result->auth_key;
                    $response['user'] = $this->_userData($result);
                    $message = 'successfully Login';
                    $this->_sendResponse($response, $message);
                }
                else{
                    $error_msg = "wrong password";
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
            }
        }
        else{
            $error_msg = "parameter not set";
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
    }
    public function actionLogout(){
        $value = $this->_checkAuth();
        $response['user'] = '';
        $message = "user logged out successfully";
        $this->_sendResponse($response, $message);
    }
    public function actionAddWishlist(){
        if (Yii::$app->request->get('user_id')) {
           // $session = Yii::$app->session;
            $product_id = Yii::$app->request->get('product_id');
            $user_id = Yii::$app->request->get('user_id');
            if (Wishlist::findOne(['product_id' => $product_id])) {
                Wishlist::findOne(['product_id' => $product_id], ['user_id' => $user_id])->delete();
                $product=Product::findOne(['id'=>$product_id]);
                $product->wishlist_cnt -= '1';
                if($product->save(false)){
                    $error_msg = "remove from wishlist";
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
            }
            else {
                $wishlist = new Wishlist();
                $wishlist->user_id = $user_id;
                $wishlist->product_id = $product_id;
                if ($wishlist->save(false)) {
                    $product=Product::findOne(['id'=>$product_id]);
                    $product->wishlist_cnt += '1';
                    if($product->save(false)){
                        $message = "add to wishlist";
                        $response['wishlist'] = $wishlist;
                    }
                }
                else {
                    $error_msg = "something went wrong";
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
            }
        }
        else{
            $product_id = Yii::$app->request->get('product_id');
            $anonymous_identifier= Yii::$app->request->get('anonymous_identifier');
            if ( Wishlist::findOne(['product_id' => $product_id,'anonymous_identifier' => $anonymous_identifier])) {
                Wishlist::findOne(['product_id' => $product_id,'anonymous_identifier' => $anonymous_identifier])->delete();
                $product=Product::findOne(['id'=>$product_id]);
                $product->wishlist_cnt -= '1';
                if($product->save(false)){
                    $error_msg = "remove from wishlist";
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
            }
            else {
                $wishlist = new Wishlist();
                $wishlist->anonymous_identifier = $anonymous_identifier;
                $wishlist->product_id = $product_id;
                if ($wishlist->save(false)) {
                   $product=Product::findOne(['id'=>$product_id]);
                    $product->wishlist_cnt += '1';
                    if($product->save(false)){
                        $message = "add to wishlist";
                        $response['wishlist'] = $wishlist;
                    }
                }
                else {
                    $error_msg = "something went wrong";
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
            }
        }
        $this->_sendResponse($response, $message);
    }
    public function actionGetCartDetail(){
             if(Yii::$app->request->get()) {
            $user_id = Yii::$app->request->get('user_id');
            $anonymous_identifier= Yii::$app->request->get('anonymous_identifier');
            $offset = Yii::$app->request->get('offset');
            if ($user_id) {
                $wishlist = (new Query())
                    ->select('count(id)')
                    ->from('wishlist')
                    ->where(['user_id'=>$user_id])
                    ->andWhere('product_id = c.product_id');
                $user = Cart::findOne(['user_id' =>$user_id]);
                if(empty($user)){
                    $error_msg = "cart is empty";
                    $this->_sendErrorResponse(200, $error_msg, 501);
                }
                $cart =  Cart::find();
                $cart->alias('c');
                $cart->select(['c.*']);
                $cart->where(['c.user_id' => $user_id]);
                $cart->with('product');
                $cart->addSelect(['wishlist'=>$wishlist]);
                $cart->asArray();
                $cart->offset($offset);
                $cart->limit(10);
                $result = $cart->all();

           //    echo $cart->createCommand()->getRawSql(); die;


            } else {

                $wishlist = (new Query())
                    ->select('count(id)')
                    ->from(Wishlist::tableName())
                    ->where(['anonymous_identifier'=>$anonymous_identifier])
                    ->andWhere('product_id = c.product_id');
                $cart =  Cart::find();
                $cart->alias('c');
                $cart->select(['c.*']);
                $cart->where(['c.anonymous_identifier' => $anonymous_identifier]);
                $cart->with('product');
                $cart->addSelect(['wishlist'=>$wishlist]);
                $cart->asArray();
                $cart->offset($offset);
                $cart->limit(10);
                $result = $cart->all();

            }
            if ($result == NULL) {
                $error_msg = "cart is empty";
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            $response['cart'] = $result;
            $message = 'successfully send cart detail';
            $this->_sendResponse($response, $message);
        }
        else{
            $error_msg = "parameter not set";
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
     }
    public function actionGetWhishlist(){
      //  $value = $this->_checkAuth();
        $offset = Yii::$app->request->get('offset');
        if(Yii::$app->request->get('user_id')){
            $query= Wishlist::find()->alias('w');
            $query->where(['user_id'=>Yii::$app->request->get('user_id')]);

            $query->with('product');

            $query->asArray();
            $query->offset($offset);
            $query->limit(10);
            $result = $query->all();

            if($result==NULL){
                $error_msg = 'Empty whishlist';
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            else{
                $response['whishlist'] = $result;
                // $response['ProductImage']=$modelImage;
                $message = 'whishlist send successfully';
                $this->_sendResponse($response, $message);
            }
        }
        else{
            $query= Wishlist::find()->alias('w');
            $query->where(['anonymous_identifier'=>Yii::$app->request->get('anonymous_identifier')]);

            $query->with('product');

            $query->asArray();
            $query->offset($offset);
            $query->limit(10);
            $result = $query->all();

            if($result==NULL){
                $error_msg = 'Empty whishlist';
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            else{
                $response['whishlist'] = $result;
                // $response['ProductImage']=$modelImage;
                $message = 'whishlist send successfully';
                $this->_sendResponse($response, $message);
            }
        }
    }
    public function actionGetUserData(){
        $value = $this->_checkAuth();
        if (Yii::$app->request->get()) {

            $Order = (new Query())
                ->select('*')
                ->from(Orders::tableName())
                ->where(['user_id'=>Yii::$app->request->get('user_id')])
                ->count();

            $cart = (new Query())
                ->select('*')
                ->from(Cart::tableName())
                ->where(['user_id'=>Yii::$app->request->get('user_id')])
                ->count();

            $wishlist = (new Query())
                ->select('*')
                ->from(Wishlist::tableName())
                ->where(['user_id'=>Yii::$app->request->get('user_id')])
                ->count();

            $user = User::findOne(['id' =>Yii::$app->request->get('user_id')]);

            if(empty( $user)){
                $response['status']='false';
                $error_msg = "user not found";
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
            $response['status']='true';
            $response['user'] =$this->_userData($user);
            //$response['user'] =$result;
            $response['order']=$Order;
            $response['cart']=$cart;
            $response['whishlist']= $wishlist;
            $message = 'user data send successfully';
            $this->_sendResponse($response, $message);
        }
        else{
            $response['status']='false';
            $error_msg = "parameter not set";
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
    }
    public function actionActive($token){
       // echo $token;die;
        $user = User::findOne(['activate_token'=>$token]);
        //print_r($user);die;
        if(empty($user)){
            $response['status']='false';
            $error_msg = "token is not match";
            $this->_sendErrorResponse(200, $error_msg, 501);
        }
        else{
            $user->status = '1';
            if($user->save(false)){
                $response['status']='false';
                $error_msg = "successfully activate account";
                $this->_sendErrorResponse(200, $error_msg, 501);
            }
        }
      //  echo 'hii';
    }

}