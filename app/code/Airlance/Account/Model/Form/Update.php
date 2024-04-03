<?php
namespace Airlance\Account\Model\Form;

use Airlance\Account\Model\Profile;
use yii\base\Model;
use Yii;

class Update extends Model
{
    public ?string $fullName;

    public ?string $username;

    public function update(): array
    {
        if ($profile = $this->getProfile()) {
            $profile->full_name = $this->fullName;
            $profile->username = $this->username;
            if ($profile->save()) {
                return [
                    'uuid' => $profile->uuid,
                    'fullName' => $profile->full_name,
                    'username' => $profile->username,
                    'media' => $profile->media,
                    'profilePicture' => $profile->profile_picture ?? '/',
                    'biography' => $profile->biography,
                    'isPrivate' => (bool) $profile->is_private,
                    'followers' => $profile->followers,
                    'following' => $profile->following,
                ];
            }
        }

        return $profile->getFirstErrors();
    }

    public function rules(): array
    {
        return [
            [['fullName', 'username'], 'required'],
            [['fullName', 'username'], 'string'],
        ];
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