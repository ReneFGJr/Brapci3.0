<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PatentRDF extends Migration
{
	protected $DBGroup = 'inpi';

	public function up()
	{
	      $this->forge->addField([
            'id_a' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'a_class' => [
				'type' => 'VARCHAR',   
                'constraint' => '1' 
            ],   
            'a_uri' => [
				'type' => 'VARCHAR',   
                'constraint' => '120'         
			],
            'a_use' => [
                'type' => 'INT',
            ],
            'a_prefTerm' => [
                'type' => 'VARCHAR',
                'constraint' => '120'
			],
            'a_lattes' => [
                'type' => 'VARCHAR',
                'constraint' => '24'
			],         
            'a_brapci' => [
                'type' => 'INT',
                'default' => '0'
			],                
            'a_orcid' => [
                'type' => 'VARCHAR',
                'constraint' => '30'
			],  
            'a_UF' => [
                'type' => 'VARCHAR',
                'constraint' => '2'
			],                      
            'a_country' => [
                'type' => 'VARCHAR',
                'constraint' => '3'
			],                      
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_a', true);
        $this->forge->addKey('a_prefTerm', true);
        $this->forge->createTable('AuthorityNames');        	
	}

	public function down()
	{
		//
	}
}
