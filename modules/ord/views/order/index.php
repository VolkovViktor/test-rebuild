<?php

use app\assets\OrderAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;



/** @var yii\web\View $this */
/** @var app\modules\ord\models\OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\modules\ord\controllers\OrderController $filterService */
/** @var app\modules\ord\controllers\OrderController $viewService */

$bundle = OrderAsset::register($this);

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
//var_dump($status);
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div style="float: right;">
        <?php
            $form1 = ActiveForm::begin(['method' => 'get', 'action' => "index.php?r=ord/order/index"]); //add status !!!!!!!!!!!!!!!
            echo Html::input('text', 'search_text');
            echo Html::dropDownList('search_attr', 'id', ['id', 'user_last_name', 'user_first_name', 'link']);
            echo Html::submitButton('Search');
            ActiveForm::end();
        ?>
    </div>

    <p>
        <?= Html::a('All Orders', ['index']) ?>
        <?= Html::a('Pending', ['index', 'OrderSearch[status]' => 1]) ?>
        <?= Html::a('In progress', ['index', 'OrderSearch[status]' => 2]) ?>
        <?= Html::a('Completed', ['index', 'OrderSearch[status]' => 3]) ?>
        <?= Html::a('Canceled', ['index', 'OrderSearch[status]' => 4]) ?>
        <?= Html::a('Error', ['index', 'OrderSearch[status]' => 0]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'header' => 'ID',
                'attribute' => 'id',
                'filter' => false,
            ],
            [
                'header' => 'User',
                'attribute' => 'users.last_name',
            ],
            [
                'header' => 'Link',
                'attribute' => 'link',
                'filter' => false,
            ],
            [
                'header' => 'Quantity',
                'attribute' => 'quantity',
            ],
            [
                'header' => 'Service',
                'attribute' => 'service_id',
                'content' => function ($data) use ($viewService) {
                    return $viewService[$data['service_id']];
                },
                'filter' => $filterService,
            ],
            [
                'header' => 'Status',
                'attribute' => 'status',
                'filter' => false,
                'value' => function ($data) {
                    $status = $data['status'];
                    $arr = array(
                        1 => 'Pending',
                        2 => 'In progress',
                        3 => 'Completed',
                        4 => 'Canceled',
                        0 => 'Error',
                    );
                    return $arr[$status];
                },
            ],
            ['header' => '__Mode__',
                'attribute' => 'mode',
                'filter' => ['' => 'All', '0' => 'Manual', '1' => 'Auto'],
                'value' => function ($data) {
                    return ($data['mode'] == 0) ? 'Manual' : 'Auto';
                }
            ],
            [
                'header' => 'Created',
                'attribute' => 'created_at',
                'format' => ['date', 'php: d.m.Y <\b\\r> H:i:s'],
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
