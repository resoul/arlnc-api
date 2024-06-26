<?php
namespace Airlance\Account\Model;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

class Token extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class
        ];
    }

    public static function tableName(): string
    {
        return "{{%account_token}}";
    }

    public function getAccount(): ActiveQuery
    {
        return $this->hasOne(Account::class, ['id' => 'account_id']);
    }
}