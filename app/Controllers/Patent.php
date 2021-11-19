<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr','sessions']);
$session = \Config\Services::session();

define("LIBRARY", "3001");
define("LIBRARY_NAME", "BRAPCI_RESEARCH");
define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix']);
define("MODULE", 'patent/');
define("URL", $_SERVER['app.baseURL']);
define("PREFIX",'brapci_patent.');

echo '<pre>';
echo '===>'.PATH;
exit;

$this->Socials = new \App\Models\Socials();

class Patent extends BaseController
{
	public function __construct()
	{
		$this->Socials = new \App\Models\Socials();
		$this->Search = new \App\Models\Search\Search();
	}
	function cab($tp = '')
	{
		$hd = new \App\Models\Header\Header();
		$tela = '';
		$dt['title'] = 'Patent INPI';
		$dt['menu'][''] = 'main';
		$dt['menu'][PATH.MODULE.'/index/list'] = 'list';
		$dt['menu'][PATH.MODULE.'/inpi'] = 'INPI';

		$title = lang(MODULE . '.' . $dt['title']);
		switch ($tp) {
			case 'typping':
				$tela .= $hd->typing($title, lang(MODULE . '.' . $dt['title'] . '_sub'));
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
				$tela .= view('Pages/_menu_top', $dt);
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

	public function inpi($d1='',$d2='',$d3='',$d4='')
		{
			$tela = '';
			$INPI = new \App\Models\INPI\Index();

			$tela = $INPI->index($d1,$d2,$d3,$d4);
			return $tela;
		}

	public function elastic()
	{
		//
		$Elastic = new \App\Models\Search\ElasticSearch();
		$tela = $this->cab();
		$tela .= $Elastic->formTest();
		return $tela;
	}	

	function v($id)
	{
		$V = new \App\Models\Brapci\V();
		$tela = $V->index($this, $id);
		return $tela;
	}

	function download($id = 0)
	{
		$PDF = new \App\Models\PDF\PDF();
		$PDF->download($id);
	}

	public function social($d1 = '', $id = '')
	{
		$cab = $this->cab('all');
		$dt = array();
		$sx = $this->Socials->index($d1, $id, $dt, $cab);
		return $sx;
	}

	function painel($p = '')
	{
		//$tela = view('Pages/virtual-reality');
		switch ($p) {
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
