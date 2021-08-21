<?php

namespace App\Models;

use CodeIgniter\Model;

class RDF extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'rdfs';
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

	function index($d1,$d2,$d3='',$cab='')
		{
			$sx = '';
			switch($d1)
				{
					case 'inport':
						$sx = $cab;
						$this->RDFPrefix = new \App\Models\RDFPrefix();
						$sx .= $this->RDFPrefix->inport();						
					break;
					default:
						$sx = $cab;
						$sx .= lang('command not found').': '.$d1;
						$sx .= '<ul>';
						$sx .= '<li><a href="'.base_url(PATH.'rdf/inport').'">'.lang('Inport').'</a></li>';
						$sx .= '</ul>';
				}
			$sx = bs($sx);
			return $sx;
		}
}
