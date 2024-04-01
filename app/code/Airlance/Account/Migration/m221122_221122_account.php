<?php
namespace Airlance\Account\Migration;

use Middleware\Framework\Db\Model\Migration;

/**
 * Class m221122_221122_account
 */
class m221122_221122_account extends Migration
{
    protected string $table = "{{%account}}";

    public function up(): void
    {
        $this->createTable($this->table, [
            'uuid' => $this->char(16)->comment('UUID'),
            'udid' => $this->char(16)->comment('UDID'),
            'email' => $this->string(255)->notNull()->comment('Email'),
            'username' => $this->char(32)->notNull()->comment('Username'),
            'is_private' => $this->smallInteger(1)->defaultValue(0)->comment('Is Private'),
            'full_name' => $this->string(64)->null()->comment('Full Name'),
            'profile_picture' => $this->string(128)->null()->comment('Profile Picture'),
            'biography' => $this->string(256)->null()->comment('Biography'),
            'media' => $this->integer()->defaultValue(0)->comment('Media'),
            'followers' => $this->integer()->defaultValue(0)->comment('Followers'),
            'following' => $this->integer()->defaultValue(0)->comment('Following'),
            'created_at' => $this->integer()->null()->comment('Created At'),
            'updated_at' => $this->integer()->null()->comment('Updated At'),
            'deleted_at' => $this->integer()->null()->comment('Deleted At')
        ], $this->tableOptions);

        $this->createIndex('account_uuid', $this->table, 'uuid', true);
        $this->createIndex('account_email', $this->table, 'email', true);
        $this->createIndex('account_media', $this->table, ['media', 'followers', 'following']);
        $this->createIndex('account_created', $this->table, ['created_at', 'updated_at', 'deleted_at']);
        $this->createIndex('account_is_private', $this->table, 'is_private');
        $this->createIndex('account_username', $this->table, 'username', true);
        $this->createIndex('account_uuid', $this->table, 'uuid', true);
    }
}
