<?php
namespace Airlance\Media\Controller\Rest;

use Middleware\Framework\Rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class ImageController extends Controller
{
    public function actionView($user_id, $folder_id, $file): Response
    {
        $imageFolder = Yii::$app->params['images'];
        if (!file_exists("$imageFolder/$user_id/$folder_id/$file")) {
            throw new NotFoundHttpException('The requested image does not exist.');
        }

        return $this->response->sendFile("$imageFolder/$user_id/$folder_id/$file", null, ['inline' => true]);
    }
}