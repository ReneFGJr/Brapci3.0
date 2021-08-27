<?php

namespace App\Models;

use CodeIgniter\Model;

class RDF_Authority extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = '';
	protected $primaryKey           = '';
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

	function prepare($a)
		{
			$Class = new \App\Models\RDFClass();
			$C = new \App\Models\RDFConcept();

			/*************************************** Prepara variavel de autores */
			$auth = array();
			if (!is_array($a))
			{
				$a = array($a);
			}
			/**************************************** Processando Autores DC ******/
			for ($r=0;$r < count($a);$r++)
			{
				$au = $a[$r];
				$af = '';	

				/************************ Vreifica se tem afliação institucional **/						
				if (strpos($au,';') > 0)
					{
						$af = trim(substr($au,strpos($au,';')+1,strlen($au)));
						$au = trim(substr($au,0,strpos($au,';')));
					}

				/****************************************** Prepara a Classe */
				$c['Class'] = 'foaf:Person';
				$c['Literal']['skos:prefLabel'] = nbr_author($au,1);
				$c['Literal']['skos:altLabel'] = nbr_author($au,7);

				if (strlen($af) > 0)
					{
						$c['Propriety']['dbpedia:affiliation'] = nbr_author($af,7);
					}
				$a[$r] = $C->concept($c);				
			}
			return $a;
		}
}
