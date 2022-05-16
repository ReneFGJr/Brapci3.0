<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Model;

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
			case 'header':
				$tela .= view('Header/header',$dt);
				break;
			case 'user':
				$tela .= view('Header/header',$dt);
				$tela .= view('Brapci/menu_top',$dt);
				break;
			default:
				$tela .= view('Header/header',$dt);
				$tela .= view('Brapci/menu_top',$dt);
				$tela .= cookies_acept();

				//$tela .= view('Pages/_aside');
				//$tela .= view('Pages/_main_00');
				//$tela .= view('Pages/_navbar');
				break;
		}
		return $tela;
	}

	/***************************************************************** PRIVATE */
		private function header()
			{

			}

		private function navbar()
			{

			}

		private function logo()
			{
				$IV = new \App\Models\_VisualIdentify\Brapci();
				$height = 150;
				$sx = '<div style="height: '.$height.'px;" class="text-center">'.$IV->logo_animage($height).'</div>';				
				$sx = bs(bsc($sx,12));
				return $sx;
			}			
	

	public function index()
	{
		$Services = new \App\Models\Brapci\Services();
		//
		$tela = $this->cab("user");
		$tela .= view('Brapci/collections');
		$tela .= $this->logo();
		$tela .= $this->Search->formSearch();
		$tela .= $Services->index();
		return $tela;
	}

	function indexes($tp='',$lt='')
		{
			$RDF = new \App\Models\Rdf\RDF();
			$tela = $this->cab("user");
			$tela .= view('Brapci/collections');

			switch($tp)
				{
					default:
						if ($tp != '')
							{
								$tela .= bs(bsc(h(lang('rdf.'.$tp),1),12));
								$tela .= $RDF->show_index($tp,$lt);
							} else {
								$tela .= $RDF->list_indexes($tp,$lt);
							}
						
						break;
				}

						
			$tela .= $this->cab("footer");
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

	function rdf($d1='',$d2='',$d3='',$d4='',$d5='')
		{
			$cab = $this->cab('head');
			$RDF = new \App\Models\Rdf\RDF();
			$tela = $RDF->index($d1,$d2,$d3,$d4,$d5,$cab);
			return $tela;
		}

	function popup($d1='',$d2='',$d3='',$d4='',$d5='')
	{	
		$sx = '';
		$cab = $this->cab('header');
		switch($d1)
			{
				case 'myfiles':
					$MyFiles = new \App\Models\Brapci\MyFiles();
					$sx .= $MyFiles->upload($cab,$d2,$d3,$d4,$d5);
					break;
				default:
					$sx = $cab;
					$sx .= bsmessage('Popup não encontrado - '.$d1);
					break;
			}
		return $sx;
	}
	public function oai($d1='',$d2='',$d3='',$d4='',$d5='')
		{
			$OAI = new \App\Models\OaiPmhServer\Index();
			$OAI->index($d1,$d2,$d3,$d4,$d5);
		}

	public function ai($d1='',$d2='',$d3='',$d4='',$d5='')
		{
			$sx = $this->cab();
			$sx .= breadcrumbs();
			switch($d1)
				{
					case 'nlp':
						$AI = new \App\Models\AI\NLP();
						$sa = $AI->index($d2,$d3,$d4,$d5);
						break;
					default:
						$sa = bsmessage(lang('ai.not_found ' .$d1),3);
				}	
			$sx .= bs(bsc($sa,12));
			$sx .= $this->cab("footer");
			return $sx;
		}

	public function file($d1=0,$d2='',$d3='',$d4='')
		{
			$My = new \App\Models\Brapci\MyFiles();
			$My->preview($d1,$d2,$d3,$d4);
		}

	public function tools($d1='',$d2='',$d3='',$d4='')
		{
			$cab = $this->cab("user");
			$My = new \App\Models\Brapci\MyFiles();
			$sa = $My->tools($d1,$d2,$d3,$d4);			
			$sx = $cab.$sa.$this->cab("footer");
			return $sx;
		}		

	public function admin($d1='',$d2='',$d3='',$d4='',$d5='')
	{
		//
		$tela = $this->cab("user");
		switch($d1)
			{
				case 'temp':
					$Temp = new \App\Models\XXX\Index();
					$tela .= $Temp->index($d2,$d3,$d4,$d5);
					break;
				case 'ckan':
					$Ckan = new \App\Models\Ckan\Index();
					$tela .= $Ckan->index($d2,$d3,$d4,'');
					break;				
				case 'authority':
					$Authority = new \App\Models\Authority\Index();
					$tela .= $Authority->index($d2,$d3,$d4,'');
					break;
				case 'lattes':
					$Lattes = new \App\Models\Lattes\Index();
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
				case 'oai':
					$Oaipmh = new \App\Models\Oaipmh\Index();
					$tela .= $Oaipmh->index($d2,$d3,$d4,$d5);
					break;
				case 'issue':
					$JournalIssue = new \App\Models\Journal\JournalIssue();
					switch($d2)
						{
						case 'delete':
							$tela .= $JournalIssue->issue_trash($d3,$d4);
							break;
						case 'check':
							$d3 = round($d3);
							$tela .= $JournalIssue->check_issue($d3);
							break;
						case 'join':
							$issue = get("issue");
							if (round($issue) > 0)
								{
									$tela .= $JournalIssue->join_issue($d3,$issue);
								} else {
									$tela .= bs(bsc(bsmessage(lang("brapci.issue_not_found"))));
								}
							
							break;
						}
					break;
				default:
						$sxa = bsc(bsmessage("Comando não informado ou inválido - ".$d1,3),12);
						$tela .= bs($sxa);
						break;
					
			}
		$tela .=$this->cab('footer');
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

	public function pq($d1='',$d2='',$d3='',$d4='')
	{
		$dt['collection'] = mb_strtoupper(lang('brapci.base_pq'));
		$sx = $this->cab("user",$dt);

		$PQ = new \App\Models\PQ\Index();
		$sx .= $PQ->Index($d1,$d2,$d3,$d4);
		$sx .= $this->cab("footer");
		return $sx;
	}

	public function patent($d1='',$d2='',$d3='',$d4='')
	{
		$dt['collection'] = mb_strtoupper(lang('brapci.PatentBR'));
		$tela = $this->cab("user",$dt);

		$Patent = new \App\Models\Patent\Index();
		$tela .= $Patent->Index($d1,$d2,$d3,$d4);
		return $tela;
	}	

	public function authority($d1='',$d2='',$d3='',$d4='')
	{
		$Authority = new \App\Models\Authority\Index();
		$sx = $this->cab("user");
		$sx .= $Authority->index($d1,$d2,$d3,$d4);
		$sx .= $this->cab("footer");
		return $sx;
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
		$Ontology = new \App\Models\Rdf\RDFOntology();
		$tela = $this->cab();		
		$tela .= bs($Ontology->index($d1,$d2,$d3,$d4));
		$tela .= $this->cab("footer");		
		return $tela;
	}	

	function dataverse($d1='',$d2='',$d3='',$d4='')
	{
		$Dataverse = new \App\Models\Dataverse\Index();
		$tela = $this->cab();		
		$tela .= bs($Dataverse->index($d1,$d2,$d3,$d4));
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
		$this->Socials = new \App\Models\Socials();
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

	function elasctic($d1='',$d2='',$d3='',$d4='')
	{
		$sx = $this->cab();
		$ElastichSerach = new \App\Models\ElasticSearch\Index();
		$sx .= $ElastichSerach->index($d1,$d2,$d3,$d4);
		return $sx;
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
