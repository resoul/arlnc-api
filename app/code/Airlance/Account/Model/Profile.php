<?php
namespace Airlance\Account\Model;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

class Profile extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class
        ];
    }

    public static function tableName(): string
    {
        return "{{%account_profile}}";
    }
}