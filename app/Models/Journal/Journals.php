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
		'string:20:#','string:20','string:20',
		'string:20','string:20','string:20',
		'string:20','string:20','string:20',
		'string:20','string:20','string:20',
		'string:20','string:20','string:20',
		'string:20','string:20','string:20',
		'string:20','string:20','string:20'
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
					case 'viewid':
						$tela = $this->viewid($d2);
						break;
					case 'edit':
						break;						
					default:
						$tela = $this->tableview();
						break;
				}
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
			
			$jnl .= '<div class="row">';
			$jnl .= bsc($this->start_end($dt),4);
			$jnl .= bsc($this->issn($dt),8);
			$jnl .= bsc($this->url($dt),4);
			$jnl .= bsc($this->active($dt),8);
			$jnl .= '</div>';
			
			$tela = bsc($jnl,10);
			$tela .= bsc($img,2);
			$tela = bs($tela);
			return $tela;
		}

	function tableview()
		{			
			$tela = tableview($this);
			$tela = bs(bsc($tela,12));
			return $tela;
		}
}
