<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
	public function up()
	{
        $this->forge->addField([
            'id_us' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'us_nome' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'us_email' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
			],
            'us_image' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
			],	
            'us_genero' => [
                'type' => 'VARCHAR',
                'constraint' => '1'
			],
            'us_verificado' => [
                'type' => 'INT',
				'default' => 0
			],
            'us_login' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
			],
            'us_password' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
			],
            'us_password_method' => [
                'type' => 'VARCHAR',
                'constraint' => '3'
			],			
            'us_oauth2' => [
                'type' => 'VARCHAR',
                'constraint' => '20'
			],
            'us_lastaccess' => [
                'type' => 'int'
			],																				
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_us', true);
        $this->forge->createTable('users2');
	}

	public function down()
	{
		//
	}
}
