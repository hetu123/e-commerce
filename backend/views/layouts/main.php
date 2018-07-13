<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
$baseurl ="http://" .$_SERVER['SERVER_NAME'];
DashboardAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>

    <div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="index2.html" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">APP</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">E-commerce</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">Welcome </span>
                            <span class="col-xs"><?php if(Yii::$app->user->isGuest){
                                    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
                                }
                                else{?>
                                    <?=    Yii::$app->user->identity->username;
                                } ?></span>
                        </a>
                    </li>
                    <?=
                    $menuItems[] = '<li>'
                        . Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            'Sign out',
                            ['class' => 'btn btn-link logout','style'=>"background-color:#dd4b39;border-color: #d73925;color:white"]
                        )
                        . Html::endForm()
                        . '</li>';  ?>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li>
                    <a href="<?= $baseurl ?>/admin/dashboard">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span>Category</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?= $baseurl ?>/admin/addcategory"><i class="fa fa-minus"></i> Add Category</a></li>
                        <li><a href="<?= $baseurl ?>/admin/category"><i class="fa fa-minus"></i> List Category</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-tags"></i>
                        <span>Product</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?= $baseurl ?>/admin/addproduct"><i class="fa fa-minus"></i> Add Product</a></li>
                        <li><a href="<?= $baseurl ?>/admin/product"><i class="fa fa-minus"></i> List Product</a></li>
                    </ul>
                </li>





            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
    <!--<div class="container">-->
    <div class="content-wrapper">
        <!-- Content Wrapper. Contains page content -->
        <!--<div class="content-wrapper">-->

        <!-- Content Header (Page header) -->
        <!--<section class="content-header">
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>
-->
        <!-- Main content -->
        <!--  <section class="content">-->

        <?= $content ?>
        <!-- </section>-->

    </div>


</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
