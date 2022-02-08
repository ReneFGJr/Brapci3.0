<?php

namespace App\Controllers;

//define("PATH", 'Authoriry');
define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix']);
define("MODULE",'authority');
define("URL",$_SERVER['app.baseURL']);

define("LIBRARY", "202101");
helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr']);
define("PREFIX",'brapci3.');

$this->session = \Config\Services::session();
$language = \Config\Services::language();

use App\Controllers\BaseController;

$hd = new \App\Models\Header\Header();

class Authority extends BaseController
{
	function cab($tp = '')
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
		$Authority = new \App\Models\Authority\Index();

		$tela = $this->cab('all');
		$tela .= $Authority->index($d1,$d2,$d3,$d4);
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
	function v($id)
	{
		$V = new \App\Models\Brapci\V();
		$tela = $V->index($this, $id);
		return $tela;
	}	
}
