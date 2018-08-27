<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup center-block text-center">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните все поля правильно:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'signup-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"\">{input}</div>\n<div class=\"\">{error}</div>",
            'labelOptions' => ['class' => 'control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'email')->input('email') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="center-block capthca">
        <?= $form->field($model, 'reCaptcha')->widget(
            \himiklab\yii2\recaptcha\ReCaptcha::className(),
            ['siteKey' => '6Ld1e2wUAAAAALiGhc1HRGWZHfmPrbWZRdOnWzGh']
        ) ?>
    </div>
    <div class="form-group">
        <div class="">
            <?= Html::submitButton('Вперед', ['class' => 'btn btn-primary btn-lg', 'name' => 'signup-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
