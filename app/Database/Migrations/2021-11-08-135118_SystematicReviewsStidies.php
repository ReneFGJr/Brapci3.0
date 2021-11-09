<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SystematicReviewsStidies extends Migration
{
	protected $DBGroup = 'ai';
	public function up()
	{
$this->forge->addField([
            'id_sr' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'sr_title' => [
                'type' => 'TEXT',
            ], 
            'sr_status' => [
                'type' => 'INT',
				'default' => 0
            ], 			
            'sr_user' => [
                'type' => 'INT',
				'default' => 0
            ], 			
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
		
		$this->forge->addKey('id_sr', true);
        $this->forge->createTable('SystematicReviews_Studies');

$this->forge->addField([
            'id_sp' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'sr_study' => [
                'type' => 'INT',
				'default' => 0
            ],			
            'sp_field' => [
                'type' => 'INT',
				'default' => 0
            ], 				
            'sp_context' => [
                'type' => 'TEXT',
            ], 			
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
		
		$this->forge->addKey('id_sp', true);
        $this->forge->createTable('SystematicReviews_Protocol');	

$this->forge->addField([
            'id_fs' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'fs_field' => [
                'type' => 'VARCHAR',
                'constraint' => '120'
            ], 				
            'fs_context' => [
                'type' => 'TEXT',
            ], 			
            'fs_type' => [
                'type' => 'TEXT',
            ], 			
            'fs_sample' => [
                'type' => 'TEXT',
            ], 			
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
		
		$this->forge->addKey('id_fs', true);
        $this->forge->createTable('SystematicReviews_Fields');			

$this->forge->addField([
            'id_fr' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'fr_aliena' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
            ],             
            'fr_group' => [
                'type' => 'VARCHAR',
                'constraint' => '120'
            ], 				
            'fr_subgroup' => [
                'type' => 'VARCHAR',
                'constraint' => '120'
            ], 							
            'fr_field' => [
                'type' => 'INT',
				'default' => 0
            ], 			
            'fr_order' => [
                'type' => 'INT',
				'default' => 0
            ], 	           
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
		
		$this->forge->addKey('id_fr', true);
        $this->forge->createTable('SystematicReviews_Group');			
	}

	public function down()
	{
		//
	}
}