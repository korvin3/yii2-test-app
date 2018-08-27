<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Вход';
?>
<div class="site-login center-block text-center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните поля и подтвердите, что Вы не робот:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "<div class=\"\">{label}</div><div class=\"\">{input}</div>\n<div class=\"\">{error}</div>",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="center-block capthca">
            <?= \himiklab\yii2\recaptcha\ReCaptcha::widget(['name' => 'reCaptcha']) ?>
        </div>
        <? /*$form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"\">{input} {label}</div>\n<div class=\"\">{error}</div>",
        ]) */?>

        <div class="form-group">
            <div class="">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-lg', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
