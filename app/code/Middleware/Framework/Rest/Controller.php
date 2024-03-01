<?php
namespace Middleware\Framework\Rest;

use yii\filters\Cors;
use yii\rest\Controller as BaseController;

class Controller extends BaseController
{
    public function behaviors(): array
    {
        return [
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => []
                ]
            ]
        ];
    }
}
