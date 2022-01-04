<?php

namespace App\Models\Patent;

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

	function cab()
		{
			$sx = bsc('<img src="'.URL.'img/logo/patent_br.png" class="img-fluid"',2);
			$sx = bs($sx);
			return $sx;
		}

	function status()
		{
			
		}

	function index($d1,$d2,$d3,$d4)
	{
		$sx = $this->cab();
		switch($d1)
		{
			case 'authority':
				$INPI = new \App\Models\INPI\Index();
				$sx .= $INPI->index($d2,$d3,$d4,'');
				break;
			default:
				$sx .= '<ul>';				
				$sx .= '<li>'.anchor(PATH.MODULE.'patent/authority','Autoridades').'</li>';
				$sx .= '</ul>';
				break;
		}
		return $sx;
	}	
}
