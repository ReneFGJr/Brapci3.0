<?php

namespace App\Models;

use CodeIgniter\Model;

class RDF_Place extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'rdfplaces';
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

	function check($place)
		{
			$RDFConcept = new \App\Models\RDFConcept();
			$dt['Class'] = 'brapci:PlaceCity';
			$dt['Literal']['skos:prefLabel'] = $place;
			$IDP = $RDFConcept->concept($dt);

			return $IDP;
		}	
}
