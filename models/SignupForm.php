<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $password;
    public $email;
    public $reCaptcha;

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email'], 'required', 'message' => "Обязательное поле"],
            ['email', 'email', 'message' => "Введите правильный email"],
            [['reCaptcha'], 'required', 'message' => "Подтвердите, что вы не робот"],
            [['username'], 'unique', 'targetClass' => User::className(), 'message' => "Пользователь с таким именем уже зарегистрирован"],
            [['email'], 'unique', 'targetClass' => User::className(), 'message' => "Пользователь с такой почтой уже зарегистрирован"],
            //[['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => 'your secret key', 'uncheckedMessage' => 'Please confirm that you are not a bot.']
        ];
    }
}