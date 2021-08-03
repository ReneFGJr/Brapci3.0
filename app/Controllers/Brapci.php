<?php

namespace App\Controllers;

use App\Controllers\BaseController;

$this->session = \Config\Services::session();
$language = \Config\Services::language();


helper(['boostrap','url','graphs','sisdoc_forms']);

function cr()
	{
		return chr(13).chr(10);
	}

class Brapci extends BaseController
{
	// https://www.richdataservices.com/showcase

	public function __construct()
	{
		$this->Socials = new \App\Models\Socials();
		$this->Analysis = new \App\Models\Analysis();

		helper(['boostrap','url','canvas']);
		define("PATH", "brapci/");
		define("LIBRARY", "");
		define("LIBRARY_NAME", "");
	}	

	

	private function cab($dt=array())
		{
			$title = 'Brapci3 - DrashDataBoard';
			if (isset($dt['title'])) { $title = $dt['title']; } 
			$sx = '<!doctype html>'.cr();
			$sx .= '<html>'.cr();
			$sx .= '<head>'.cr();
			$sx .= '<title>'.$title.'</title>'.cr();
			$sx .= '  <meta charset="utf-8" />'.cr();
			$sx .= '  <link rel="apple-touch-icon" sizes="180x180" href="'.base_url('favicon.ico').'" />'.cr();
			$sx .= '  <link rel="icon" type="image/png" sizes="32x32" href="'.base_url('favicon.ico').'" />'.cr();
			$sx .= '  <link rel="icon" type="image/png" sizes="16x16" href="'.base_url('favicon.ico').'" />'.cr();
			$sx .= '  <!-- CSS -->'.cr();
			$sx .= '  <link rel="stylesheet" href="'.base_url('/css/bootstrap.css').'" />'.cr();
			$sx .= '  <link rel="stylesheet" href="'.base_url('/css/style.css?v0.0.8').'" />'.cr();
			$sx .= ' '.cr();
			$sx .= '  <!-- CSS -->'.cr();
			$sx .= '  <script src="'.base_url('/js/bootstrap.js?v=5.0.2').'"></script>'.cr();
			$sx .= '</head>'.cr();
			return $sx;

		}

	private function navbar($dt=array())	
		{
			$title = 'BRAPCI Labs';
			if (isset($dt['title'])) { $title = $dt['title']; } 
			$sx = '<nav class="navbar navbar-expand-lg navbar-dark bc">'.cr();
			$sx .= '  <div class="container-fluid">'.cr();
			$sx .= '    <a class="navbar-brand" href="'.base_url().'">'.$title.'</a>'.cr();
			$sx .= '    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">'.cr();
			$sx .= '      <span class="navbar-toggler-icon"></span>'.cr();
			$sx .= '    </button>'.cr();
			$sx .= '    <div class="collapse navbar-collapse" id="navbarSupportedContent">'.cr();
			$sx .= '      <ul class="navbar-nav me-auto mb-2 mb-lg-0">'.cr();
			/*
			$sx .= '        <li class="nav-item">'.cr();
			$sx .= '          <a class="nav-link active" aria-current="page" href="#">Home</a>'.cr();
			$sx .= '        </li>'.cr();
			$sx .= '        <li class="nav-item">'.cr();
			$sx .= '          <a class="nav-link" href="#">Link</a>'.cr();
    		$sx .= '		</li>'.cr();
			*/
			$sx .= '        <li class="nav-item dropdown">'.cr();
			$sx .= '          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">'.cr();
			$sx .= '            '.lang('brapci.Labs').cr();
			$sx .= '          </a>'.cr();
			$sx .= '          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">'.cr();
			$sx .= '            <li><a class="dropdown-item" href="'.base_url('brapci/labs').'">'.lang('brapci.Labs.Drashboard').'</a></li>'.cr();
			$sx .= '            <li><a class="dropdown-item" href="'.base_url('brapci/ontology').'">'.lang('brapci.Labs.Ontology').'</a></li>'.cr();
			$sx .= '            <li><a class="dropdown-item" href="'.base_url('brapci/analysis').'">'.lang('brapci.Labs.Analysis').'</a></li>'.cr();
			$sx .= '          </ul>'.cr();
			$sx .= '        </li>'.cr();

			$sx .= '        <li class="nav-item dropdown">'.cr();
			$sx .= '          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">'.cr();
			$sx .= '            '.lang('brapci.Analyse').cr();
			$sx .= '          </a>'.cr();
			$sx .= '          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">'.cr();
			$sx .= '            <li><a class="dropdown-item" href="#">'.lang('brapci.Analyse.Drashboard').'</a></li>'.cr();
			$sx .= '            <li><a class="dropdown-item" href="#">Another action</a></li>'.cr();
			$sx .= '            <li><hr class="dropdown-divider"></li>'.cr();
			$sx .= '            <li><a class="dropdown-item" href="#">Something else here</a></li>'.cr();
			$sx .= '          </ul>'.cr();
			$sx .= '        </li>'.cr();
			$sx .= '      </ul>'.cr();

			/*
			$sx .= '        <li class="nav-item">'.cr();
			$sx .= '          <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>'.cr();
			$sx .= '        </li>'.cr();
			$sx .= '      </ul>'.cr();
			*/

			/*
			$sx .= '      <form class="d-flex">'.cr();
			$sx .= '        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">'.cr();
			$sx .= '        <button class="btn btn-outline-success" type="submit">Search</button>'.cr();
			$sx .= '      </form>'.cr();
			*/

			$sx .= $this->Socials->nav_user();

			$sx .= '    </div>'.cr();
			$sx .= '  </div>'.cr();
			$sx .= '</nav>'.cr();
			return $sx;
		}

	public function social($d1 = '', $id = '')
	{
		$cab = $this->cab();
		$dt = array();
		$sx = $this->Socials->index($d1,$id,$dt,$cab);
		return $sx;
	}	

	public function index()
	{
		//
		$tela = $this->cab();
		$tela .= $this->navbar();
		$dt = array();
		$d[0] = array('image'=>'http://images.unsplash.com/photo-1492305175278-3b3afaa2f31f?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=1080&fit=max','link'=>'');
		$d[1] = array('image'=>'https://images3.alphacoders.com/102/102609.jpg','link'=>'');
		$d[2] = array('image'=>'https://static.escolakids.uol.com.br/2019/07/paisagem-natural-e-paisagem-cultural.jpg','link'=>'');
		
		$tela .= bscarousel($d);

		#### Logado
		if (isset($_SESSION['user']['id']))
			{
				$login = 'Welcome';
			} else {
				$login = $this->Socials->login(0);
			}

		$tela .= bs(h('Drashboard',1),array('fluid'=>0,'g'=>5));
		$tela .= bs(
						bsc(bscard('Hello'),4).
						bsc(bscard('Hello'),4).
						bsc($login,4)
					);

		//$tela .= bs(bsc(graph_demo(),12));
		
		return $tela;
	}

	public function analysis($d1 = '', $id = '')
	{
		$tela = $this->cab();
		$tela .= $this->navbar();
		$tela .= $this->Analysis->index($d1,$id);
		return $tela;
	}

	public function labs()
	{
		//
		$tela = $this->cab();
		$tela .= $this->navbar();
		$tela .= bs(h('Labs',1),array('fluid'=>0,'g'=>5));
		$tela .= bs(bsc(bscard('Hello'),4).bsc(bscard('Hello'),4).bsc(bscard('Hello'),4));
		
		return $tela;
	}	

	public function ontology()
	{
		//
		$tela = $this->cab();
		$tela .= $this->navbar();
		$tela .= bs(h('Ontology',1),array('fluid'=>0,'g'=>5));
		$tela .= bs(bsc(bscard('Hello'),4).bsc(bscard('Hello'),4).bsc(bscard('Hello'),4));
		
		return $tela;
	}	
}
