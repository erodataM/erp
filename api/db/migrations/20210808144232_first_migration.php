<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FirstMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('company');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('balance', 'integer')
              ->addColumn('country', 'string', ['limit' => 100])
              ->create();
        $table = $this->table('product');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('price', 'integer')
              ->addColumn('tax', 'integer')
              ->addColumn('stock', 'integer')
              ->create();
        $table = $this->table('provider');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('address', 'string', ['limit' => 100])
              ->addColumn('country', 'string', ['limit' => 100])
              ->create();
        $table = $this->table('client');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('address', 'string', ['limit' => 100])
              ->addColumn('country', 'string', ['limit' => 100])
              ->create();
        $table = $this->table('employee');
        $table->addColumn('name', 'string', ['limit' => 100])
              ->addColumn('password', 'string', ['limit' => 100])
              ->addColumn('birthday', 'datetime')
              ->addColumn('country', 'string', ['limit' => 100])
              ->addColumn('firstday', 'datetime')
              ->addColumn('role', 'string', ['limit' => 100])
              ->create();
        $table = $this->table('transaction');
        $table->addColumn('company_id', 'integer', ['null' => true])
              ->addForeignKey('company_id', 'company', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
              ->addColumn('client_id', 'integer', ['null' => true])
              ->addForeignKey('client_id', 'client', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
              ->addColumn('provider_id', 'integer', ['null' => true])
              ->addForeignKey('provider_id', 'provider', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
              ->addColumn('product_id', 'integer', ['null' => true])
              ->addForeignKey('product_id', 'product', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
              ->addColumn('quantity', 'integer')
              ->addColumn('employee_id', 'integer', ['null' => true])
              ->addForeignKey('employee_id', 'employee', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION'])
              ->create();
    }
}
