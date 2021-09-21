<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BrapciPersistentID extends Migration
{
	public function up()
	{
        $this->forge->addField([
            'id_rq' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'rq_ip' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'rq_doi' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],			
            'rq_type' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
			],
            'rq_filename' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
			],	
            'rq_user' => [
                'type' => 'VARCHAR',
                'constraint' => '30'
			],
            'rq_pass' => [
                'type' => 'VARCHAR',
				'constraint' => '40'
			],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_rq', true);
        $this->forge->createTable('brapci_doi_request');
	}

	public function down()
	{
		//
	}
}
