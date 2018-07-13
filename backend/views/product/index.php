<?php
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index" style="margin-left: 11px;">

    <h1><?= Html::encode($this->title) ?></h1>
    <div>
        <div>
            <h4 style="margin-left: 40px">Date Range:(Filter by category created date)</h4>
        </div>
        <div>
            <div class="daterange" style="margin-top: 10px;width: 422px;">
                <?php

                $form = ActiveForm::begin([
                    'method' => 'get',
                    'enableClientScript' => false
                ]);

                ?>
                <?php $model=new \common\models\User();?>

                <?=  DateRangePicker::widget([
                    'model'=>$searchModel   ,

                    'attribute'=>'createTimeRange',
                    // 'initRangeExpr' => true,
                    'convertFormat'=>true,
                    'options' => ['class'=>'date'],
                    'pluginOptions'=>[
                        'timePicker'=>true,
                        'timePickerIncrement'=>30,
                        'locale'=>[
                            'format'=>'Y-m-d h:i A',
                            'style'=>"width: 387px;",
                        ]
                    ],
                    // 'presetDropdown' => false,
                    'hideInput' => true,

                ]);
                ?>
                <?php echo Html::submitButton('Find', array('class' => 'btn btn-success find','style' => 'margin-top: -56px;    margin-left: 458px;    width: 128px;')) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div>
    <div class="pull-right" style="margin-right:90px;margin-top: -94px; ">
        <h4> Export Menu </h4>
        <div class="btn-toolbar kv-grid-toolbar" role="toolbar">

            <div class="btn-group">

                <?php $gridColumns = [
                    'id',
                    'category_id',
                    'name',
                    'price',
                    'image',
                    'description:ntext',
                    'short_description:ntext',
                    'total_stock',
                    'use_stock',
                    'left_stock',
                    'created_at',
                    'updated_at',
                ]; ?>
                <?=  ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'target' => ExportMenu::TARGET_BLANK,
                ]);?>
            </div>
        </div>

    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div style="overflow-y: scroll;Height:?">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
           // 'category_id',
            [
                'attribute' => 'category_id',
                'value'     => 'category.name'
            ],
            'name',
            'price',
            'image',
            'description:ntext',
            'short_description:ntext',

            'total_stock',
            'use_stock',
            'left_stock',
            'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
</div>
