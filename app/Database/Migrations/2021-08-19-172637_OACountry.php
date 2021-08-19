<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OACountry extends Migration
{
	public function up()
	{
      $this->forge->addField([
            'id_ct' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ct_code' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
            ],            
            'ct_name' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
			],			
            'ct_lang' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_ct', true);
        $this->forge->createTable('OA_Country');
	}

	public function down()
	{
		//
	}
}
