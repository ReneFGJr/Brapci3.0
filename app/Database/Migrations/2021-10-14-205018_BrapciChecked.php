<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BrapciChecked extends Migration
{
	protected $DBGroup = 'brapci3';

	public function up()
	{
 		$this->forge->addField([
            'id_at' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'at_rdf' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'at_type' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'at_title' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'at_abstract' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'at_keyword' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'at_portuguese' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'at_english' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'at_spanish' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'at_pdf' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'at_txt' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'at_authors' => [
                'type' => 'INT',
				'default' => 0
            ], 				

            'at_chapter' => [
                'type' => 'INT',
				'default' => 0
            ], 	
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_at', true);
		$this->forge->addKey('at_rdf', true);
        $this->forge->createTable('checked');
	}

	public function down()
	{
		//
	}
}
