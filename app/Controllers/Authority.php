<?php

namespace App\Controllers;

//define("PATH", 'Authoriry');
define("PATH",$_SERVER['app.baseURL'].'/authority');
define("LIBRARY", "202101");
helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr']);

$this->session = \Config\Services::session();
$language = \Config\Services::language();

use App\Controllers\BaseController;

$hd = new \App\Models\Header\Header();

class Authority extends BaseController
{
	private function cab($tp = '')
	{
		$hd = new \App\Models\Header\Header();
		$tela = '';
		$dt['title'] = 'Authority';
		$title = lang('authority.'.$dt['title']);
		switch ($tp) {
			case 'typping':
				$tela .= $hd->typing($title,'authority.'.$dt['title'].'_sub');
				break;
			case 'footer':
				$tela .= $hd->footer($dt);
				break;
			case 'menu':
				$tela .= $hd->menu($dt);
				break;				
			default:
				$tela .= $hd->cab($dt);
				$tela .= $hd->navbar($dt);
				$tela .= $hd->menu($dt);
				break;
		}
		return $tela;
	}
	public function index($d1='',$d2='',$d3='',$d4='')
	{
		$Authority = new \App\Models\Authority\Index();

		$tela = $this->cab('all');
		if ($d1=='')
			{
				$tela .= $this->cab('typping');
			} else {
				$tela .= $Authority->index($d1,$d2,$d3,$d4);
			}
		$tela .= $this->cab('footer');

		return $tela;
	}

	public function import2($d1='',$d2='',$d3='')
	{
		$app = new \App\Models\Authority\Import();
		$tela = $this->cab('all');

		$tela .= $app->index($d1,$d2,$d3);
		$tela .= $this->cab('footer');

		return $tela;
	}	
}
