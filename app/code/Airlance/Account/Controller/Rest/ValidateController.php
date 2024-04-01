<?php
namespace Airlance\Account\Controller\Rest;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Middleware\Framework\Rest\Controller;
use yii\helpers\Json;
use yii\web\Response;
use Yii;

class ValidateController extends Controller
{
    public function actionToken(): Response
    {
        $data = [];
        if (($idToken = Yii::$app->request->post('token'))) {
            $keys = file_get_contents('https://appleid.apple.com/auth/keys');
            $keys = Json::decode($keys);
            $decodedToken = JWT::decode($idToken, JWK::parseKeySet($keys));
            if (
                $decodedToken->iss !== 'https://appleid.apple.com' ||
                !in_array($decodedToken->aud, Yii::$app->params['apple.client.id'])
            ) {
                $data['msg'] = 'Invalid issuer or audience';
            }

            $success = [
                'iss' => $decodedToken->iss ?? '',
                'aud' => $decodedToken->aud ?? '',
                'sub' => $decodedToken->sub ?? '',
                'email' => $decodedToken->email ?? '',
                'auth_time' => $decodedToken->auth_time ?? '',
                'email_verified' => $decodedToken->email_verified ?? '',
            ];
            Yii::warning($success);
            $data = [
                'uuid' => '246296108',
                'username' => 'resoul.ua',
                'fullName' => 'YM',
                'profilePicture' => '/246296107/4335712153130804/4549742191903244288_n.jpg',
                'isPrivate' => false,
                'biography' => 'Explore',
                'media' => 0,
                'followers' => 0,
                'following' => 0
            ];
        }

        return $this->asJson($data);
    }
}