<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BrapciMain extends Migration
{
    protected $DBGroup = 'main';

	public function up()
	{
      $this->forge->addField([
            'id_mn' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'mn_name' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
			],		
            'mn_text' => [
                'type' => 'TEXT',
			],		            	
            'mn_origin' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
            ],
            'mn_library' => [
                'type' => 'INT',
			],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_mn', true);
        $this->forge->createTable('main');
	}

	public function down()
	{
		//
	}
}
