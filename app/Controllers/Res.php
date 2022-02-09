<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap', 'url', 'graphs', 'sisdoc_forms', 'form', 'nbr','sessions']);
$session = \Config\Services::session();

define("LIBRARY", "3001");
define("LIBRARY_NAME", "BRAPCI_RESEARCH");
define("PATH",$_SERVER['app.baseURL'].$_SERVER['app.sufix']);
define("MODULE", 'res/');
define("URL", $_SERVER['app.baseURL']);
define("PREFIX",'brapci.');

$this->Socials = new \App\Models\Socials();

class Res extends BaseController
{
	public function __construct()
	{
		$this->Search = new \App\Models\Search\Search();
		$this->Socials = new \App\Models\Socials();
	}

	function cab($tp = '',$dt=array())
	{
		$hd = new \App\Models\Header\Header();
		$tela = '';
		$dt['title'] = 'Brapci 3.0';
		$dt['menu'][''] = 'main';
		$dt['menu']['index/list'] = 'list';

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
			case 'user':
				$tela .= view('Header/header',$dt);
				$tela .= view('Brapci/menu_top',$dt);
				break;
			default:
				$tela .= view('Header/header',$dt);
				$tela .= view('Brapci/menu_top',$dt);

				//$tela .= view('Pages/_aside');
				//$tela .= view('Pages/_main_00');
				//$tela .= view('Pages/_navbar');
				break;
		}
		return $tela;
	}

	public function index()
	{
		//
		$tela = $this->cab("user");
		$tela .= view('Brapci/collections');
		$tela .= $this->Search->formSearch();
		return $tela;
	}


	public function about($d1='',$d2='',$d3='',$d4='')
	{
		//
		$tela = $this->cab("user");
		$dt = array();
		$tela .= view('Brapci/about_pt',$dt);

		return $tela;
	}	

	public function help($d1='',$d2='',$d3='',$d4='')
	{
		//
		$tela = $this->cab("user");
		$dt = array();
		$tela .= view('Brapci/help_pt',$dt);

		return $tela;
	}	

	public function lattesdata($d1='',$d2='',$d3='',$d4='')
	{
		//
		$tela = $this->cab("user");
		$dt = array();
		$LattesData = new \App\Models\Lattes\LattesData();
		$tela .= $LattesData->process();

		return $tela;
	}	

	public function research($d1='',$d2='',$d3='',$d4='')
	{
		//
		$tela = $this->cab("user");
		$dt = array();
		$tools = new \App\Models\AI\Tools();
		$tela .= $tools->index($d1,$d2,$d3,$d4);

		return $tela;
	}	

	public function oai($d1='',$d2='',$d3='',$d4='',$d5='')
		{
			$OAI = new \App\Models\OaiPmhServer\Index();
			$OAI->index($d1,$d2,$d3,$d4,$d5);
		}

	public function admin($d1='',$d2='',$d3='',$d4='')
	{
		//
		$tela = $this->cab("user");
		switch($d1)
			{
				case 'lattes':
					$Lattes = new \App\Models\Journal\Journals();
					$tela .= $Lattes->index($d2,$d3,$d4);
					break;
				case 'proceeding':
					$Journal = new \App\Models\Journal\Journals();
					$tela .= $Journal->index($d2,$d3,$d4);
					break;
				case 'journal':
					$Journal = new \App\Models\Journal\Journals();
					$tela .= $Journal->index($d2,$d3,$d4);
					break;		
				case 'issue':
					$JournalIssue = new \App\Models\Journal\JournalIssue();
					switch($d2)
						{
						case 'check':
							$d3 = round($d3);
							$tela = $JournalIssue->check_issue($d3);
							break;
						}
					
			}
		return $tela;
	}	

	public function benancib($d1='',$d2='',$d3='',$d4='')
	{
		$dt['collection'] = mb_strtoupper(lang('brapci.Benancib'));
		$tela = $this->cab("user",$dt);

		$Benancib = new \App\Models\Benancib\Index();
		$tela .= $Benancib->Index($d1,$d2,$d3,$d4);
		return $tela;
	}

	public function patent($d1='',$d2='',$d3='',$d4='')
	{
		$dt['collection'] = mb_strtoupper(lang('brapci.PatentBR'));
		$tela = $this->cab("user",$dt);

		$Patent = new \App\Models\Patent\Index();
		$tela .= $Patent->Index($d1,$d2,$d3,$d4);
		return $tela;
	}	

	public function authoriry($d1='',$d2='',$d3='',$d4='')
	{
		$dt['collection'] = mb_strtoupper(lang('brapci.Authoriry'));
		$tela = $this->cab("user",$dt);
		//$Book = new \App\Models\Book\Books();
		//$tela .= $Book->index($d1,$d2,$d3,$d4);
		return $tela;
	}	

	public function book($d1='',$d2='',$d3='',$d4='')
	{
		$dt['collection'] = mb_strtoupper(lang('brapci.Books'));
		$tela = $this->cab("user",$dt);
		$Book = new \App\Models\Book\Books();
		$tela .= $Book->index($d1,$d2,$d3,$d4);
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

	function v($id,$act='')
	{
		$V = new \App\Models\Brapci\V();
		$tela = $V->index($this, $id,$act);
		return $tela;
	}

	function a($id,$act='')
	{
		$RDF = new \App\Models\Rdf\RDF();
		$tela = $this->cab();
		$tela .= bs(bsc($RDF->form($id),12));
		$tela .= $this->cab("footer");	
		return $tela;
	}

	function ontology($d1='',$d2='',$d3='',$d4='')
	{
		$Ontology = new \App\Models\Brapci\Ontology();
		$tela = $this->cab();		
		$tela .= bs($Ontology->index($d1,$d2,$d3,$d4));
		$tela .= $this->cab("footer");		
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

	function security($url='')
		{
			if (isset($_SESSION['id']) == true)
				{
					$user = $_SESSION['id'];
				} else {
					echo '===>REDIRECT '.$url;
					return 0;
				}
		}

	function painel($p = '')
	{
		//$tela = view('Pages/virtual-reality');
		$this->security(PATH.MODULE);		
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
