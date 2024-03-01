<?php
namespace Middleware\Framework\Db\Model;

use yii\db\Migration as BaseMigration;

/**
 * Migration class with extended settings and added standard methods for all application migrations.
 *
 * @author resoul <resoul.ua@icloud.com>
 * @since 0.1
 */
class Migration extends BaseMigration
{
    protected string $table = "";

    protected string $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';

    public function down(): void
    {
        if (!empty($this->table)) {
            $this->dropTable($this->table);
        }
    }
}
