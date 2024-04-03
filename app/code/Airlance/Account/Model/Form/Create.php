<?php
namespace Airlance\Account\Model\Form;

use Airlance\Account\Model\Account;
use Airlance\Account\Model\Token;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use yii\base\Model;
use yii\helpers\Json;
use Yii;

class Create extends Model
{
    public ?string $token;

    public function create(): bool
    {
        if ($this->validate()) {
            $client = Yii::$app->params['apple.client.id'];
            $keys = file_get_contents('https://appleid.apple.com/auth/keys');
            $keys = Json::decode($keys);
            $decodedToken = JWT::decode($this->token, JWK::parseKeySet($keys));

            if ($decodedToken->iss !== 'https://appleid.apple.com' || !in_array($decodedToken->aud, $client)) {
                $this->addError('token', 'Invalid issuer or audience');
                return false;
            }

            $this->token = Yii::$app->security->generateRandomString(64);
            if (($account = Account::findOne(['uuid' => $decodedToken->sub])) === null) {
                $account = new Account;
                $account->is_private = $decodedToken->email_verified ?? null;
                $account->auth_time = $decodedToken->auth_time ?? null;
                $account->email = $decodedToken->email ?? null;
                $account->uuid = $decodedToken->sub;
                if ($account->save()) {
                    $token = new Token;
                    $token->account_id = $account->id;
                    $token->token = $this->token;

                    return $token->save();
                }
            }

            $token = new Token;
            $token->account_id = $account->id;
            $token->token = $this->token;
            return $token->save();
        }

        return false;
    }

    public function rules(): array
    {
        return [
            [['token'], 'required'],
            [['token'], 'string'],
        ];
    }
}