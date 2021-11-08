<?php

namespace App\Models\Ai;

use CodeIgniter\Model;

class Research extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'researches';
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

	function index($d1='',$d2='',$d3='')
		{
			$tela = '';
			switch($d1)
				{
					case 'systematic_review':
						$SystematicReview = new \App\Models\AI\Research\SystematicReview();
						$tela .= $SystematicReview->index($d1,$d2,$d3);						
						break;
					case 'pq':
						$BasePQ = new \App\Models\Collection\BasePQ();
						$tela .= $BasePQ->index($d1,$d2,$d3);						
						break;						
					default:
						$tela .= bsmessage('Service not found: '.$d1,2);
						break;
				}
			return $tela;		
		}	
}
