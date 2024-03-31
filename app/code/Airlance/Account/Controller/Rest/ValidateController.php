<?php
namespace Airlance\Account\Controller\Rest;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Middleware\Framework\Rest\Controller;
use yii\base\Exception;
use yii\helpers\Json;
use yii\web\Response;
use Yii;

class ValidateController extends Controller
{
    public function actionToken(): Response
    {
        $data['success'] = false;
        if (($idToken = Yii::$app->request->post('token')) && $ID = Yii::$app->params['apple.client.id']) {
            $keys = file_get_contents('https://appleid.apple.com/auth/keys');
            $keys = Json::decode($keys);
            $decodedToken = JWT::decode($idToken, JWK::parseKeySet($keys));
            if ($decodedToken->iss !== 'https://appleid.apple.com' || $decodedToken->aud !== $ID) {
                $data['msg'] = 'Invalid issuer or audience';
            }

            $success = [
                'iss' => $decodedToken->iss,
                'aud' => $decodedToken->aud,
                'sub' => $decodedToken->sub,
                'email' => $decodedToken->email,
                'auth_time' => $decodedToken->auth_time,
                'email_verified' => $decodedToken->email_verified,
            ];
            $data['success'] = true;
        }

        return $this->asJson($data);
    }
}