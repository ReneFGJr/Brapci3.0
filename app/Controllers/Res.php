<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap','url','graphs','sisdoc_forms','form','nbr']);

define("LIBRARY", "3001");
define("LIBRARY_NAME", "BRAPCI_RESEARCH");
define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix']);
define("MODULE",'res');
define("URL",$_SERVER['app.baseURL']);

class Res extends BaseController
{
		public function __construct()
	{
		$this->Socials = new \App\Models\Socials();
		$this->Search = new \App\Models\Brapci\Search();
	}
	function cab($tp = '')
	{
		$hd = new \App\Models\Header\Header();
		$tela = '';
		$dt['title'] = 'Authority';
		$dt['menu'][''] = 'main';
		$dt['menu']['index/list'] = 'list';
		
		$title = lang(MODULE.'.'.$dt['title']);
		switch ($tp) {
			case 'typping':
				$tela .= $hd->typing($title,lang(MODULE.'.'.$dt['title'].'_sub'));
				break;
			case 'footer':
				$tela .= view('Pages/_footer');
				break;
			case 'menu':
				$tela .= $hd->menu($dt);
				break;				
			default:
				$tela = view('Pages/_head');
				$tela .= view('Pages/_aside');
				$tela .= view('Pages/_main_00');
				$tela .= view('Pages/_navbar');
				$tela .= view('Pages/_menu_top',$dt);
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

	function v($id)
		{
			$V = new \App\Models\Brapci\V();
			$tela = $V->index($this,$id);
			return $tela;
		}

		function painel($p='')
			{
				//$tela = view('Pages/virtual-reality');
				switch($p)
					{
						default:
						$tela = view('Pages/_head');
						$tela .= view('Pages/_aside');
						$tela .= view('Pages/_main_00');
						$tela .= view('Pages/_navbar');
						$tela .= view('Pages/dashboard');
						$tela .= view('Pages/_footer');
						break;
					}
				
				return $tela;
			}
}
