<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AuthorityWords extends Migration
{
    protected $DBGroup = 'auth';

	public function up()
	{
      $this->forge->addField([
            'id_w' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'w_term' => [
				'type' => 'VARCHAR',   
                'constraint' => '30'
			],
            'w_term_o' => [
				'type' => 'VARCHAR',   
                'constraint' => '30'
			],            
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_w', true);
        $this->forge->addKey('w_term', true);
        $this->forge->createTable('AuthorityWords');
	}

	public function down()
	{
		//
	}
}
