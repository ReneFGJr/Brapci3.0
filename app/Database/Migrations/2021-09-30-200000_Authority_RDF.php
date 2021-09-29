<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Authority_RdfMame extends Migration
{

    protected $DBGroup = 'auth';

	public function up()
	{
      $this->forge->addField([
            'id_n' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'n_name' => [
                'type' => 'TEXT',
			], 
            'n_lock' => [
				'type' => 'INT',
				'default' => 0
			], 			         
            'n_lang' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
            ],            
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_n', true);
        $this->forge->createTable('rdf_name');

      $this->forge->addField([
            'id_prefix' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'prefix_ref' => [
                'type' => 'VARCHAR',
                'constraint' => '30'
			], 
            'prefix_url' => [
				'type' => 'TEXT',
			], 			         
            'prefix_ativo' => [
                'type' => 'INT',
                'default' => '1'
            ],            
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_prefix', true);
        $this->forge->createTable('rdf_prefix');

        $this->forge->addField([
            'id_d' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'd_r1' => [
                'type' => 'INT',
            ],   
            'd_p' => [
				'type' => 'INT',            
			], 			         
            'd_r2' => [
                'type' => 'INT',
            ],            
            'd_literal' => [
				'type' => 'INT',			
			],
            'd_library' => [
                'type' => 'INT',
			],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_d', true);
        $this->forge->createTable('rdf_data');

        $this->forge->addField([
            'id_cc' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'cc_class' => [
                'type' => 'INT',
            ],   
            'cc_pref_term' => [
				'type' => 'INT',            
			], 			         
            'cc_use' => [
                'type' => 'INT',
            ],            
            'cc_origin' => [
                'type' => 'VARCHAR',
                'constraint' => '80'
			],			
            'cc_origin' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
            ],
            'cc_library' => [
                'type' => 'INT',
			],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_cc', true);
        $this->forge->createTable('rdf_concept');
        
        $this->forge->addField([
            'id_c' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'c_class' => [
                'type' => 'VARCHAR',
                'constraint' => '80'
            ],            
            'c_prefix' => [
                'type' => 'INT',
            ],            
            'c_type' => [
                'type' => 'VARCHAR',
                'constraint' => '1'
			],			
            'c_url' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
            ],            
            'c_equivalent' => [
				'type' => 'INT',            
			],  
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_c', true);
        $this->forge->createTable('rdf_class');        
	}

	public function down()
	{
		//
	}
}
