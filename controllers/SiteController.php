<?php

namespace app\controllers;

use app\models\SignupForm;
use app\models\User;
use app\services\SignupService;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\base\ErrorException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSignup(){
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()){
            $user = new User();

            $user->username = $form->username;
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($form->password);
            $user->email = $form->email;
            $user->activationToken = Yii::$app->getSecurity()->generateRandomString(40);
            $user->authKey = Yii::$app->getSecurity()->generateRandomString(40);

            try {$success = $user->sendConfirmMail();} catch (\Exception $e) {
                Yii::error("Ошибка при отправлении письма: ". $e);
            }

            if ($success){
                $user->save();
                Yii::$app->session->setFlash('success', 'Регистрация прошла успешно, осталось только активировать аккаунт по ссылке на почте.');
                $this->redirect(Url::home());
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при отправлении письма на Вашу почту. Проверьте правильность данных и попробуйте еще раз.');
                return $this->render('signup', ['model' => $form]);
            }
        } else
            return $this->render('signup', ['model' => $form]);
    }

    public function actionConfirm($username, $token){
        $model = User::findByUsername($username);
        if ($model && $model->activationToken == $token) {
                $model->isActive = 1;
                $model->activationToken = NULL;
                $model->save();
                Yii::$app->session->setFlash('success', 'Ваш аккаунт активирован!');
                $this->redirect(Url::toRoute('site/login'));
        }
        else
            return $this->render('error', ['message' => 'Произошла ошибка, аккаунт не активирован']);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
