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
			$Checked = new \App\Models\Brapci\Checked();
			$RDF = new \App\Models\Rdf\RDF();

			if ($act == 'export')
				{
					$RDF->c($id,true);
				}

			$sx = $th->cab();			
			$dt = $RDF->le($id,1);

			if (!isset($dt['concept']['c_class']))
				{
					return "ERRO DE ACESSO - ".$id;
					exit;
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
						$sx .= $this->Issue($th,$id,$act);
						//$JournalIssue = new \App\Models\Journal\JournalIssue();
						//$sx .= bs(bsc(h('Class: '.$class,2),12));
						//$sx .= $JournalIssue->view_issue_articles($id);
						//$sx .= bs(bsc($RDF->view_data($id),12));
						break;	
					case 'Subject':
						$sx .= $this->Subject($th,$id,$dt);
						break;

					case 'IssueProceeding':
						$sx .= $this->Issue($th,$id,$act);
						//$sx .= bs(bsc($RDF->view_data($id),12));
						break;
						
					case 'Person':
						//$Articles = new \App\Models\Journal\Articles();
						$sx .= $this->Person($th,$id,$act);
						$sx .= bs(bsc($RDF->view_data($id),12));
						break;

					default:
						//$sx = h($class,4);
						$sx .= h(lang('rdf.class').': '.$class,6);
						$sx .= h('Method not defined',4);
						$sx .= bs(bsc($sx,12));
						$sx .= bs(bsc($RDF->view_data($id),12));
					break;
				}
			$sx .= bs(bsc($this->bt_export($id),12));
			$sx .= $th->cab('footer');
			return $sx;
		}

		function Person($th,$id,$dt)
			{
				$sx = '';
				$Person = new \App\Models\Authority\Index();
				$sx .= $Person->index('viewid',$id,'','');
				return $sx;
			}

		function Subject($th,$id,$dt)
			{
				$RDF = new \App\Models\Rdf\RDF();
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
					$sx .= bs(bsc(h(lang('brapci.articles'),4),12));
					$sx .= $JournalIssue->ArticlesIssue($id);
				}
				return $sx;
			}
		function bt_export($id)
			{
				$link = URL.'/res/v/'.$id.'/export/';
				$sx = '<a href="'.$link.'" class="btn btn-outline-primary btn-sm" onclick="export_rdf('.$id.');">'.lang('rdf.export').'</a>';
				return $sx;
			}
}
