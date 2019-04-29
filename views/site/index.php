<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Hello!</h1>
        <h3>This is test application with shopping cart</h3>
        <?php
            if (Yii::$app->user->isGuest){
                echo "<p class='lead'>Log in to check it out</p>";
            }
        ?>
    </div>

</div>
