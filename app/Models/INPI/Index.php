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
			if ($d1=='') { $d1 = $d2; $d2 = $d3; $d3 = $d4; $d4 = ''; }
			switch($d1)
				{
					case 'viewid':
						$sx .= $this->viewid($d2,$d3,$d4);
						break;
					case 'harvesting':
						$sx .= $this->harvesting($d2,$d3,$d4);
						break;
					case 'edit':
						$sx .= $this->edit($d2,$d3,$d4);
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
						$sx .= '<li>'.anchor(PATH.'patent/inpi/harvesting','Harvesting').'</li>';
						$sx .= '<li>'.anchor(PATH.'patent/inpi/process/1','Process Authority').'</li>';
						$sx .= '<li>'.anchor(PATH.MODULE.'patent/authority','Authority').'</li>';
						
						$sx .= '</ul>';
				}
			return $sx;
		}

	function edit($d2)
		{
			$AuthorityNames = new \App\Models\Authority\AuthorityNames();
			$AuthorityNames->table = 'brapci_inpi.AuthorityNames';
			$AuthorityNames->path = base_url(PATH. MODULE . '/inpi/');
			$AuthorityNames->path_back = base_url(PATH. MODULE . '/inpi/authority');
			$AuthorityNames->id = $d2;
			$sx = $AuthorityNames->edit($d2);			
			return $sx;
		}	
		
	function viewid($id)
		{
			$AuthorityNames = new \App\Models\Authority\AuthorityNames();
			$AuthorityNames->table = 'brapci_inpi.AuthorityNames';
			$sx = $AuthorityNames->view($id);	
			return $sx;
		}

	function tableView()
		{
			$AuthorityNames = new \App\Models\Authority\AuthorityNames();
			$AuthorityNames->table = 'brapci_inpi.AuthorityNames';
			$AuthorityNames->path = base_url(PATH. MODULE . 'patent/authority/');
			$sx = tableView($AuthorityNames);			
			return $sx;
		}

	function harvesting($d1,$d2,$d3)
		{
			$sx = '';
			$Patent = new \App\Models\INPI\HarvestingPatent();
			$sx .= $Patent->harvesting();
			return $sx;
		}
}
