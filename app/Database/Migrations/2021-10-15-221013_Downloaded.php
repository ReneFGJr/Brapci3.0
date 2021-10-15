<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Downloaded extends Migration
{
	protected $DBGroup = 'click';

	public function up()
	{
		// php spark db:create brapci_click
		// $this->forge->createDatabase('brapci_click');

 		$this->forge->addField([
            'id_dw' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'dw_rdf' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'dw_download' => [
                'type' => 'INT',
				'default' => 0
            ], 			

            'dw_type' => [
                'type' => 'INT',
				'default' => 0
            ], 			
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
		
		$this->forge->addKey('id_dw', true);
		$this->forge->addKey('dw_rdf', true);
        $this->forge->createTable('download');
	}

	public function down()
	{
		//
	}
}
