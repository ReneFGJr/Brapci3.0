<?php

namespace App\Controllers;

define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix']);

use App\Controllers\BaseController;

$hd = new \App\Models\Header\Header();

class V extends BaseController
{
	public function index($d1='',$d2='',$d3='',$d4='')
	{
		$tela = metarefresh(PATH.'res/v/'.$d1.'/'.$d2.'/'.$d3.'/'.$d4);
		return $tela;
	}	
}
