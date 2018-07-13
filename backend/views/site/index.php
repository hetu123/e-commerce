<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<section class="content-header">
    <h1 style="padding-top: 10px;
    padding-bottom: 10px;">
        E-commerce           </h1>

</section>
<div class="row">

    <div class="info-box bg-green" style="width: 20%;float: left;margin: 0 5px 50px 70px;">
        <span class="info-box-icon"><i class="fa fa-usd"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">Category</span>
            <h3><?= \common\models\Category::find()->where(['pid'=>NULL])->count()?></h3>
        </div>
        <!-- /.info-box-content -->
    </div>

    <div class="info-box bg-red" style="width: 20%;float: left;margin: 0 5px 50px 70px;">
        <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">Sub Category</span>
            <h3><?= \common\models\Category::find()->where(['not',['pid' => null]])->count()?></h3>
        </div>
        <!-- /.info-box-content -->
    </div>

    <div class="info-box bg-yellow" style="width: 20%;float: left;margin: 0 5px 50px 70px;">
        <span class="info-box-icon"><i class="fa fa-briefcase"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">Product</span>
            <h3><?= \common\models\Product::find()->count()?></h3>
        </div>
        <!-- /.info-box-content -->
    </div>

    <div class="info-box bg-pink" style="width: 20%;float: left;margin: 0 5px 50px 70px;">
        <span class="info-box-icon"><i class="fa fa-users"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">All Users</span>
            <h3><?= \common\models\User::find()->count()?></h3>
        </div>
        <!-- /.info-box-content -->
    </div>

    <div class="info-box bg-teal" style="width: 20%;float: left;margin: 0 5px 50px 70px;">
        <span class="info-box-icon"><i class="fa fa-user-plus"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">Cart</span>
            <h3><?= \common\models\Cart::find()->count()?></h3>
        </div>
        <!-- /.info-box-content -->
    </div>

    <div class="info-box bg-light-blue" style="width: 20%;float: left;margin: 0 5px 50px 70px;">
        <span class="info-box-icon"><i class="fa fa-user-secret"></i></span>

        <div class="info-box-content">
            <span class="info-box-text">Order</span>
            <h3><?= \common\models\Orders::find()->count()?></h3>
        </div>
        <!-- /.info-box-content -->
    </div>
</div>
