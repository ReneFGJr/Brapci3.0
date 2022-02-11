<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class Brapci extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapcis';
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

	function link($dt)
		{
			$link = URL .'res/v/' . trim($dt['a_brapci']);
			$link1 = '<a href="' . $link . '" target="_new' . $dt['a_brapci'] . '">';
			$link1 .= '<img src="' . URL.'img/logo/brapci_200x200.png" style="height: 50px">';
			$link1 .= '</a>';
			return $link1;
		}	
}
