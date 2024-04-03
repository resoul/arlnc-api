<?php
namespace Airlance\Account\Migration;

use Middleware\Framework\Db\Model\Migration;

/**
 * Class m221122_221124_account_profile
 */
class m221122_221124_account_profile extends Migration
{
    protected string $table = "{{%account_profile}}";

    public function up(): void
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->comment('ID'),
            'account_id' => $this->integer()->notNull()->comment('Account Id'),
            'uuid' => $this->char(16)->notNull()->comment('UUID'),
            'email' => $this->string(255)->null()->comment('Email'),
            'username' => $this->char(32)->null()->comment('Username'),
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

        $this->addForeignKey(
            'account_profile_account_id',
            $this->table,
            'account_id',
            '{{%account}}',
            'id',
            'CASCADE',
            'CASCADE');

        $this->createIndex('account_profile_email', $this->table, 'email', true);
        $this->createIndex('account_profile_uuid', $this->table, 'uuid', true);
        $this->createIndex('account_profile_media', $this->table, ['media', 'followers', 'following']);
        $this->createIndex('account_profile_created', $this->table, ['created_at', 'updated_at', 'deleted_at']);
        $this->createIndex('account_profile_is_private', $this->table, 'is_private');
        $this->createIndex('account_profile_username', $this->table, 'username', true);
    }
}
