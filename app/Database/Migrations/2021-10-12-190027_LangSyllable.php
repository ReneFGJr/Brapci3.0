<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LangSyllable extends Migration
{
	protected $DBGroup = 'ai';

	public function up()
	{
 		$this->forge->addField([
            'id_sy' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'sy_syllable' => [
                'type' => 'VARCHAR',
                'constraint' => '10'
            ], 			
            'sy_lang' => [
                'type' => 'VARCHAR',
                'constraint' => '5'
            ],   
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_sy', true);
        $this->forge->createTable('ai_syllables');
	}

	public function down()
	{
		//
	}
}
