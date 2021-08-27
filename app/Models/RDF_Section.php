<?php

namespace App\Models;

use CodeIgniter\Model;

class RDF_Section extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'oai_setspec';
	protected $primaryKey           = 'id_ss';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_ss','ss_journal','ss_issue','ss_ref','ss_name'
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

	function check($name,$jnl=0)
		{
			$RDFConcept = new \App\Models\RDFConcept();

			$this->where('ss_ref',$name);
			if ($jnl > 0)
				{
					$this->where('ss_journal',$jnl);
				}
			$dts = $this->first();
			
			$section = $ID = str_pad($dts['ss_journal'],5,'0', STR_PAD_LEFT).'-'.$dts['ss_ref'];
			$dt['Class'] = 'brapci:Section';
			$dt['Literal']['skos:prefLabel'] = $dts['ss_name'];			
			$dt['Literal']['brapci:codeLabel'] = $section;
			//$dt['Literal']['brapci:journalSection'] = $dts['ss_journal'];
			//$dt['Literal']['brapci:journalIssueSection'] = $dts['ss_issue'];

			return $RDFConcept->concept($dt);
		}
}
