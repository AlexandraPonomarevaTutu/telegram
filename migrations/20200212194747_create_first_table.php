<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateFirstTable extends AbstractMigration
{
    public function up()
    {
    	$debtTable = $this->table('session');
        $debtTable->addColumn('is_active', 'boolean', ['default' => 1])
            ->addColumn('chat_id', 'string')
            ->addIndex(['chat_id']);
        $debtTable->save();
    }

    public function down()
    {
        $this->table('debt')->drop()->save();
    }
}
