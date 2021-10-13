<?php

namespace App\Models\AI;

use CodeIgniter\Model;

class Title extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'titles';
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

	function check($txtf,$id)
		{
			foreach($txtf as $lang=>$txt)
				{
					$DOI = $this->DOI($txt);
				}
		}
	
	function DOI($txt)
		{
			if (strpos(' '.$txt,'DOI:') > 0)
				{
					$doi = substr($txt,strpos($txt,'DOI:'),strlen($txt));
					return $doi;
				} else {
					return '';
				}
		}
}
