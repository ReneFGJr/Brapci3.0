<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class V extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'vs';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	function index($th,$id,$act='')
		{			
			$this->Socials = new \App\Models\Socials();			
			$Checked = new \App\Models\Brapci\Checked();
			$RDF = new \App\Models\Rdf\RDF();
			$dt = $RDF->le($id,1);

			/************************* Header */
			$sx = $th->cab();

			/***************************** void */
			if (!isset($dt['concept']['c_class']))
				{
					return $sx . "ERRO DE ACESSO - ".$id;
					exit;
				}			

			/**************************** REMISSIVA */
			if ($dt['concept']['cc_use'] > 0)
				{
					$id = $dt['concept']['cc_use'];
					$dt = $RDF->le($id,1);
				}			

			$act .= get("action");

			if ($act == 'export')
				{
					$RDF->c($id,true);
				}			

			$class = trim($dt['concept']['c_class']);
			$name = $dt['concept']['n_name'];
		
			$items['home'] = PATH;
			$items[$class] = '';
			$sx .= breadcrumbs($items);

			switch ($class)
				{
					case 'Article':
						$Checked->check($id,100);
						$Articles = new \App\Models\Journal\Articles();
						$sx .= $Articles->view_articles($id);
						$sx .= bs(bsc($RDF->view_data($id),12));
						break;						

					case 'Proceeding':
						$Checked->check($id,100);
						$Articles = new \App\Models\Journal\Articles();
						$sx .= $Articles->view_articles($id);
						$sx .= bs(bsc($RDF->view_data($id),12));
						break;

					case 'Journal':
						/******************************************* Publicação */
						$Journals = new \App\Models\Journal\Journals();
						$JournalsIssue = new \App\Models\Journal\JournalIssue();
						$dt = $Journals->where('jnl_frbr',$id)->find();
						$dt = $dt[0];
						$adm = false;
						$sx .= $Journals->header($dt,$adm);
						$sx .= $JournalsIssue->view_issue($id);

						//$Articles = new \App\Models\Journal\Articles();
						//$sx .= $Articles->view_articles($id);
						//$sx .= bs(bsc($RDF->view_data($id),12));					
						break;								

					case 'Issue':
						$Bibliometric = new \App\Models\Bibliometric\Bibliometric();
						$sx .= $this->Issue($th,$id,$act);				
						$sx .= $Bibliometric->IssueAuthors($id);
						$sx .= bs(bsc($RDF->view_data($id),12));
						break;	

					case 'Subject':
						$sx .= $this->Subject($th,$id,$dt);
						break;

					case 'IssueProceeding':
						$Bibliometric = new \App\Models\Bibliometric\Bibliometric();						
						$sx .= $this->Issue($th,$id,$act);						
						$sx .= $Bibliometric->IssueAuthors($id);
						$sx .= bs(bsc($RDF->view_data($id),12));
						break;

					case 'CorporateBody':
						//$Articles = new \App\Models\Journal\Articles();
						$sx .= $this->CorporateBody($th,$id,$act);
						break;				
						
					case 'Person':
						helper('highchart');
						$LattesProducao = new \App\Models\Lattes\LattesProducao();
						$AuthorityNames = new \App\Models\Authority\AuthorityNames();
						$Production = new \App\Models\Journal\Production();
						$Bibliometric = new \App\Models\Bibliometric\Bibliometric();
						$Person = new \App\Models\Authority\Person();
						$sx .= $Person->viewid($id);

						$sx .= $Production->person_producer($id);

						$dt = $AuthorityNames->where("a_brapci",$id)->FindAll();
						$dt = $dt[0];

						/* Produção Científica */
						$sx .= $Bibliometric->PersonAuthors($id);

						/* Nuvem de palavras */
						$sx .= $Bibliometric->SubjectAuthors($id);
						
						$st = '<span href="#" onclick="mostrar();">Show Lattes</span>';
						$st .= '<div id="lattes_producao" style="display: none;">';
						$st .= $LattesProducao->producao($dt['a_lattes']);
						$sx .= '</div>';
						$sx .= bs(bsc($st));

						$sx .= '<script>';
						$sx .= 'function mostrar() {';
						$sx .= ' dsp = document.getElementById(\'lattes_producao\').style.display; ';
						$sx .= 'if (dsp == "block") {';
						$sx .= '	document.getElementById(\'lattes_producao\').style.display = "none";';
						$sx .= '} else {';
						$sx .= '	document.getElementById(\'lattes_producao\').style.display = "block";';
						$sx .= '}';
						$sx .= 'alert(ok);';
						$sx .= '}';
						$sx .= '</script>';

						//$Articles = new \App\Models\Journal\Articles();
						$sx .= bsc($this->BibliograficProduction($th,$id,$act),12);

						if  ($this->Socials->getAccess("#ADM"))
						{
							$sx .= bs(bsc($RDF->view_data($id),12));
						}	
						break;				

					default:
						//$sx = h($class,4);
						$sx .= h(lang('rdf.class').': '.$class,6);
						$sx .= h('Method not defined',4);
						$sx .= bs(bsc($sx,12));
						$sx .= bs(bsc($RDF->view_data($id),12));
					break;
				}
			$sx .= bs(bsc($this->bt_export($id).' | '.$this->bt_edit($id),12));
			$sx .= $th->cab('footer');
			return $sx;
		}

		function BibliograficProduction($th,$id,$dt)
			{
				$RDF = new \App\Models\Rdf\RDF();
				$dt = $RDF->le($id);
				$pub = $RDF->recover($dt,'hasAuthor');

				$pubs = array();	
				$authors = array();
				$keywords = array();

				for ($r=0;$r < count($pub);$r++)
					{
						$ida = $pub[$r];
						$dir = $RDF->directory($ida);
						$txt = $RDF->c($ida);
						
						if (!file_exists($dir.'year.nm'))
							{
								echo "<br>OPS - Year not found in ".$dir.' '.$ida;
							} else {
								
							}
						/*********************** RECUPERA ANO */
						if (file_exists($dir.'year.nm'))
							{
								$year = file_get_contents($dir.'year.nm');
							} else {
								$year = 0;
							}
						/******************************* YEAR */
						if (!isset($pubs[$year]))
							{
								$pubs[$year] = array();
							}						
						array_push($pubs[$year],$txt);

						/*********************** RECUPERA AUTHORS */
						if (file_exists($dir.'authors.json'))
							{
								array_push($authors,(array)json_decode(file_get_contents($dir.'authors.json')));
							}						
						/*********************** RECUPERA AUTHORS */
						if (file_exists($dir.'keywords.json'))
							{
								array_push($keywords,(array)json_decode(file_get_contents($dir.'keywords.json')));
							}						
					}
				krsort($pubs);

				/****************************************************************** KEYWORDS *********************/
				$keyw = array();
				for ($r=0;$r < count($keywords);$r++)
					{
						foreach($keywords[$r] as $id=>$key)
							{
								$key = trim($key);
								if (!isset($keyw[$key])) 
									{
										$keyw[$key] = 1;
									} else {
										$keyw[$key]++;
									}
							}
					}

				/****************************************************************** COAUTORES *********************/
				$coauthors = array();
				$coauthorsId = array();
				for ($r=0;$r < count($authors);$r++)
					{
						$authors = (array)$authors;
						foreach($authors[$r] as $id=>$auth)
							{
								$auth = (array)$auth;
								$auth_name = trim($auth['name']);
								$auth_id = trim($auth['id']);

								if (!isset($coauthors[$auth_name])) 
									{
										$coauthors[$auth_name] = 1;
										$coauthorsId[$auth_name] = $auth_id;;
									} else {
										$coauthors[$auth_name]++;
									}
							}
					}	
				ksort($coauthors);		
				$co = h('Coauthors',4);		
				$co .= '<ul class="nolist">';
				foreach($coauthors as $name=>$total)
					{
						$link = $RDF->link(array('id_cc'=>$coauthorsId[$name]));
						$linka = '</a>';
						$co .= '<li>'.$link.$name.$linka.' ('.$total.')</li>';
					}
				$co .= '</ul>';

				$txt = '';
				$xyear = '';
				$tot = 0;
				foreach($pubs as $year=>$works)
					{
						if ($xyear != $year)
							{
								$txt .= h($year,2);
								$xyear = $year;
							} 
						for ($r=0;$r < count($works);$r++)
							{
								$txt .= '<p>'.$works[$r].'</p>';
								$tot++;
							}			
					}

				$txtb = h('Bibliografic Production',4);

				$txt = bs(bsc($txt,8).bsc($co.$txtb,4));
				return $txt;
			}

		function CorporateBody($th,$id,$dt)
			{
				$RDF = new \App\Models\Rdf\RDF();
				$sx = '';
				$CorporateBody = new \App\Models\Authority\CorporateBody();
				$sx .= $CorporateBody->viewid($id);
				$sx .= bs(bsc($RDF->view_data($id),12));
				return $sx;
			}


		function Subject($th,$id,$dt)
			{
				$sx = '';
				$RDF = new \App\Models\Rdf\RDF();

				$Subject = new \App\Models\Authority\Subject();
				$sx .= $Subject->viewid($id);
				return $sx;				
				$dt = $RDF->le($id);

				$sx = '';
				$sx .= bsc(h($dt['concept']['c_class'],6),12);
				$sx .= bsc(h($dt['concept']['n_name'],1),12);
				$sx .= bsc(lang('brapci.total').' <b>'.count($dt['data']).' '.lang('brapci.records').'</b>',12);
				$sx = bs($sx);
				
				$sx .= bs(bsc($RDF->view_data($id),12));
				return $sx;
			}
		function Issue($th,$id,$act)
			{
				$Journal = new \App\Models\Journal\Journals();
				$JournalIssue = new \App\Models\Journal\JournalIssue();	

				$RDF = new \App\Models\Rdf\RDF();
				$dt = $RDF->le($id);
				/* Recupera Rótulo */
				$label = $RDF->c($id);

				/* Recupera Trabalhos */
				$tps = array('hasIssue','hasIssueProceeding');				
				$idj = 0;
				for ($r=0;$r < count($tps);$r++)
					{
						$tp = $tps[$r];
						$idx = $RDF->recover($dt,$tp);
						if (count($idx) > 0)
							{
								$idj = $idx[0];
							}
					}
						
				$dtj = $Journal->where('jnl_frbr',$idj)->findAll();
				$sx = '';			
				if (count($dtj) > 0)
				{				
					$sx .= $Journal->header($dtj[0],false);
					$sx .= $JournalIssue->header($dt);
					$sx .= $this->bt_join_issue($dt);
					$sx .= bs(bsc(h(lang('brapci.works'),2),12));
					$sx .= $JournalIssue->ArticlesIssue($id);
				}
				return $sx;
			}
		function bt_join_issue($dt)
			{
				$RDF = new \App\Models\Rdf\RDF();
				$sx = '';				

				if  ($this->Socials->getAccess("#ADM"))
					{
						$issue_a = $dt['concept']['id_cc'];
						$jnl1 = $RDF->recover($dt,'hasIssue');
						$jnl2 = $RDF->recover($dt,'hasIssueProceeding');
						$jnl = array_merge($jnl1,$jnl2);
						if (count($jnl) > 0)
							{
								$issue = $RDF->le($jnl[0]);
								$issue1 = $RDF->recover($issue,'hasIssue');
								$issue2 = $RDF->recover($issue,'hasIssueProceeding');
								$issue = array_merge($issue1,$issue2);
							
								$sx .= '<form action="'.PATH.MODULE.'admin/issue/join/'.$issue_a.'" method="post">';
								$sx .= '<div class="btn-group" role="group" aria-label="Basic example">';
								$form = '<select width="1" name="issue">';
								for ($r=0;$r < count($issue);$r++)
									{
										/* Diferente do atual Issue */
										if ($issue[$r] != $issue_a)
										{
											$form .= '<option value="'.$issue[$r].'">'.$RDF->c($issue[$r]).' ('.$issue[$r].')</option>';
										}
									}
								$form .= '</select>';
								$sx .= '<span class="me-2">'.lang('brapci.join_with').'</span>';
								$sx .= $form;
								$sx .= '<input type="submit" value="'.lang('brapci.join').'">';
								$sx .= '</div>';
								$sx .= '</form>';

								$sx .= '<hr>';
								$sx .= '<a href="'.PATH.MODULE.'admin/temp/convert_proceeding/'.$issue_a.'">'.lang('brapci.convert_proceeding').'</a>';

								$sx = bs($sx);
							} else {								
								$sx .=  bs(bsc(bsmessage("brapci.issue_not_found"." ".'JNL: empty',3),12));
							}
					}
				return $sx;
			}		
		function bt_export($id)
			{
				$sx = '';
				if  ($this->Socials->getAccess("#ADM"))
				{
					$link = PATH.MODULE.'v/'.$id.'?action=export';
					$sx = '<a href="'.$link.'" class="btn btn-outline-primary btn-sm">'.lang('rdf.export').'</a>';
				}
				return $sx;
			}

			function bt_edit($id)
			{
				$sx = '';
				if  ($this->Socials->getAccess("#ADM"))
				{
				$link = PATH.MODULE.'a/'.$id;
				$sx = '<a href="'.$link.'" class="btn btn-outline-primary btn-sm">'.lang('rdf.edit').'</a>';
				}
				return $sx;
			}			
}
