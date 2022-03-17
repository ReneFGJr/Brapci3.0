<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BookSelf extends Migration
{
    protected $DBGroup = 'books';

	public function up()
	{
 		$this->forge->addField([
            'id_bs' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'bs_title' => [
                'type' => 'TEXT',
            ], 
            'bs_rdf' => [
                'type' => 'INT',
				'default' => 0
            ], 			
            'bs_status' => [
                'type' => 'INT',
				'default' => 0
            ], 			
            'bs_user' => [
                'type' => 'INT',
				'default' => 0
            ], 			
			'bs_agree' => [
				'type' => 'INT',
				'default' => 0
            ], 			
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
		
		$this->forge->addKey('id_bs', true);
        $this->forge->createTable('book_self');
	}

	public function down()
	{
		//
	}
}
