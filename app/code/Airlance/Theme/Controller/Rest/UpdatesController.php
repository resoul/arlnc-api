<?php
namespace Airlance\Theme\Controller\Rest;

use Middleware\Framework\Rest\Controller;
use yii\web\Response;

class UpdatesController extends Controller
{
    public function actionGetVersion(): Response
    {
        return $this->asJson(['success' => true, 'data' => '0.0.1']);
    }
}