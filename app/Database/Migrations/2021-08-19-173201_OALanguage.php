<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OALanguage extends Migration
{
	public function up()
	{
      $this->forge->addField([
            'id_lg' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'lg_code' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
            ],            
            'lg_name' => [
                'type' => 'VARCHAR',
                'constraint' => '80'
			],			
            'lg_lang' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_lg', true);
        $this->forge->createTable('OA_Language');
	}

	public function down()
	{
		//
	}
}
