<?php

namespace App\Models\Journal;

use CodeIgniter\Model;

class Journals extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci.source_source';
	protected $primaryKey           = 'id';
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
			switch ($d1)
				{
					default:
						$tela = $this->tableview();
						break;
				}
			return $tela;
		}

	function tableview()
		{
			$tela = tableview($this);
			$tela = bs(bsc($tela,12));
			return $tela;
		}
}
