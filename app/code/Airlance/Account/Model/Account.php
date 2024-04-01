<?php
namespace Airlance\Account\Model;

use yii\db\ActiveRecord;

class Account extends ActiveRecord
{
    public static function tableName()
    {
        return "{{%account}}";
    }
}