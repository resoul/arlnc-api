<?php
namespace Airlance\Account\Migration;

use Middleware\Framework\Db\Model\Migration;

/**
 * Class m221122_221123_account_token
 */
class m221122_221123_account_token extends Migration
{
    protected string $table = "{{%account_token}}";

    public function up(): void
    {
        $this->createTable($this->table, [
            'session_id' => $this->primaryKey()->comment('Session ID'),
            'token' => $this->string(255)->notNull()->comment('Token'),
            'created_at' => $this->integer()->null()->comment('Created At'),
            'updated_at' => $this->integer()->null()->comment('Updated At'),
            'deleted_at' => $this->integer()->null()->comment('Deleted At')
        ], $this->tableOptions);

        $this->createIndex('account_token_token', $this->table, 'token', true);
        $this->createIndex('account_token_time', $this->table, ['created_at', 'updated_at', 'deleted_at']);
    }
}
