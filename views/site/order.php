<?php
use yii\helpers\Html;
?>
<h2>Hooray!</h2>
<p> Your order has been placed </p>
<?= Html::a("Go back to shopping", '/basic/web/index.php/site/products', [
        'class' => 'btn btn-success btn-lg',
    ]);?>