<?php

use app\assets\OrderAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\tree\TreeViewInput;

/** @var yii\web\View $this */
/** @var app\modules\ord\models\OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$bundle = OrderAsset::register($this);

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <?php var_dump($filterService); ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>   
        <?= Html::a('All Orders', ['index']) ?> 
        <?= Html::a('Prompt', ['index', 'OrderSearch[status]' => '1']) ?>
        <?= Html::a('In progress', ['index', 'OrderSearch[status]' => '2']) ?>
        <?= Html::a('Completed', ['index', 'OrderSearch[status]' => '3']) ?> 
        <?= Html::a('Canceled', ['index', 'OrderSearch[status]' => '4']) ?> 
        <?= Html::a('Error', ['index', 'OrderSearch[status]' => '5']) ?> 
    </p>

    <?php  // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'header' => 'ID',
                'attribute'=>'id',
            ],
            [
                'header' => 'User',
                'attribute' => 'users.last_name',
            ],
            [
                'header' => 'Link',
                'attribute'=>'link',
            ],
            [
                'header' => 'Quantity',
                'attribute'=>'quantity',
            ],
            [
                'header' => 'Service',
                'attribute' => 'service_id',
                'content' => function($data) use ($countServices, $filterService) {
                    $services = \app\modules\ord\models\Services::find()->all();
                    //var_dump($services);
                    //var_dump$services = \app\modules\ord\models\Services::find();($services['id']);
                    //return '<span style="border:1px #777777 solid;">' . $countServices[$data['service_id']-1]['cnt'] . '</span>' . $services[$data['service_id']-1]['name'] ;
                    return $filterService[$data['service_id']];
                },
                'filter' => $filterService,
            ],
            [
                'header' => 'Status',
                'attribute'=>'status',
                'filter' => false,
            ],
            [   'header' => '__Mode__',
                'attribute' => 'mode',
                'filter' => ['' => 'All', '0' => 'Manual', '1' => 'Auto'],
            ],
            [
                'header' => 'Created',
                'attribute'=>'created_at',
            ],
            /*
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Order $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
            */
        ],
    ]); ?>
    

</div>
