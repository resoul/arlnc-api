<?php
namespace Airlance\Media\Controller\Rest;

use Middleware\Framework\Rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class MovieController extends Controller
{
    public function actionMovie($slug): Response
    {
        $mediaFolder = Yii::$app->params['media'];
        if (!file_exists("$mediaFolder/$slug")) {
            throw new NotFoundHttpException('The requested video file does not exist.');
        }

        return $this->response->sendFile(
            "$mediaFolder/$slug/$slug",
            $slug,
            ['inline' => true, 'mimeType' => 'video/mp4']
        );
    }

    public function actionSeries($slug, $slug2, $slug3): Response
    {
        $mediaFolder = Yii::$app->params['media'];
        if (!is_dir("$mediaFolder/$slug/$slug2")) {
            throw new NotFoundHttpException('The requested movie series does not exist.');
        }
        if (!file_exists("$mediaFolder/$slug/$slug2/$slug3")) {
            throw new NotFoundHttpException('The requested video file does not exist.');
        }

        return $this->response->sendFile(
            "$mediaFolder/$slug/$slug2/$slug3",
            $slug3,
            ['inline' => true, 'mimeType' => 'video/mp4']
        );
    }
}