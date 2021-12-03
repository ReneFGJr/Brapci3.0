<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Sus2 extends Migration
{
	protected $DBGroup = 'ai';
	public function up()
	{
$this->forge->addField([
            'id_st' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'st_database' => [
                'type' => 'INT',
				'default' => 0
            ], 			
            'st_strategy' => [
                'type' => 'TEXT'
            ], 			
            'st_justify' => [
                'type' => 'TEXT',
            ], 
            'st_status' => [
                'type' => 'INT',
				'default' => 0
            ],             
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
		
		$this->forge->addKey('id_st', true);
        $this->forge->createTable('SystematicReviews_Strategy');    
	}

	public function down()
	{
		//
	}
}
