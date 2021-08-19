<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Proccedings extends Migration
{
	public function up()
	{
      $this->forge->addField([
            'id_ep' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ep_nome' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
            ],
            'ep_abrev' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],            
            'ep_url' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
			],	
            'ep_url_oai' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
			],	
            'ep_status' => [
                'type' => 'int',
                'default' => '0'
			],						
            'us_update' => [
                'type' => 'int'
			],																				
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_ep', true);
        $this->forge->createTable('event_proceedings');
	}

	public function down()
	{
		//
	}
}
