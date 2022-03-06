<?php

namespace App\Models\Journal;

use CodeIgniter\Model;

class Production extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'produtions';
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

	function person($id)
		{
			$RDF = new \App\Models\RDF\Rdf;
			$Timeline = new \App\Models\Brapci\Timeline();
			$dt = $RDF->le($id);

			$art = $RDF->recover($dt,'hasAuthor');
			$sx = $Timeline->timeline($art);
			return $sx;
		}
}
