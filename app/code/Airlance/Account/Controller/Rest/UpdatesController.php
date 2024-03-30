<?php
namespace Airlance\Account\Controller\Rest;

use Middleware\Framework\Rest\Controller;
use yii\web\Response;
use Yii;

class UpdatesController extends Controller
{
    public function actionNotification(): Response
    {
        Yii::error(Yii::$app->request);
        return $this->asJson(['success' => true]);
    }
}