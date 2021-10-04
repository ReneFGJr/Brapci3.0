<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap','url','graphs','sisdoc_forms','form','nbr']);

define("LIBRARY", "3001");
define("LIBRARY_NAME", "BRAPCI_RESEARCH");
define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix']);

class Res extends BaseController
{
		public function __construct()
	{
		$this->Socials = new \App\Models\Socials();
		$this->Search = new \App\Models\Brapci\Search();
	}
	private function cab($tp = '')
	{
		$hd = new \App\Models\Header\Header();
		$tela = '';
		$dt['title'] = ' - Base de Dados em Ciência da Informação';
		switch ($tp) {
			case 'footer':
				$tela .= $hd->footer($dt);
				break;
			case 'menu':
				$tela .= $hd->footer($dt);
				break;				
			default:
				$tela .= $hd->cab($dt);
				$tela .= $hd->navbar($dt);
				$tela .= $hd->menu($dt);
				break;
		}
		return $tela;
	}

	public function index()
	{
		//
		$tela = $this->cab();
		$tela .= $this->Search->formSearch();
		return $tela;
	}
}
