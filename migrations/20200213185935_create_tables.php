<?php

use Phinx\Migration\AbstractMigration;

class CreateTables extends AbstractMigration
{
    public function up()
    {

        $paymentTable = $this->table('payment');
        $paymentTable->addColumn('sum', 'decimal', ['null' => false])
            ->addColumn('session_id', 'integer')
            ->addColumn('description', 'string', ['default' => ''])
            ->addColumn('is_open', 'boolean', ['default' => 1])
            ->addIndex(['session_id']);
        $paymentTable->save();

        $sessionTable = $this->table('debt');
        $sessionTable->addColumn('payment_id', 'integer')
            ->addColumn('description', 'string', ['default' => ''])
            ->addColumn('amount', 'decimal', ['null' => false])
            ->addColumn('user_debtor', 'string', ['null' => false])
            ->addColumn('creditor', 'string', ['null' => false])
            ->addColumn('is_open', 'boolean', ['default' => 1])
            ->addIndex(['payment_id']);
        $sessionTable->save();
    }

    public function down()
    {
        $this->table('debt')->drop()->save();
        $this->table('payment')->drop()->save();
    }
}
