<?php

namespace App\Models\Authority;

use CodeIgniter\Model;

class AuthorityNames extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'brapci_authority.AuthorityNames';
	protected $primaryKey           = 'id_a';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_a','a_prefTerm','a_class','a_uri','a_use'
	];

	protected $typeFields        = [
		'hidden',
		'string:100',
		'hidden',
		'string:100',
		'string:1'
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
}
