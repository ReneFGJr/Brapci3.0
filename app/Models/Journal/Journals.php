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
			$this->path = base_url(PATH.'/index/');
			$this->path_back = base_url(PATH.'/index/');

			switch ($d1)
				{
					case 'edit_issue':
						$tela = $this->editar_issue($d2,$d3);
						break;	
					case 'oai_check':				
						$tela = $this->oai_check();
						break;	
					case 'edit':
						$tela = $this->editar($d2);
						break;
					case 'viewid':
						$tela = $this->viewid($d2);
						break;
					case 'oai':
						$tela = $this->oai($d2,$d3);
						break;						
					case 'edit':
						break;						
					default:
						$tela = $this->tableview();
						break;
				}
			return $tela;
		}
	function oai($jnl,$id)
		{
			$tela = '';
			$OaipmhRegister = new \App\Models\Oaipmh\OaipmhRegister();
			switch ($id)
				{
					case '0':
					$idr = $OaipmhRegister->process_00($jnl);
					$tela .= '==>'.$idr;

					case '1':
					$idr = $OaipmhRegister->process_01($jnl);
					$tela .= '==>'.$idr;
					break;
				}
			
			
			return $tela;
		}
	function oai_check()
		{
			$JournalIssue = new \App\Models\Journal\JournalIssue();
			$tela = $JournalIssue->oai_check();
			return $tela;

		}
	function editar_issue($id,$jnl)
		{
			$JournalIssue = new \App\Models\Journal\JournalIssue();
			$tela = $JournalIssue->edit($id,$jnl);
			return $tela;
		}
	function editar($id)
		{
			$this->id = $id;
			if ($id > 0)
				{
					$dt = $this->find($id);					
					$tela = h($dt['jnl_name'],1);
				} else {
					$tela = h(lang('Editar'),1);
				}
			
			$tela .= form($this);
			$tela = bs(bsc($tela,12));
			return $tela;
		}
	function start_end($dt)
		{
			$tela = '';
			$ini = $dt['jnl_ano_inicio'];
			$fim = $dt['jnl_ano_final'];
			$tela = $ini;
			if ($fim > 1900)
				{
					$tela .= '-'.$fim;
				} else {
					$tela .= '-'.lang('brapci.Actual');
				}
			return $tela;
		}
	function openaccess($dt)
		{
			$tela = '';
			$tela .= '<img src="'.base_url(URL.'/img/icones/open_access.png?v0.').'" class="img-fluid" title="'.lang('brapci.icone_open_access').'">';
			return $tela;
		}
	function active($dt)
		{
			
			$tela = '';
			if ($dt['jnl_historic'] == 1)
			{
				$tela .= '<span style="color: red">';
				$tela .= bsicone('off',24);
				$tela .= '</span>';
				$tela .= ' '.lang('brapci.journal_descontinue');
			} else {
				if ($dt['jnl_active'] == '1')
					{
						$tela .= '<span style="color: green">';
						$tela .= bsicone('on',24);
						$tela .= '</span>';
						$tela .= ' '.lang('brapci.journal_active');
						
					} else {
						$tela .= '<span style="color: red">';
						$tela .= bsicone('off',24);
						$tela .= '</span>';
						$tela .= ' '.lang('brapci.journal_inative');
					}
			}
		return $tela;
		}		
	function url($dt)
		{
			$tela = '';
			if (strlen($dt['jnl_url']) != '')
				{
					$tela = '<a href="'.$dt['jnl_url'].'" target="_new'.$dt['id_jnl'].'" class="btn-outline-primary rounded-3 p-2">'.bsicone('url',24).' ';
					$tela .= lang('brapci.journal_site');
					$tela .= '</a>';
				}
			return $tela;
		}

	function issn($dt)
		{
			//https://portal.issn.org/resource/ISSN/xxxx-xxxx
			$tela = '';
			$url = $link = '<a href="https://portal.issn.org/resource/ISSN/$issn" target="new_.$issn." class="btn-outline-primary rounded-3 p-2">'.bsicone('url',24).' $issn</a>';
			if ($dt['jnl_issn'] != '')
				{
					$issn = $dt['jnl_issn'];
					$link = troca($url,'$issn',$issn);
					$tela .= 'ISSN: '.$link;
				}
				if ($dt['jnl_eissn'] != '')
				{
					$tela .= ' - ';
					$issn = $dt['jnl_eissn'];
					$link = troca($url,'$issn',$issn);
					$tela .= 'eISSN: '.$link;
				}
			return $tela;
		}

	function viewid($id)
		{
			$this->Cover = new \App\Models\Journal\Cover();
			$dt = $this->find($id);
			$img = '<img src="'.$this->Cover->image($id).'" class="img-fluid">';
			$tela = '';
			$jnl = h($dt['jnl_name'],3);

			$openaccess = $this->openaccess($dt);
			
			$jnl .= '<div class="row ">';
			$jnl .= bsc($this->start_end($dt),4);
			$jnl .= bsc($this->issn($dt),8);
			$jnl .= bsc($this->url($dt),4);
			$jnl .= bsc($this->active($dt),8);
			$jnl .= '</div>';

			$Oaipmh = new \App\Models\Oaipmh\Oaipmh();
			$idj = $dt['jnl_frbr'];
			$jnl .= '<div class="row mt-5">';
			$jnl .= bsc('<img src="'.base_url('img/icones/oaipmh.png').'" class="img-fluid">',2);
			$jnl .= $Oaipmh->resume($idj);
			$jnl .= '</div>';
			
			$tela = bsc($jnl,9);
			$tela .= bsc($openaccess,1);
			$tela .= bsc($img,2);
			$tela = bs($tela);

			/************** ISSUES */
			$JournalIssue = new \App\Models\Journal\JournalIssue();
			$jn_rdf = $dt['jnl_frbr'];
			$tela .= $JournalIssue->view_issue($jn_rdf);

			$tela .= $JournalIssue->btn_new_issue($dt);

			return $tela;
		}

	function tableview()
		{	
			switch(MOD)
				{
				case 'proceeding':
					$this->where("jnl_collection = 'EV'");
					break;
				default:
					$this->where("jnl_collection = 'JA'");
					break;
				}	
			$this->path = base_url(PATH.MODULE.'/'.MOD.'/index/');
			$tela = tableview($this);
			$tela = bs(bsc($tela,12));
			return $tela;
		}
}
