<?php

namespace App\Models;

use CodeIgniter\Model;

class PqBolsista extends Model
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
		'bs_nome','bs_rdf_id','bs_lattes'
	];

	protected $typeFields        = [
		'hi',
		'st100*',
		'I10',
		'st100'
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
			switch ($d1)
				{
					case '1':
					break;

					default:
					$sx = tableview($this);
					break;

				}
			$sx = bs($sx);

			return $sx;
		}
}
