<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProccedingsIssue extends Migration
{
	public function up()
	{
      $this->forge->addField([
            'id_epi' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'epi_procceding' => [
                'type' => 'int',
				'default' => 0,
			],			
            'epi_year' => [
                'type' => 'int',
            ],
            'epi_edition' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],			
            'epi_edition_name' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],			
            'epi_about' => [
                'type' => 'TEXT',
            ],			
            'epi_date_start' => [
                'type' => 'int',
            ],
            'epi_date_end' => [
                'type' => 'int',
            ],
            'epi_place' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
			],																				
            'epi_url' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
			],	
            'epi_url_oai' => [
                'type' => 'VARCHAR',
                'constraint' => '200'
			],			
            'epi_source' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
			],	
            'epi_status' => [
                'type' => 'int',
                'default' => '0'
			],					
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_epi', true);
        $this->forge->createTable('event_proceedings_issue');
	}

	public function down()
	{
		//
	}
}
