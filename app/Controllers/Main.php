<?php

namespace App\Controllers;

use App\Controllers\BaseController;

$this->session = \Config\Services::session();
$language = \Config\Services::language();


helper(['boostrap','url','graphs','sisdoc_forms','form','nbr']);

define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix']);

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
		define("LIBRARY", "3001");
		define("LIBRARY_NAME", "BRAPCI_LABS");
		define("MODULE", "main");
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

	public function social($d1 = '', $id = '')
	{
		$cab = $this->cab('all');
		$dt = array();
		$sx = $this->Socials->index($d1,$id,$dt,$cab);
		return $sx;
	}	

	public function pdf($d1 = '', $id = '')
	{
		$pdf = new \App\Models\PDF\PDF();
		$cab = $this->cab('all');
		$dt = array();
		$sx = $pdf->index($d1,$id,$dt,$cab);
		return $sx;
	}	

	public function index()
	{
		//
		$tela = $this->cab('all');

		#### Logado
		if (isset($_SESSION['user']['id']))
			{
				$login = 'Welcome';
			} else {
				$login = $this->Socials->login(0);
			}
	
        $MENU = bsc('Menu',12);
        $menu = array();
		$menu['res'] = array('',lang("main.Brapci"),lang("main.Brapci_desc"));
		$menu['res/painel'] = array('',lang("main.BrapciPainel"),lang("main.Brapci_desc"));
        $menu['authority'] = array('',lang("main.Authority"),lang("main.Authority_desc"));
        $menu['book'] = array('',lang("main.Books"),lang("main.Books_desc"));
        $menu['journal'] = array('',lang("main.Journals"),lang("main.Journals_desc"));
        $menu['proceeding'] = array('',lang("main.Proceedings"),lang("main.Proceedings_desc"));
		$menu['patent'] = array('',lang("main.Patents"),lang("main.Patents"));
        $menu['thesi'] = array('',lang("main.Thesis"),lang("main.Thesis_info_desc"));
		$menu['api/doc'] = array('',lang("main.Api"),lang("main.Api_desc"));
        
        foreach($menu as $url => $dt)
            {
                $title = '';
                $title .= $dt[1];
                $link = '<a href="'.base_url(PATH.$url).'">';
                $linka = '</a>';
                $MENU .= bsc($link.bscard($title,$dt[2],'shadow-lg p-3 mb-5','min-height: 150px').$linka,4);
            }
    
        $MENU = bs($MENU);

        $tela .= '<div style="height: 50px;"></div>';

		$tela .= bs(
						bsc($MENU,8).
						bsc($login,4)
					);	

		$tela .= $this->EventProceedings->resume();		
		
		return $tela;
	}

	public function news($cmd='',$id='')
		{
			$News = new \App\Models\Admin\News();
			$tela = $this->cab('All');
			switch($cmd)
				{
					case 'edit':
						$tela .= $News->edit($id);
					break;

					default:
					$tela .= $News->list();
					break;
				}

			$tela .= $this->cab('footer');

			return $tela;
		}

	public function dropall($tp='')
		{
			$sx = $this->cab('all');
			$tela = '';
			$this->RDF = new \App\Models\RDF();
			$tables = array('OAI_SetSpec','OAI_ListRecords','OAI_log','rdf_concept','rdf_data','rdf_literal','');
			for ($r=0;$r < count($tables);$r++)
				{
					$table = $tables[$r];
					if (strlen($table) > 0) { $this->RDF->query('TRUNCATE '.$table); $tela .= bsmessage('Truncate '.$table,1); }
				}

			$dirs = array('.tmp/','.c','../.temp/oai','../.temp/ddi');
			for ($r=0;$r < count($dirs);$r++)
				{
					$dirn = $dirs[$r];		
					delTree($dirn);
					$tela .= bsmessage('Remove '.$dirn,1);
				}
			$tela = $sx.bs($tela);
			return $tela;
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
