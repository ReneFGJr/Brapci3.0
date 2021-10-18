<?php

namespace App\Controllers;

//define("PATH", 'Authoriry');
define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix'].'social');

use App\Controllers\BaseController;
helper(['boostrap', 'url', 'sisdoc_forms', 'form']);
$session = \Config\Services::session();

class Social extends BaseController
{
	public function index()
	{
		$tela = metarefresh(base_url());
		return $tela;
	}
	public function ajax()
	{
		$Social = new \App\Models\Socials();
		$sx = $Social->index();
		return $sx;
	}
}
