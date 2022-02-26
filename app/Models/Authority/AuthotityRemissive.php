<?php

namespace App\Models\Authority;

use CodeIgniter\Model;

class AuthotityRemissive extends Model
{
	protected $DBGroup              = 'rdf';
	protected $table                = 'rdf_concept';
	protected $primaryKey           = 'id_cc';
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

	function remissive_author($id)
		{
			$this->join('rdf_name','rdf_name.id_n = rdf_concept.cc_pref_term','left');
			$this->where('cc_use',$id);
			$dt = $this->findAll();
			return $dt;
		}
}
