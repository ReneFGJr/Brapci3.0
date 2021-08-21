<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OAI extends Migration
{
	public function up()
	{
      $this->forge->addField([
            'id_log' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'log_id' => [
                'type' => 'VARCHAR',
                'constraint' => '16'
            ],             
            'log_journal' => [
                'type' => 'INT',
            ],            
            'log_issue' => [
                'type' => 'INT',
            ],            
            'log_action' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
			],			
            'log_total' => [
                'type' => 'INT',
            ],            
            'log_new' => [
                'type' => 'INT',
            ],            
            'log_del' => [
                'type' => 'INT',
            ],
            'log_token' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
			],			            
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_log', true);
        $this->forge->createTable('OAI_log');
	}

	public function down()
	{
		//
	}
}
