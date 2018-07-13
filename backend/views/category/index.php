<?php
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\form\ActiveForm;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index" style="margin-left: 11px;">

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
                            'name',
                            'image',
                            'is_active',
                            'pid',
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
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'image',
           // 'is_active',
            [
                'label' => 'Is Active',
                'attribute' => 'is_active',
                // 'class' => 'yii\grid\ActionColumn',


                'filter' => [
                    '1' => 'active',
                    '0' => 'inactive'
                ],

                // translate lookup value
                'value' => function ($model) {
                    $active = [
                        '1' => 'active',
                        '0' => 'inactive'
                    ];

                    return $active[$model->is_active];
                },
                'contentOptions' =>function ($model, $key, $index, $column)
                {
                    if($model->is_active=='1'){
                        return ['style' =>'width:100px','class'=>'btn btn-success','onclick'=>"myFunction($model->id)"];

                    }
                    else
                    {
                        return ['style' =>'width:100px','class'=>'btn btn-danger','onclick'=>"myFunction($model->id)"];
                    }

                },
            ],
            'pid',
            'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],

        ],
    ]); ?>
</div>
<script>
    function myFunction($id){
      //  alert($id);
        $.ajax({
            url: '<?php echo Yii::$app->request->baseUrl. '/category/active' ?>',
            type: 'post',
            data: {id: $id },
            success: function (data) {
                location. reload(true);
                alert(data);

            }
        });
    }

</script>