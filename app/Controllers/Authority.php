<?php

namespace App\Controllers;

//define("PATH", 'Authoriry');
define("PATH",$_SERVER['app.baseURL'].'/authority');
helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr']);

use App\Controllers\BaseController;

$hd = new \App\Models\Header\Header();

class Authority extends BaseController
{
	private function cab($tp = '')
	{
		$hd = new \App\Models\Header\Header();
		$tela = '';
		$dt['title'] = 'Authoriry';
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
		$tela = $this->cab('all');
		$tela .= $this->cab('footer');

		return $tela;
	}

	public function import($d1='',$d2='',$d3='')
	{
		$app = new \App\Models\Authority\Import();
		$tela = $this->cab('all');

		$tela .= $app->index($d1,$d2,$d3);
		$tela .= $this->cab('footer');

		return $tela;
	}	
}
