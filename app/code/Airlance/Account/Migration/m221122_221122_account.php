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
            'id' => $this->primaryKey()->comment('ID'),
            'uuid' => $this->char(64)->notNull()->comment('UUID'),
            'email' => $this->string(255)->null()->comment('Email'),
            'is_private' => $this->smallInteger(1)->null()->comment('Is Private'),
            'auth_time' => $this->integer()->null()->comment('Auth Time'),
            'auth_key' => $this->string(32)->notNull()->comment('Auth Keys'),
            'media_path' => $this->char(16)->notNull()->comment('Media Path'),
            'created_at' => $this->integer()->null()->comment('Created At'),
            'updated_at' => $this->integer()->null()->comment('Updated At'),
            'deleted_at' => $this->integer()->null()->comment('Deleted At')
        ], $this->tableOptions);

        $this->createIndex('account_uuid', $this->table, 'uuid', true);
        $this->createIndex('account_email', $this->table, 'email', true);
        $this->createIndex('account_created', $this->table, ['created_at', 'updated_at', 'deleted_at']);
        $this->createIndex('account_is_private', $this->table, 'is_private');
        $this->createIndex('account_media_path', $this->table, 'media_path', true);
    }
}
