<?php
namespace Middleware\Framework\Queue\Migration;

use Middleware\Framework\Db\Model\Migration;

/**
 * Class m221122_221122_queue
 */
class m221122_221122_queue extends Migration
{
    protected string $table = "{{%queue}}";

    public function up(): void
    {
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->comment('ID'),
            'channel' => $this->string(255)->notNull()->comment('Channel'),
            'job' => "longblob NOT NULL COMMENT 'Job'",
            'pushed_at' => $this->integer()->comment('Pushed At'),
            'ttr' => $this->integer()->comment('TTR'),
            'delay' => $this->integer()->notNull()->defaultValue(0)->comment('Delay'),
            'priority' => $this->integer()->unsigned()->notNull()->defaultValue(1024)->comment('Priority'),
            'reserved_at' => $this->integer()->null()->comment('Reserved At'),
            'attempt' => $this->integer()->null()->comment('Attempt'),
            'done_at' => $this->integer()->null()->comment('Done At')
        ], $this->tableOptions);

        $this->createIndex('queue_channel', $this->table, 'channel');
        $this->createIndex('queue_reserved_at', $this->table, 'reserved_at');
        $this->createIndex('queue_priority', $this->table, 'priority');
    }
}
