<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\db\Query;
/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?php

    $category=ArrayHelper::map(\common\models\Category::find()->asArray()->all(), 'id', 'name');
   // echo $category->createCommand()->getRawSql();
    echo $form->field($model, 'category_id')
        ->dropDownList(
            $category,
            ['id'=>'name','prompt'=>'Select category Name']
        );
    ?>

     <!--$form->field($model, 'category_id')->textInput() -->

    <!-- $form->field($model, 'sub_category_id')->textInput() -->

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model,'image')->fileInput() ?>

    <!-- $form->field($modelImage,'image[]')->fileInput(['multiple' => true,])-->
    <?= $form->field($modelImage, 'image[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>


    <!-- $form->field($model, 'image')->textInput(['maxlength' => true])-->
    <?= $form->field($model, 'description')->widget(\yii\redactor\widgets\Redactor::className()) ?>

    <?= $form->field($model, 'short_description')->widget(\yii\redactor\widgets\Redactor::className()) ?>


    <?php
    $brand=ArrayHelper::map(\common\models\Product::find()->asArray()->all(), 'brand', 'brand');
    echo $form->field($model, 'brand')
        ->dropDownList(
            $brand,
            ['brand'=>'brand','prompt'=>'Select brand Name']
        );
    ?>

    <?php
    echo $form->field($model, 'size')->dropDownList(
        ['XS' => 'XS', 'S' => 'S', 'M' => 'M','L'=>'L','XL'=>'XL'],
            ['prompt'=>'Select Size']
    ); ?>

    <!-- $form->field($model, 'description')->textarea(['rows' => 6])-->

    <!-- $form->field($model, 'short_description')->textarea(['rows' => 6])-->

    <!-- $form->field($model, 'is_favorite')->textInput()-->

    <!-- $form->field($model, 'is_on_wishlist')->textInput()-->

    <?= $form->field($model, 'total_stock')->textInput() ?>

    <!--$form->field($model, 'use_stock')->textInput()-->

    <!-- $form->field($model, 'left_stock')->textInput()-->

    <!-- $form->field($model, 'created_at')->textInput()-->

    <!-- $form->field($model, 'updated_at')->textInput() -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
