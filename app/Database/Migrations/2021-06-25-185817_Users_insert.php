<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users2 extends Migration
{
	public function up()
	{
        $data = [
                'us_nome' => 'admin',
                'us_email' => 'admin',
                'us_image' => '',
                'us_genero' => '',
                'us_verificado' => '1',
                'us_login' => 'admin',
                'us_password' => '21232f297a57a5a743894a0e4a801fc3',
                'us_password_method' => 'MD5',
                'us_oauth2' => '',
                'us_lastaccess' => ''
        ];
        $this->db->table('users2')->insert($data);

	}

	public function down()
	{
		//
	}
}
