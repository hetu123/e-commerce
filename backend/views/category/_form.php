<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model,'image')->fileInput() ?>

    <!--$form->field($model, 'is_active')->textInput()-->
    <?= $form->field($model, 'is_active')->dropDownList([ '1' => 'Yes', '0' => 'No', ], ['prompt' => '']) ?>

    <?php

    $category=ArrayHelper::map(\common\models\Category::findAll(['pid' => null]), 'id', 'name');
    echo $form->field($model, 'pid')
        ->dropDownList(
            $category,
            ['id'=>'name','prompt'=>'select PID']
        );
    ?>
    <!-- $form->field($model, 'created_at')->textInput() -->

    <!-- $form->field($model, 'updated_at')->textInput() -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
