<?php

namespace App\Models\RDF;

use CodeIgniter\Model;

class RDFConcept extends Model
{
	var $DBGroup              = 'rdf';
	protected $table                = PREFIX.'rdf_concept';
	protected $primaryKey           = 'id_cc';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_cc','cc_class','cc_pref_term','cc_use',
		'cc_origin','cc_library'
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

	function like($t,$class)
		{
			if ($class != sonumero($class))
				{
					$RDFClass = new \App\Models\Rdf\RDFClass();
					$class = $RDFClass->class($class,false);
				}

			$cp = 'id_cc, n_name, cc_use';
			$sql = "select $cp from ".$this->table." ";
			$sql .= "left join rdf_name ON cc_pref_term = rdf_name.id_n";
			$sql .= " where (cc_class = ".$class.') ';
			$sql .= " and (n_name like '%".$t."%') ";
			$sql .= " order by n_name";
			$sql .= " limit 100";
			$dt = $this->query($sql)->getResult();
			$dt = (array)$dt;
			return $dt;
		}

	function le($id)
		{
			$this->join(PREFIX.'rdf_name', 'cc_pref_term = rdf_name.id_n', 'LEFT');
			$this->join(PREFIX.'rdf_class', 'rdf_concept.cc_class = rdf_class.id_c', 'LEFT');
			$this->join(PREFIX.'rdf_prefix', 'rdf_class.c_prefix = rdf_prefix.id_prefix', 'LEFT');

			$this->select('rdf_class.c_class, rdf_class.id_c, rdf_class.c_type, rdf_class.c_url, rdf_class.c_equivalent');
    		$this->select('rdf_name.n_name, rdf_name.n_lang');    		
			$this->select('rdf_prefix.prefix_ref, rdf_prefix.prefix_url');
			$this->select('rdf_concept.*');
			$this->where('id_cc',$id);
			$dt = $this->First();
			//echo $this->db->getLastQuery();
			return($dt);
		}

	function concept($dt)
		{		
			$Language = new \App\Models\AI\NLP\Language();

			/* Definição da Classe */
			$Class = new \App\Models\Rdf\RDFClass();			
			$Class->DBGroup = $this->DBGroup;
			$RDFdata = new \App\Models\Rdf\RDFData();
			$RDFdata->DBGroup = $this->DBGroup;
			$RDFLiteral = new \App\Models\Rdf\RDFLiteral();
			$RDFLiteral->DBGroup = $this->DBGroup;
			$Property = new \App\Models\Rdf\RDFClassProperty();
			$Property->DBGroup = $this->DBGroup;

			$cl = $dt['Class'];
			$id_class = $Class->class($cl);

			$lang = $Language->getTextLanguage($dt['Literal']['skos:prefLabel']);
			$id_prefTerm = $RDFLiteral->name($dt['Literal']['skos:prefLabel'],$lang);

			/************************************************************* CREATE CONCEPT */
			$dtc = $this
						->where('cc_class',$id_class)
						->where('cc_pref_term',$id_prefTerm)
						->where('cc_library',LIBRARY)
						->First();

			/* Novo */
			if (!is_array($dtc))
				{
					$data['cc_class'] = $id_class;
					$data['cc_pref_term'] = $id_prefTerm;
					$data['cc_use'] = 0;
					$data['cc_origin'] = '';
					$data['cc_library'] = LIBRARY;
					$this->insert($data);

					$dtc = $this
						->where('cc_class',$id_class)
						->where('cc_pref_term',$id_prefTerm)
						->where('cc_library',LIBRARY)
						->First();					
				}

			$id_concept = $dtc['id_cc'];

			if ($id_concept > 0)
				{
					/******************************************************** Literal */
					if (isset($dt['Literal']))
						{
							foreach($dt['Literal'] as $prop => $name)
							{
								$id_prop = $Class->class($prop);
								$idl = $RDFLiteral->name($name);
								$data = array();
								$data['d_r1'] = $id_concept;
								$data['d_p'] = $id_prop;
								$data['d_r2'] = 0;
								$data['d_literal'] = $idl;
								$data['d_library'] = LIBRARY;
								$RDFdata->check($data);
							}
						}
					
					/******************************************************** Relations */					
					if (isset($dt['Relation']))
						{
							foreach($dt['Relation'] as $prop => $id_relation)
							{
								$id_prop = $Class->class($prop);
								$data = array();
								$data['d_r1'] = $id_concept;
								$data['d_p'] = $id_prop;
								$data['d_r2'] = $id_relation;
								$data['d_literal'] = 0;
								$data['d_library'] = LIBRARY;
								$RDFdata->check($data);
							}
						}						
				}
			return $id_concept;
		}
}
