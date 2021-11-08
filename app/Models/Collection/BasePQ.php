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
				switch($d1)
					{
						case 'viewid':
							$tela .= $this->viewid($d2);
						break;

						default:
							$tela = $this->tableview();
							break;
					}	
				return $tela;
			}

		function viewid($id)
			{
				$tela = '';
				$dt = $this->find($id);
				print_r($dt);
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
