<?php
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Products');
?>

<h3><?= Html::encode($this->title) ?></h3>

<?= GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'description',
        [
            'attribute'=>'price',
            'label'=>Yii::t('app', 'Price per unit'),
            'content'=>function($model){
                return  $model['price']."€";
            }
        ],
        [
            'attribute'=>'tax',
            'label'=>Yii::t('app', 'Tax'),
            'content'=>function($model){
                return  $model['tax']."%";
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 40px; white-space: normal;'],
            'template' => '{add}',
            'buttons' => [
                'add' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                        'class' => 'btn btn-link btn-sm',
                        'title' => Yii::t('app', 'Add to cart'),
                    ]);
                },
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'add') {
                    $url = '/basic/web/index.php/site/add?id='. $model['id'];
                    return $url;
                }
            }
        ],    
    ],
]);
?>