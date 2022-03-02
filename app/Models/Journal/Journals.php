<?php

namespace App\Models\Journal;

use CodeIgniter\Model;

class Journals extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci.source_source';
	protected $primaryKey           = 'id_jnl';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_jnl','jnl_name','jnl_name_abrev',
		'jnl_issn','jnl_eissn','jnl_periodicidade',
		'jnl_ano_inicio','jnl_ano_final','jnl_url',
		'jnl_url_oai','jnl_oai_from','jnl_cidade',
		'jnl_scielo','jnl_collection','jnl_active',
		'jnl_historic','jnl_frbr'
	];

	protected $viewFields        = [
		'id_jnl','jnl_name','jnl_name_abrev',
		'jnl_issn'
	];	

	protected $typeFields        = [
		'hidden','string:100:#','string:20:#',
		'string:20:#','string:20','op: & :Q&Quadrimestral:S&Semestral:A&Anual:F&Continuos FLuxo',
		'year','year','string:20',
		'string:20','string:20','string:20',
		'sn','string:20','sn',
		'sn','string:20'
	];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'create_at';
	protected $updatedField         = 'update_at';
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

	function index($d1,$d2,$d3)
		{
			$this->path = base_url(PATH.MODULE.'/index/');
			$this->path_back = base_url(PATH.MODULE.'/index/');

			switch ($d1)
				{
					/******************* Validade ******/
					default:
						$sx = $this->menu();						
						break;

					case 'menu':
						$sx = $this->menu();
						break;

					case 'tableview':
						$sx = $this->tableview();
						break;
						
					case 'inport_rdf':
						$JournalIssue = new \App\Models\Journal\JournalIssue();
						$sx = $JournalIssue->inport_rdf($d2,$d3);
						break;			
									
					/******************* Implementando */
					case 'issue':
						$sx = $this->issue($d1,$d2,$d3);
						break;

					case 'harvesting':
						$sx = 'Harvesting';
						break;
						
					case 'issue_harvesting':
						$JournalIssue = new \App\Models\Journal\JournalIssue();
						$sx = $JournalIssue->harvesting_oaipmh($d2,$d3);
						break;		

										
					/******************* Para testes ***/
					case 'edit_issue':
						$sx = $this->editar_issue($d2,$d3);
						break;							
					case 'oai_check':				
						$sx = $this->oai_check();
						break;	
					case 'edit':
						$sx = $this->editar($d2);
						break;
					case 'viewid':
						$sx = $this->viewid($d2);
						break;
					case 'view_issue':
						$sx = $this->view_issue_id($d2);
						break;						
					case 'oai':
						$sx = $this->oai($d2,$d3);
						break;						
					case 'edit':
						break;						
				}
			return $sx;
		}
	function menu()
		{
			$sx = '';
			$items = array();
			$mod = $this->MOD();
			$items['admin/'.$mod.'/tableview'] = 'TableView';
			foreach($items as $it=>$tx)
				{
					$link = '<a href="'.PATH.MODULE.$it.'">'.$tx.'</a>';
					$sx .= '<li>'.$link.'</li>';
				}

			$sx .= $this->resume();
			$sx = bs(bsc($sx));
			return $sx;
		}

	/******************************************** RESUME */
	function resume()
		{
			$MOD = $this->MOD(TRUE);
			echo $MOD;
			//$dt = $this->get_resume();
			$total = 0;
			//print_r($dt);
			$sx = '<span class="small">'.lang('brapci.total_journals').'</span>';
			$sx .= h($total,1);
			return $sx;
		}
	function le_rdf($id)
		{
			$dt = $this->where('jnl_frbr',$id)->FindAll();
			return $dt;
		}
	function oai($jnl,$id)
		{
			$sx = '';
			$OaipmhRegister = new \App\Models\Oaipmh\OaipmhRegister();
			switch ($id)
				{
					case '0':
					$idr = $OaipmhRegister->process_00($jnl);
					$sx .= '==>'.$idr;

					case '1':
					$idr = $OaipmhRegister->process_01($jnl);
					$sx .= '==>'.$idr;
					break;
				}
			
			
			return $sx;
		}
	function oai_check()
		{
			$JournalIssue = new \App\Models\Journal\JournalIssue();
			$sx = $JournalIssue->oai_check();
			return $sx;
		}

	function issue($th,$d2,$d3)
		{
			$JournalIssue = new \App\Models\Journal\JournalIssue();
			$sx = $JournalIssue->ArticlesIssue($d2);
			return $sx;
		}

	/*******************************************************/
	function editar_issue($id,$jnl)
		{
			$JournalIssue = new \App\Models\Journal\JournalIssue();
			$sx = $JournalIssue->edit($id,$jnl);
			return $sx;
		}

	/*******************************************************/
	function editar($id)
		{
			$this->id = $id;
			if ($id > 0)
				{
					$dt = $this->find($id);					
					$sx = h($dt['jnl_name'],1);
				} else {
					$sx = h(lang('Editar'),1);
				}
			
			$sx .= form($this);
			$sx = bs(bsc($sx,12));
			return $sx;
		}
	function start_end($dt)
		{
			$sx = '';
			$ini = $dt['jnl_ano_inicio'];
			$fim = $dt['jnl_ano_final'];
			$sx = $ini;
			if ($fim > 1900)
				{
					$sx .= '-'.$fim;
				} else {
					$sx .= '-'.lang('brapci.Actual');
				}
			return $sx;
		}
	function openaccess($dt)
		{
			$sx = '';
			$sx .= '<img src="'.base_url(URL.'/img/icones/open_access.png?v0.').'" class="img-fluid" title="'.lang('brapci.icone_open_access').'">';
			return $sx;
		}
	function active($dt)
		{			
			$sx = '';
			if ($dt['jnl_historic'] == 1)
			{
				$sx .= '<span style="color: red">';
				$sx .= bsicone('off',24);
				$sx .= '</span>';
				$sx .= ' '.lang('brapci.journal_descontinue');
			} else {
				if ($dt['jnl_active'] == '1')
					{
						$sx .= '<span style="color: green">';
						$sx .= bsicone('on',24);
						$sx .= '</span>';
						$sx .= ' '.lang('brapci.journal_active');
						
					} else {
						$sx .= '<span style="color: red">';
						$sx .= bsicone('off',24);
						$sx .= '</span>';
						$sx .= ' '.lang('brapci.journal_inative');
					}
			}
		return $sx;
		}	

	function url($dt)
		{
			$sx = '';
			if (strlen($dt['jnl_url']) != '')
				{
					$sx = '<a href="'.$dt['jnl_url'].'" target="_new'.$dt['id_jnl'].'" class="btn-outline-primary rounded-3 p-2">'.bsicone('url',24).' ';
					$sx .= lang('brapci.journal_site');
					$sx .= '</a>';
				}
			return $sx;
		}

	function issn($dt)
		{
			//https://portal.issn.org/resource/ISSN/xxxx-xxxx
			$sx = '';
			$url = $link = '<a href="https://portal.issn.org/resource/ISSN/$issn" target="new_.$issn." class="btn-outline-primary rounded-3 p-2">'.bsicone('url',24).' $issn</a>';
			if ($dt['jnl_issn'] != '')
				{
					$issn = $dt['jnl_issn'];
					$link = troca($url,'$issn',$issn);
					$sx .= 'ISSN: '.$link;
				}
				if ($dt['jnl_eissn'] != '')
				{
					$sx .= ' - ';
					$issn = $dt['jnl_eissn'];
					$link = troca($url,'$issn',$issn);
					$sx .= 'eISSN: '.$link;
				}
			return $sx;
		}

	function view_issue_id($id)
		{
			$JournalIssue = new \App\Models\Journal\JournalIssue();
			$dd = $JournalIssue->find($id);
			$dt = $this->find($dd['is_source']);
			$sx = bs($this->journal_header($dt));

			return $sx;
		}
	
	function header($dt,$resume=true)
		{
			return $this->journal_header($dt,$resume);
		}

	function journal_header($dt,$resume=true)
		{
			if (!is_array($dt)) { $sx = bsmessage('Erro de identificação do ISSUE/Jornal',3); return $sx; exit; }

			$idj = $dt['jnl_frbr'];
			$this->Cover = new \App\Models\Journal\Cover();	
			$img = '<img src="'.$this->Cover->image($dt['id_jnl']).'" class="img-fluid">';
			$sx = '';
			$url = PATH.MODULE.'v/'.$idj;
			$jnl = h(anchor($url,$dt['jnl_name']),3);

			$jnl .= '<div class="row">';
			$jnl .= bsc($this->start_end($dt),4);
			$jnl .= bsc($this->issn($dt),8);
			$jnl .= bsc($this->url($dt),4);
			$jnl .= bsc($this->active($dt),8);
			$jnl .= '</div>';			

			if ($resume)
			{
				$Oaipmh = new \App\Models\Oaipmh\Oaipmh();				
				$jnl .= '<div class="row mt-5" style="border-bottom: 2px solid #888">';
				$jnl .= bsc('<img src="'.base_url('img/icones/oaipmh.png').'" class="img-fluid p-4">',2);
				$jnl .= $Oaipmh->resume($idj);
				$jnl .= '</div>';	
			}
			
			$openaccess = $this->openaccess($dt);
			
			$sx = bsc($jnl,10);
			$sx .= bsc($openaccess,1,'p-4');
			$sx .= bsc($img,1,'p-2');
			$sx = bs($sx);	

			return $sx;
		}

	function viewid($id)
		{
			$sx = '';
			$dt = $this->find($id);		

			/************** ISSUES */
			$JournalIssue = new \App\Models\Journal\JournalIssue();
			$jn_rdf = $dt['jnl_frbr'];

			$sx = $this->journal_header($dt);

			/************************************************* Mostra edições */
			$sx .= $JournalIssue->view_issue($jn_rdf);

			/********************************************** Botoes de edições */
			$sx .= bs(bsc($JournalIssue->btn_new_issue($dt),12,'mt-4'));

			return $sx;
		}
	function MOD($simple=false)
		{
			$url = $_SERVER['REQUEST_URI'];
			if (strpos($url,'proceeding') > 0)
				{
					$MOD = 'proceeding';
					if ($simple) { $MOD = 'EV'; }
				} else {
					$MOD = 'journal';
					if ($simple) { $MOD = 'JA'; }
				}
			return $MOD;
		}
	/******************************************** MOSTRA LISTA DE PUBLICAÇÕES */
	function tableview()
		{	
			$MOD = $this->MOD();
			switch($MOD)
				{
				case 'proceeding':
					$this->where("jnl_collection = 'EV'");
					break;
				default:
					$this->where("jnl_collection = 'JA'");
					break;
				}	
			$this->path = (PATH.MODULE.'admin/'.$MOD);
			$sx = tableview($this);
			$sx = bs(bsc($sx,12));
			return $sx;
		}
}
