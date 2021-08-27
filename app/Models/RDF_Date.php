<?php

namespace App\Models;

use CodeIgniter\Model;

class RDF_Date extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'rdfdates';
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

	function check($data)
		{
			$RDFConcept = new \App\Models\RDFConcept();
			$IDY = 0;
			$year = substr($data,0,4);
			$month = substr($data,4,2);
			$day = substr($data,6,2);

			if (strlen($year) == 4)
				{
					/************* Somente Ano */
					$dt['Class'] = 'brapci:Year';
					$dt['Literal']['skos:prefLabel'] = $year;
					$IDY = $RDFConcept->concept($dt);					
				}
			return $IDY;
		}
}
