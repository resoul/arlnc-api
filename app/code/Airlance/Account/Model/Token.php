<?php
namespace Airlance\Account\Model;

use yii\db\ActiveRecord;

class Token extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%account_token}}";
    }
}