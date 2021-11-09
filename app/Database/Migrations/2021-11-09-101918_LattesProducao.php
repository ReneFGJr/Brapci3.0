<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LattesProducao extends Migration
{
	protected $DBGroup = 'auth';

	public function up()
	{
$this->forge->addField([
            'id_lp' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
			'lp_author' => [
				'type' => 'INT',
				'constraint' => '0',
			],
			'lp_author_total' => [
				'type' => 'INT',
				'constraint' => '0',
			],            
			'lp_brapci_rdf' => [
				'type' => 'INT',
				'constraint' => '0',
			],			
            'lp_authors' => [
                'type' => 'TEXT',
            ],			
            'lp_title' => [
                'type' => 'TEXT',
            ],             
            'lp_ano' => [
                'type' => 'VARCHAR',
                'constraint' => '4'
            ], 				
            'lp_url' => [
                'type' => 'VARCHAR',
                'constraint' => '120'
            ], 
            'lp_doi' => [
                'type' => 'VARCHAR',
                'constraint' => '120'
            ], 	
            'lp_issn' => [
                'type' => 'VARCHAR',
                'constraint' => '9'
            ],	
            'lp_journal' => [
                'type' => 'VARCHAR',
                'constraint' => '80'
            ],													
            'lp_vol' => [
                'type' => 'VARCHAR',
                'constraint' => '10'
            ],			
            'lp_nr' => [
                'type' => 'VARCHAR',
                'constraint' => '10'
            ],			
            'lp_place' => [
                'type' => 'VARCHAR',
                'constraint' => '40'
            ],			
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
		
		$this->forge->addKey('id_lp', true);
        $this->forge->createTable('LattesProducao');	
	}

	public function down()
	{
		//
	}
}
