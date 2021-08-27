<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OpenDataPreposition extends Migration
{
	public function up()
	{
      $this->forge->addField([
            'id_prep' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'prep_name' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
			],			
            'prep_lang' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_prep', true);
        $this->forge->createTable('OA_Preposition');
	}

	public function down()
	{
		//
	}
}
