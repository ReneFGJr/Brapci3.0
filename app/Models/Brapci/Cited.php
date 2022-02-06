<?php

namespace App\Models\Brapci;

use CodeIgniter\Model;

class Cited extends Model
{
	protected $DBGroup              = 'brapci_cited';
	protected $table                = 'cited_article';
	protected $primaryKey           = 'id_ca';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDeletes       = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'id_ca','ca_id','ca_rdf',
		'ca_journal','ca_journal_origem','ca_year',
		'ca_year_origem','ca_vol','ca_nr',
		'ca_pag','ca_tipo','ca_text',
		'ca_status','ca_ordem'
	];

	protected $typeFields        = [
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

	function cited($idc,$txt,$ord)
		{
			$dt = $this->where('ca_rdf',$idc)->where('ca_text',$txt)->findAll();
			if (count($dt) == 0)
				{
					$data = [
						'ca_rdf' => $idc,
						'ca_text' => $txt,
						'ca_ordem' => $ord,
						'ca_status' => 0
					];
					$this->insert($data);
				}
			return true;
		}

	function show($idc)
		{		
			$sx = '';	
			$dt = $this->where('ca_rdf',$idc)->orderBy('ca_ordem')->findAll();
			if (count($dt) > 0)
				{
					$sx .= h(lang('Brapci.References'),2);
				}
			$sx .= '<ul class="list-group">';
			for ($r=0;$r < count($dt);$r++)
				{
					$sx  .= '<li class="list-group-item">'.$dt[$r]['ca_text'].'</li>';
				}
			$sx .= '</ul>';
			return $sx;
		}
}
