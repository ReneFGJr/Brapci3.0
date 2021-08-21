<?php

namespace App\Controllers;

use App\Controllers\BaseController;

$this->session = \Config\Services::session();
$language = \Config\Services::language();


helper(['boostrap','url','graphs','sisdoc_forms','form']);

define("PATH","index.php/eventos/");

function cr()
	{
		return chr(13).chr(10);
	}

class Eventos extends BaseController
{
	// https://www.richdataservices.com/showcase

	public function __construct()
	{
		$this->Socials = new \App\Models\Socials();
		$this->EventSearch = new \App\Models\EventSearch();
		$this->EventProceedings = new \App\Models\EventProceedings();
		$this->EventProceedingsIssue = new \App\Models\EventProceedingsIssue();

		helper(['boostrap','url','canvas']);
		define("LIBRARY", "BRAPCI_LABS");
		define("LIBRARY_NAME", "");
	}	

	private function cab($dt=array())
		{
			$title = 'Brapci Proceedings';
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

			if (get("debug") != '')
				{
					$sx .= '<style> div { border: 1px solid #000000;"> </style>';
				}			
			return $sx;

		}

	private function navbar($dt=array())	
		{
			$title = 'BRAPCI Proceedings';
			if (isset($dt['title'])) { $title = $dt['title']; } 
			$sx = '<nav class="navbar navbar-expand-lg navbar-dark bc">'.cr();
			$sx .= '  <div class="container-fluid">'.cr();
			$sx .= '    <a class="navbar-brand" href="'.base_url(PATH).'">'.$title.'</a>'.cr();
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
			$sx .= '            <li><a class="dropdown-item" href="'.base_url(PATH.'events/').'">'.lang('proceedings.Menu.events').'</a></li>'.cr();
			$sx .= '          </ul>'.cr();
			$sx .= '        </li>'.cr();

			if ($this->Socials->perfil("#ADM"))
			{
				$sx .= '        <li class="nav-item dropdown">'.cr();
				$sx .= '          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">'.cr();
				$sx .= '            '.lang('events.proceedings').cr();
				$sx .= '          </a>'.cr();
				$sx .= '          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">'.cr();
				$sx .= '            <li><a class="dropdown-item" href="'.base_url(PATH.'proceedings').'">'.lang('events.proceedings.row').'</a></li>'.cr();
				$sx .= '            <li><a class="dropdown-item" href="'.base_url(PATH.'rdf').'">'.lang('events.rdf.row').'</a></li>'.cr();
				$sx .= '          </ul>'.cr();
				$sx .= '        </li>'.cr();
			}
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

	public function rdf($d1 = '', $id = '')
	{
		$this->RDF = new \App\Models\RDF();
		$sx = $this->cab();
		$sx .= $this->navbar();
		$dt = array();
		$sx .= $this->RDF->index($d1,$id,$dt);
		return $sx;
	}	

	public function index()
	{
		//
		$tela = $this->cab();
		$tela .= $this->navbar();

		#### Logado
		if (isset($_SESSION['user']['id']))
			{
				$login = 'Welcome';
			} else {
				$login = $this->Socials->login(0);
			}

		$tela .= bs(h('Drashboard',1),array('fluid'=>0,'g'=>5));			

		$tela .= bs(
					bsc($this->EventSearch->form(),8) .
					bsc($this->EventSearch->logo(),4)
					);

		
		$tela .= bs(
						bsc(bscard('Hello'),4).
						bsc(bscard('Hello'),4).
						bsc($login,4)
					);			
		
		return $tela;
	}

	public function proceedings($d1 = '', $id = '')
	{
		$tela = $this->cab();
		$dt = array();
		$tela .= $this->navbar();
		$tela .= $this->EventProceedings->index($d1,$id,$dt,'');
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
