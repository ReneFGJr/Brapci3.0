<?php

namespace App\Models\Authority;

use CodeIgniter\Model;

class AuthotityIds extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = '';
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

	function LattesFindID($id)
		{
			$Api = new \App\Models\Api\Endpoints();
			$AuthorityNames = new \App\Models\Authority\AuthorityNames();

			$dt = $AuthorityNames->find($id);
			$name = trim($dt['a_prefTerm']);

			$dt = $Api->LattesFindID($name);

			echo '<pre style="color: white;">';
			$tela = var_dump($dt);
			return $tela;
		}
}
