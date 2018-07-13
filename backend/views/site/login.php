<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'E-commerce';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1 style="margin-left: 639px;margin-top: 86px;"><?= Html::encode($this->title) ?></h1>



    <div class="row">
        <!-- <div class="col-lg-5">-->
        <div style="width:399px;margin-left: 564px;margin-top: 13px;background-color: white;height: 297px;">
            <p style="margin-left: 117px;;padding-top:25px;padding-bottom: 10px;">Sign in to start your session</p>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <!--$form->field($model, 'username')->textInput(['autofocus' => true,'label'=>false]) -->
            <div style="margin-left: 48px;width: 297px;">
                <?= $form->field($model, 'username')->textInput(['style'=>"border-radius:0",'autofocus' => true,'placeholder' => "UserName"])->label(false)  ?>
                <div>
                    <div style="width: 297px;">
                        <?= $form->field($model, 'password')->passwordInput(['style'=>"border-radius:0",'placeholder' => "Password"])->label(false) ?>
                    </div>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div class="form-group">
                        <?= Html::submitButton('Login', ['class' => 'btn','style'=>"color: #fff;background-color: #337ab7;border-color: #2e6da4;width: 295px;border-radius: 0;",'name' => 'login-button']) ?>
                    </div>
                    <div style="color:#999;margin:1em 0">
                        If you forgot your password you can <?= Html::a('reset it', ['../site/request-password-reset']) ?>.
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
