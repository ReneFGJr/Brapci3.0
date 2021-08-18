<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProccedingsIsseSections extends Migration
{
	public function up()
	{
      $this->forge->addField([
            'id_eps' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'eps_nome' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'eps_acron' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'eps_type' => [
                'type' => 'int',
				'default' => 0,
            ],
            'eps_update' => [
                'type' => 'int'
			],	
            'eps_status' => [
                'type' => 'int',
                'default' => '0'
			],																						
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_eps', true);
        $this->forge->createTable('event_proceedings_sections');
	}

	public function down()
	{
		//
	}
}
