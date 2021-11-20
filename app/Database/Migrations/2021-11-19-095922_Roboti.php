<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PatentRDF extends Migration
{
	protected $DBGroup = 'roboti';

	public function up()
	{
            //https://cip.brapci.inf.br/index.php/patent/inpi/authority
	      $this->forge->addField([
            'id_task' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'task_description' => [
				'type' => 'VARCHAR',   
                'constraint' => '100' 
            ],   
            'task_url' => [
				'type' => 'VARCHAR',   
                'constraint' => '120'         
			],
            'task_w1' => [
				'type' => 'VARCHAR',   
                'constraint' => '1'         
            ],
            'task_w2' => [
				'type' => 'VARCHAR',   
                'constraint' => '1'         
            ],
            'task_w3' => [
				'type' => 'VARCHAR',   
                'constraint' => '1'         
            ],
            'task_w4' => [
				'type' => 'VARCHAR',   
                'constraint' => '1'         
            ],
            'task_w5' => [
				'type' => 'VARCHAR',   
                'constraint' => '1'         
            ],
            'task_w6' => [
				'type' => 'VARCHAR',   
                'constraint' => '1'         
            ],
            'task_w7' => [
				'type' => 'VARCHAR',   
                'constraint' => '1'         
            ],
            'task_hour' => [
				'type' => 'VARCHAR',   
                'constraint' => '2'
            ],
            'task_min' => [
				'type' => 'VARCHAR',   
                'constraint' => '2'
            ],            
            'task_day' => [
				'type' => 'INT'
            ],            
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_task', true);
        $this->forge->createTable('Task');
	
	      $this->forge->addField([
            'id_log' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'log_task' => [
				'type' => 'INT'
            ],   
            'log_result' => [
				'type' => 'TEXT'
			],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_log', true);
        $this->forge->createTable('Task_log');    
    }

	public function down()
	{
		//
	}
}
