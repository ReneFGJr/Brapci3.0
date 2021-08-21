<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OAISetSpec extends Migration
{
	public function up()
	{
      $this->forge->addField([
            'id_ss' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ss_journal' => [
                'type' => 'INT',
            ],            
            'ss_issue' => [
                'type' => 'INT',
            ],            
            'ss_ref' => [
                'type' => 'VARCHAR',
                'constraint' => '80'
			],			
            'ss_group' => [
                'type' => 'INT',
            ],            
            'ss_name' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
            ],  
            'ss_description' => [
                'type' => 'TEXT',
            ],                        
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_ss', true);
        $this->forge->createTable('OAI_SetSpec');
	}

	public function down()
	{
		//
	}
}
