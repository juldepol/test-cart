<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'comment-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>

<div class="col-md-12">
    <?= $form->field($model, 'comment') ?>
</div>  
<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
</div>  
   
<?php ActiveForm::end() ?>

