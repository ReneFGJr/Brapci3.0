<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OAIListRecords extends Migration
{
	public function up()
	{
      $this->forge->addField([
            'id_ls' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'li_journal' => [
                'type' => 'INT',
            ],            
            'li_issue' => [
                'type' => 'INT',
            ],            
            'li_ref' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
			],	
            'li_setspec' => [
                'type' => 'INT',
			],            		
            'li_datestamp' => [
                'type' => 'datetime',
            ],             
            'li_status' => [
				'type' => 'VARCHAR',
                'constraint' => '10'
            ], 
            'li_process' => [
                'type' => 'INT',
				'defaukt' => 0
            ],	
            'li_local_file' => [
				'type' => 'VARCHAR',
                'constraint' => '100'
            ],					 			          
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_ls', true);
        $this->forge->createTable('OAI_ListRecords');
	}

	public function down()
	{
		//
	}
}
