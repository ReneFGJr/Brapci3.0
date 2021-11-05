<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap','url','graphs','sisdoc_forms','form','nbr']);

define("LIBRARY", "3001");
define("LIBRARY_NAME", "BRAPCI_RESEARCH");
define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix']);
define("MODULE",'ai');
define("URL", $_SERVER['app.baseURL']);



class AI extends BaseController
{
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

	public function index($d1='',$d2='',$d3='')
	{
		$AI = new \App\Models\AI\Index();
		$tela = $this->cab();
		$tela .= $AI->index();
		return $tela;
	}

	public function nlp($d1='',$d2='',$d3='')
	{
		$NLP = new \App\Models\AI\NLP();
		$tela = $this->cab();
		$tela .= $NLP->index($d1,$d2,$d3);
		return $tela;
	}
}
