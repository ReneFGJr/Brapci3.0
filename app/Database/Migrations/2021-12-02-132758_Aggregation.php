<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Aggregation extends Migration
{
	protected $DBGroup = 'main';

	public function up()
	{
            //https://cip.brapci.inf.br/index.php/patent/inpi/authority
	      $this->forge->addField([
            'id_source' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'source_description' => [
				'type' => 'VARCHAR',   
                'constraint' => '100' 
            ],   
            'source_url' => [
				'type' => 'VARCHAR',   
                'constraint' => '120'         
			],
            'source_type' => [
				'type' => 'VARCHAR',   
                'constraint' => '1'         
            ],            
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_source', true);
        $this->forge->createTable('Aggregation');
	}

	public function down()
	{
		//
	}
}
