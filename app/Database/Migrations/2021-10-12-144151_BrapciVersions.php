<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BrapciVersions extends Migration
{
	protected $DBGroup = 'default';

	public function up()
	{
 		$this->forge->addField([
            'id_news' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'news_title' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ], 			
            'news_content' => [
                'type' => 'TEXT',
			], 
            'n_lock' => [
				'type' => 'INT',
				'default' => 0
			], 			         
            'n_lang' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
            ],   
            'n_version' => [
                'type' => 'VARCHAR',
				'constraint' => '12'
            ], 			         
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_news', true);
        $this->forge->createTable('brapci_news');
	}

	public function down()
	{
		//
	}
}
