<?php
namespace Airlance\Media\Controller\Rest;

use Airlance\Media\Model\Form\CreateImage;
use Middleware\Framework\Rest\Controller;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use yii\web\UploadedFile;

class ImageController extends Controller
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'except' => ['view'],
            'authMethods' => [
                HttpBearerAuth::class,
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'upload' => ['POST'],
            ],
        ];

        return $behaviors;
    }

    public function actionView($user_id, $folder_id, $file): Response
    {
        $imageFolder = Yii::$app->params['images'];
        if (!file_exists("$imageFolder/$user_id/$folder_id/$file")) {
            throw new NotFoundHttpException('The requested image does not exist.');
        }

        return $this->response->sendFile("$imageFolder/$user_id/$folder_id/$file", null, ['inline' => true]);
    }

    public function actionUpload(): Response
    {
        return $this->asJson((new CreateImage)->upload());
    }
}