<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateSchedulersTable extends AbstractMigration
{
    public bool $autoId = false;
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('schedulers');
        $table
            ->addColumn('uniqid', 'string', [
                'limit' => 500,
                'null' => false,
            ])
            ->addColumn('command', 'string', [
                'limit' => 500,
                'null' => false,
            ])
            ->addColumn('frequency', 'string', [
                'default' => null,
                'limit' => 140,
                'null' => false,
            ])
            ->addColumn('last_run', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('next_run', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('return_value', 'boolean', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('completed_at', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('failure_message', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addPrimaryKey(['uniqid'])
            ->addIndex(['uniqid']);

        $table->create();
    }
}
