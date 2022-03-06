<?php

namespace App\Models\PQ;

use CodeIgniter\Model;

class Index extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'indices';
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


	function subheader()
		{
			$sx = bsc('<img src="'.URL.'img/logo/base_pq.png" class="img-fluid">',2);
			$sx .= bsc('<h1>Base PQ/CNPq</h1>',10);
			$sx = bs($sx);
			return $sx;
		}

	function index($d1,$d2,$d3,$d4)
	{
		$sx = '';

		switch($d1)
			{
				case 'pq_bolsas':
					$sx .= $this->subheader();
					$sx .= $this->pq_bolsas();
					break;
				break;

				case 'pq_ano':
					$sx .= $this->subheader();
					$sx .= $this->pq_ano();
					break;
				break;

				default:
				$sx .= $this->subheader();
				$sx .= $this->resume();
			}
		$sx = bs($sx);
		return $sx;
	}	

	function pq_bolsas()
		{
			$Bolsa = new \App\Models\PQ\Bolsa();
			$sx = '';
			$sx = $Bolsa->bolsista_list();
			return $sx;
		}
	function pq_ano()
		{
			$Bolsa = new \App\Models\PQ\Bolsa();
			$sx = '';
			$sx = $Bolsa->year_list();
			return $sx;
		}

	function resume()
		{
			$Bolsa = new \App\Models\PQ\Bolsa();
			$Bolsista = new \App\Models\PQ\Bolsista();			

			$bolsas = $Bolsa->resume();

			return $bolsas;
		}
}
