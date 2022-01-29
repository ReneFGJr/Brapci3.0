<?php

namespace App\Controllers;

use App\Controllers\BaseController;

$this->session = \Config\Services::session();
$language = \Config\Services::language();


helper(['boostrap','url','graphs','sisdoc_forms','form','nbr']);

define("LIBRARY", "3001");
define("LIBRARY_NAME", "BRAPCI_RESEARCH");
define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix']);
define("MODULE", 'main');
define("URL", $_SERVER['app.baseURL']);

class Main extends BaseController
{
	// https://www.richdataservices.com/showcase

	public function __construct()
	{
		$this->Socials = new \App\Models\Socials();
		$this->EventSearch = new \App\Models\EventSearch();
		$this->EventProceedings = new \App\Models\EventProceedings();
		$this->EventProceedingsIssue = new \App\Models\EventProceedingsIssue();

		helper(['boostrap','url','canvas']);
	}	

	private function cab($tp = '')
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

	function index()
		{
			$sx = $this->cab();

			$sx .= h('PAINEL',1);
			return $sx;
		}


		function painel($p='')
			{
				$Main = new \App\Models\Admin\Main();
				$NEWS = new \App\Models\Admin\News();
				//$tela = view('Pages/virtual-reality');
				$tela = view('Pages/_head');
				$tela .= view('Pages/_aside');
				$tela .= view('Pages/_main_00');
				$tela .= view('Pages/_navbar');
				$tela .= $Main->index();
				$tela .= view('Pages/dashboard');
				$tela .= view('Pages/_footer');
				return $tela;
			}		 
}

