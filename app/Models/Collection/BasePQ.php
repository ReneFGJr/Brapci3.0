<?php

namespace App\Models\Collection;

use CodeIgniter\Model;

class BasePQ extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_pq.bolsistas';
	protected $primaryKey           = 'id_bs';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_bs','bs_nome','bs_rdf_id','bs_lattes'
	];
	protected $typeFields        = [
		'hidden','string:100','hidden','string:50'
	];

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
	
		function index($d1='',$d2='',$d3='')
			{
				$tela = '';
				switch($d2)
					{
						case 'viewid':
							$tela .= $this->viewid($d3);
						break;

						default:
							$tela = $this->tableview();
							break;
					}	
				return $tela;
			}
		// http://brapci3/ai/research/pq/viewid/1
		function viewid($id)
			{
				$tela = $id;
				$dt = $this->find($id);

				$AuthorityNames = new \App\Models\Authority\AuthorityNames();
				$da = $AuthorityNames->where('a_prefTerm',$dt['bs_nome'])->findAll();
				if (count($da) == 0)
					{
						$d['a_class'] = 'P';
						$d['a_uri'] = 'htps://brapci.inf.br/index.php/res/v/'.$dt['bs_rdf_id'];
						$d['a_use'] = 'P';
						$d['a_prefTerm'] = $dt['bs_nome'];
						$d['a_lattes'] = $dt['bs_lattes'];
						$d['a_brapci'] = $dt['bs_rdf_id'];
						$d['a_orcid'] = 'P';			
						$AuthorityNames->insert($d);
						$da = $AuthorityNames->where('a_prefTerm',$dt['bs_nome'])->findAll();
					}
				$url = 'htps://brapci.inf.br/index.php/res/v/'.$dt['bs_rdf_id'];
				$url_lattes = 'hhttp://lattes.cnpq.br/'.$dt['bs_lattes'];
				$da = $da[0];
				$url_brapci = URL.'authority/index/viewid/'.$da['id_a'];
				$tela = bsc(h($dt['bs_nome'],1),12);
				$tela .= bsc('<small>'.anchor($url).'</small>',12);
				$tela .= bsc('<small>'.anchor($url_lattes).'</small>',12);
				$tela .= bsc('<small>'.anchor($url_brapci).'</small>',12);

				return $tela;
			}

		function edit($id)
			{
				$this->path = PATH.MODULE.'research/pq';
				$this->path_back = PATH.MODULE.'research/pq';
				$tela = form($this);
				return $tela;
			}

		function tableview()
			{
				$this->path = PATH.MODULE.'research/pq';
				$tela = tableview($this);
				return $tela;
			}	

}
