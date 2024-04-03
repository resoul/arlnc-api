<?php
namespace Airlance\Account\Model;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yii\web\IdentityInterface;
use Yii;

class Account extends ActiveRecord implements IdentityInterface
{
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class
        ];
    }

    public static function tableName(): string
    {
        return "{{%account}}";
    }

    public static function findIdentity($id): IdentityInterface
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): IdentityInterface|null
    {

        if ($identity = Token::findOne(['token' => $token])) {
            return static::findOne($identity->account_id);
        }

        return null;
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function beforeSave($insert): bool
    {
        $imageFolder = Yii::$app->params['images'];
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
                $path = Yii::$app->security->generateRandomString(9);
                $path2 = Yii::$app->security->generateRandomString(5);
                FileHelper::createDirectory("$imageFolder/$path/$path2");
                $this->media_path = "/$path/$path2";
            }
            return true;
        }
        return false;
    }
}