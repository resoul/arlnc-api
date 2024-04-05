<?php
namespace Airlance\Account\Model\Form;

use Airlance\Account\Model\Account;
use Airlance\Account\Model\Profile;
use Airlance\Account\Model\Token;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use yii\base\Model;
use yii\helpers\Json;
use Yii;
use yii\web\NotFoundHttpException;

class Create extends Model
{
    public ?string $token;

    public function create(): array
    {
        $client = Yii::$app->params['apple.client.id'];
        $keys = file_get_contents('https://appleid.apple.com/auth/keys');
        $keys = Json::decode($keys);
        $decodedToken = JWT::decode($this->token, JWK::parseKeySet($keys));

        if ($decodedToken->iss !== 'https://appleid.apple.com' || !in_array($decodedToken->aud, $client)) {
            throw new NotFoundHttpException('Invalid issuer or audience');
        }

        $this->token = Yii::$app->security->generateRandomString(64);
        $token = new Token;
        if (($account = Account::findOne(['uuid' => $decodedToken->sub])) === null) {
            $account = new Account;
            $account->is_private = $decodedToken->email_verified ?? null;
            $account->auth_time = $decodedToken->auth_time ?? null;
            $account->email = $decodedToken->email ?? null;
            $account->uuid = $decodedToken->sub;
            if ($account->save()) {
                $token->account_id = $account->id;
                $token->token = $this->token;
                if ($token->save()) {
                    return ['token' => $this->token];
                }
            }
        }

        $token->account_id = $account->id;
        $token->token = $this->token;
        if ($token->save()) {
            if ($profile = Profile::findOne(['account_id' => $account->id])) {
                return [
                    'token' => $this->token,
                    'account' => [
                        'uuid' => $profile->uuid,
                        'fullName' => $profile->full_name,
                        'username' => $profile->username,
                        'media' => $profile->media,
                        'profilePicture' => $profile->profile_picture ?? '/',
                        'biography' => $profile->biography,
                        'isPrivate' => (bool) $profile->is_private,
                        'followers' => $profile->followers,
                        'following' => $profile->following,
                    ]
                ];
            }

            return ['token' => $this->token];
        }

        throw new NotFoundHttpException('Invalid issuer or audience');
    }

    public function rules(): array
    {
        return [
            [['token'], 'required'],
            [['token'], 'string'],
        ];
    }
}