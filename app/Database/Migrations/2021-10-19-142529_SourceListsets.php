<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SourceListsets extends Migration
{
    protected $DBGroup = 'brapci';
	public function up()
	{       
		$this->forge->addField([
            'id_ls' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ls_setSpec' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ], 
            'ls_setName' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],             
            'ls_description' => [
                'type' => 'TEXT',
				'default' => ''
            ], 			
            'ls_journal' => [
                'type' => 'INT',
				'default' => 0
            ], 			
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_ls', true);
        $this->forge->createTable('source_listsets');    
    
	}


	public function down()
	{
		//
	}
}
