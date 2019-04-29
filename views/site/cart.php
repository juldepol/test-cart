<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = Yii::t('app', 'Cart');
?>

<h3><?= Html::encode($this->title) ?></h3>

<?= GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'comment',
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
        'quantity',
        [
            'label'=>Yii::t('app', 'Total'),
            'content'=>function($model){
                return  round($model['quantity']*($model['price']+$model['price']*$model['tax']/100),2)."€";
            }
        ],   
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'width: 120px; white-space: normal;'],
            'template' => '{remove}{delete}{edit}',
            'buttons' => [
                'remove' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                        'class' => 'btn btn-link btn-sm',
                        'title' => Yii::t('app', 'Remove item'),
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'class' => 'btn btn-link btn-sm',
                        'title' => Yii::t('app', 'Delete from cart'),
                    ]);
                },
                'edit' => function ($url, $model) {
                    return Html::button('<span class="glyphicon glyphicon-edit"></span>', [
                        'value' => $url,
                        'class' => 'btn btn-link btn-sm modalBtn',
                        'title' => Yii::t('app', 'Edit item'),
                    ]);
                },
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'remove') {
                    $url = '/basic/web/index.php/site/remove?id='. $model['id'];
                    return $url;
                }
                if ($action === 'delete') {
                    $url = '/basic/web/index.php/site/delete?id='. $model['id'];
                    return $url;
                }
                if ($action === 'edit') {
                    $url = '/basic/web/index.php/site/edit?id='. $model['id'];
                    return $url;
                }
            }
        ], 
    ],
]);
?>

<?php
if ($provider->getTotalCount() > 0) {
    echo "<h4>"."Total Brutto: ".$total['brutto']."€"."</h4>";
    echo "<h4>"."Taxes: ".$total['tax']."€"."</h4>";
    echo "<h4>"."Total Netto: ".$total['netto']."€"."</h4>";
    echo Html::a("Order", '/basic/web/index.php/site/order', [
        'class' => 'btn btn-success',
    ]);
} 
?>


<?php
$this->registerJs(<<<JS
$(document).ready(function() { 
    $('.modalBtn').click(function(e){
        e.preventDefault();
        $('#pModal').modal('show').find('#modalContent').load($(this).attr('value'));
    });
    
});
JS
);
?>
<?php
Modal::begin([
    'header'=> '<h4 id="form-name">'.Yii::t('app', 'Update order item').'</h4>',
    'id'=>'pModal',
    'size'=>'modal-lg'
]);
echo '<div id="modalContent"></div>';
yii\bootstrap\Modal::end();
?>