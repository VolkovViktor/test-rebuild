<?php

use app\modules\ord\assets\OrderAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\ord\models\OrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\modules\ord\controllers\OrderController $filterParams */

$bundle = OrderAsset::register($this);

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div style="float: right;">
        <?php
            $form1 = ActiveForm::begin(['method' => 'get', 'action' => "index.php?r=ord/order/index&OrderSearch[status]={$filterParams['status']}"]); //add status !!!!!!!!!!!!!!!
            echo Html::input('text', 'search_text');
            echo Html::dropDownList('search_attr', 'id', ['id', 'user_last_name', 'user_first_name', 'link']);
            echo Html::submitButton('Search');
            ActiveForm::end();
        ?>
    </div>

    <p>
        <?php foreach ($filterParams['statuses'] as $key => $value): ?>
            <?= Html::a($value, ['index', 'OrderSearch[status]' => $key]) ?>
        <?php endforeach; ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
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
                'content' => function ($data) use ($filterParams) {
                    return $filterParams['viewService'][$data['service_id']];
                },
                'filter' => $filterParams['filterService'],
            ],
            [
                'header' => 'Status',
                'attribute' => 'status',
                'filter' => false,
                'value' => function ($data) use ($filterParams)  {
                    return $filterParams['statuses'][$data['status']];
                },
            ],
            ['header' => '__Mode__',
                'attribute' => 'mode',
                'filter' => $filterParams['modes'],
                'value' => function ($data) use ($filterParams)  {
                    return $filterParams['modes'][$data['mode']];
                },
            ],
            [
                'header' => 'Created',
                'attribute' => 'created_at',
                'format' => ['date', 'php: d.m.Y <\b\\r> H:i:s'],
            ],
        ],
    ]); ?>


</div>
