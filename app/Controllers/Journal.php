<?php

namespace App\Controllers;

//define("PATH", 'Authoriry');
define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix'].'journal');
define("URL",$_SERVER['app.baseURL']);
define("MODULE",'journal');

define("LIBRARY", "202101");
helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr']);

use App\Controllers\BaseController;

class Journal extends BaseController
{

	private function cab($tp = '')
	{
		/*						
						$tela .= view('Pages/_navbar');
						$tela .= view('Pages/dashboard');
						
		*/

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
	
	public function index($d1='',$d2='',$d3='',$d4='')
	{
		$Journals = new \App\Models\Journal\Journals();

		$tela = $this->cab('all');
		$tela .= $Journals->index($d1,$d2,$d3,$d4);
		$tela .= $this->cab('footer');

		return $tela;
	}
}