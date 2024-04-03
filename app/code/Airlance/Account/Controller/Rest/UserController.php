<?php
namespace Airlance\Account\Controller\Rest;

use Airlance\Account\Model\Form\Create;
use Airlance\Account\Model\Form\Update;
use Middleware\Framework\Rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class UserController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => ['create'],
            'authMethods' => [
                HttpBearerAuth::class,
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'create' => ['POST'],
                'update' => ['POST'],
            ],
        ];

        return $behaviors;
    }

    public function actionCreate(): Response
    {
        $model = new Create;
        if ($model->load(Yii::$app->request->post(), '') && $model->create()) {
            return $this->asJson(['token' => $model->token]);
        }

        throw new NotFoundHttpException($model->getFirstError('token'));
    }

    public function actionUpdate(): Response
    {
        $model = new Update;
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            return $this->asJson($model->update());
        }

        throw new NotFoundHttpException($model->getFirstErrors());
    }
}