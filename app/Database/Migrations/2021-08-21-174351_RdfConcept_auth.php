<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RdfConceptAuth extends Migration
{
    protected $DBGroup = 'auth';

	public function up()
	{
      $this->forge->addField([
            'id_cc' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'cc_class' => [
                'type' => 'INT',
            ],   
            'cc_pref_term' => [
				'type' => 'INT',            
			], 			         
            'cc_use' => [
                'type' => 'INT',
            ],            
            'cc_origin' => [
                'type' => 'VARCHAR',
                'constraint' => '80'
			],			
            'cc_origin' => [
                'type' => 'VARCHAR',
                'constraint' => '150'
            ],
            'cc_library' => [
                'type' => 'INT',
			],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp'
        ]);
        $this->forge->addKey('id_cc', true);
        $this->forge->addKey('cc_class', true);
        $this->forge->addKey('cc_pref_term', true);
        $this->forge->createTable('rdf_concept');
	}

	public function down()
	{
		//
	}
}
