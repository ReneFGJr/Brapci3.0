<?php

namespace App\Models\INPI;

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

	function index($d1,$d2,$d3,$d4)
		{
			$sx = '';
			switch($d1)
				{
					case 'harvesting':
						$sx .= $this->harvesting($d2,$d3,$d4);
						break;
					case 'authority':
						$sx .= $this->tableView();
						break;
					case 'process':
						$HarvestingPatent = new \App\Models\INPI\HarvestingPatent();
						$sx .= $HarvestingPatent->process($d2,$d3,$d4);
						break;						
					default:
						$sx = bsmessage('Not locate action - '.$d1);
						$sx .= '<ul>';
						$sx .= '<li>'.anchor(PATH.MODULE.'inpi/harvesting','Harvesting').'</li>';
						$sx .= '<li>'.anchor(PATH.MODULE.'inpi/process/1','Process Authority').'</li>';
						$sx .= '<li>'.anchor(PATH.MODULE.'inpi/authority','Authority').'</li>';
						
						$sx .= '</ul>';
				}
			return $sx;
		}

	function tableView()
		{
			$AuthorityNames = new \App\Models\Authority\AuthorityNames();
			$AuthorityNames->table = 'brapci_inpi.AuthorityNames';
			$AuthorityNames->path = base_url(PATH. MODULE . '/index/');
			$sx = tableView($AuthorityNames);			
			return $sx;
		}

	function harvesting($d1,$d2,$d3)
		{
			$sx = $d1;
			$Patent = new \App\Models\INPI\HarvestingPatent();
			$sx .= $Patent->harvesting();
			return $sx;
		}
}
