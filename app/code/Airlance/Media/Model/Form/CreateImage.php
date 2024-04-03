<?php
namespace Airlance\Media\Model\Form;

use Airlance\Account\Model\Account;
use Airlance\Account\Model\Profile;
use Airlance\Account\Model\Token;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use yii\base\Model;
use yii\helpers\Json;
use Yii;
use yii\web\UploadedFile;

class CreateImage extends Model
{
    public function upload(): array
    {
        $imageFolder = Yii::$app->params['images'];
        if ($upload = UploadedFile::getInstanceByName('image')) {
            $profile = $this->getProfile();
            $path = ltrim(Yii::$app->user->identity->media_path, '/');
            $file = Yii::$app->security->generateRandomString(16);
            if ($upload->saveAs("$imageFolder/$path/$file.jpg")) {
                if ($profile->profile_picture && ($old = ltrim($profile->profile_picture, '/'))) {
                    unlink("$imageFolder/$old");
                }

                $profile->profile_picture = "/$path/$file.jpg";
                if ($profile->save()) {
                    return ['profilePicture' => $profile->profile_picture];
                }
            }
        }

        return ['profilePicture' => '/'];
    }

    protected function getProfile(): Profile
    {
        if (($profile = Profile::findOne(['account_id' => Yii::$app->user->identity->getId()])) === null) {
            $profile = new Profile;
            $profile->account_id = Yii::$app->user->identity->getId();
            $profile->uuid = Yii::$app->security->generateRandomString(16);
            $profile->save();
        }

        return $profile;
    }
}