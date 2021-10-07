<?php

namespace App\Controllers;

//define("PATH", 'Authoriry');
define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix'].'journal');
define("URL",$_SERVER['app.baseURL']);

define("LIBRARY", "202101");
helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr']);

use App\Controllers\BaseController;

class Journal extends BaseController
{

	private function cab($tp = '')
	{
		$hd = new \App\Models\Header\Header();
		$tela = '';
		$dt['title'] = 'Journals';
		$title = lang('journals.'.$dt['title']);
		switch ($tp) {
			case 'typping':
				$tela .= $hd->typing($title,'journals.'.$dt['title'].'_sub');
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
		$Journals = new \App\Models\Journal\Journals();

		$tela = $this->cab('all');
		$tela .= $Journals->index($d1,$d2,$d3,$d4);
		$tela .= $this->cab('footer');

		return $tela;
	}
}
