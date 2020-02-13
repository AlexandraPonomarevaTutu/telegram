<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class CreateFirstTable extends AbstractMigration
{
    public function up()
    {
    	$sessionTable = $this->table('session');
        $sessionTable->addColumn('is_active', 'boolean', ['default' => 1])
            ->addColumn('chat_id', 'string')
            ->addIndex(['chat_id']);
        $sessionTable->save();
    }

    public function down()
    {
        $this->table('session')->drop()->save();
    }
}
