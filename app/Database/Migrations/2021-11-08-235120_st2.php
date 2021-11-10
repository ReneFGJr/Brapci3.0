<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SystematicReviewsStidies2 extends Migration
{
	protected $DBGroup = 'ai';
	public function up()
	{        
        $this->forge->addField([
            'id_c' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'c_study' => [
                'type' => 'INT',
				'default' => 0
            ],             
            'author' => [
                'type' => 'TEXT',
            ], 				
            'title' => [
                'type' => 'TEXT',
            ], 	
            'journal' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],             
            'year' => [
                'type' => 'INT',
				'default' => 0
            ],  
            'volume' => [
                'type' => 'VARCHAR',
                'constraint' => '10'
            ], 
            'number' => [
                'type' => 'VARCHAR',
                'constraint' => '10'
            ],                                    
            'pages' => [
                'type' => 'VARCHAR',
                'constraint' => '15'
            ],
            'month' => [
                'type' => 'VARCHAR',
                'constraint' => '15'
            ],            
            'doi' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'abstract' => [
                'type' => 'TEXT',
            ],             
            'note' => [
                'type' => 'TEXT',
            ],             
            'eprint' => [
                'type' => 'TEXT',
            ], 
            'keyword' => [
                'type' => 'TEXT',
            ],  
            'publisher' => [
                'type' => 'VARCHAR',
                'constraint' => '60'
            ],
            'language' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],
            'source' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
            ],                                         
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);		
		$this->forge->addKey('id_c', true);
        $this->forge->createTable('SystematicReviews_Corpus');        
	}

	public function down()
	{
		//
	}
}
